// var bootstrap = require('bootstrap');

$('.task-checkbox').each(function () {
    $(this).on('change', function () {
        var id = $(this).data('id');
        var completed = $(this).is(':checked');
        var taskDiv = $(this).closest('.task');
        if (completed) {
            var duration = prompt('Duration: ', $(this).data('duration'));
        }
        if (!completed || duration > 0) {
            $.ajax({
                url: '/tasks/' + id + '/completed',
                method: 'POST',
                data: {
                    'completed': completed,
                    'duration': duration
                },
                success: function (response) {
                    $(taskDiv)
                        .data('duration', response.task.duration)
                        .data('completed', response.task.completed);
                    if (response.task.completed) {
                        $(taskDiv)
                            .addClass('completed');
                    } else {
                        $(taskDiv)
                            .removeClass('completed');
                    }
                    console.log('Task completed status updated successfully:', response);
                },
            });
            return true;
        } else {
            alert('Khara');
            return false
        }
    });
});

$(() => {
    $('.tasks').sortable({
        items: '> .task',
        connectWith: '.tasks',
        placeholder: "ui-state-highlight",
        cancel: ".completed",
        handle: ".sortable-handle",
        update: function (event, ui) {
            var task = $(ui.item);
            var id = task.data('id');
            var order = $(this).sortable('toArray', { attribute: 'data-order' });
            var updatedOrder = order.indexOf(task.data('order').toString()) + 1; // Get the new order number for the updated task

            $.ajax({
                url: '/tasks/' + id + '/order',
                method: 'POST',
                data: {
                    'order': order,
                    'updatedOrder': updatedOrder
                },
                success: function (response) {
                    console.log('Task order updated successfully:', response);
                },
                error: function (xhr, status, error) {
                    console.error('Error updating task order:', error);
                }
            });
        }
    }).sortable('option', 'attribute', 'data-order'); // Specify the order attribute
});
