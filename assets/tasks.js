//assets/task.js
import functions from './functions';

var tasks = document.querySelectorAll('.task');

// Attach event listener to each task
tasks.forEach(function (task) {
    task.addEventListener('dblclick', functions.openElementInNewTab);
});

//jquery document ready
$(document).ready(function () {
        //check for the html element with id eisenhower
        if ($('#eisenhower').length) {
            //get the eisenhower element
            var eisenhower = $('#eisenhower');
            //task cards based on priority and urgency
            var tasks = $('.task');

            //Create draggable elements from the ul with class task-eisenhower-list
            $('.task-eisenhower-list').sortable({
                // items: 'li.task',
                connectWith: '.task-eisenhower-list',
                cursor: 'move',
                placeholder: 'ui-state-highlight',
                forcePlaceholderSize: true,
                update: function (event, ui) {
                    var task = ui.item.children('.task');
                    var task_id = task.data('task-id');
                    //assign urgency from the parent ul
                    var urgency = task.parent().parent().data('urgency');
                    //assign priority from the parent ul
                    var priority = task.parent().parent().data('priority');
                    //check if urgency or priority are undefined
                    if (typeof urgency !== 'undefined' && typeof priority !== 'undefined') {
                        var data = {
                            task_id: task_id,
                            urgency: urgency,
                            priority: priority,
                        };
                        console.log(data);
                        var url = eisenhower.data('url');
                        // replace url 0 with task_id
                        url = url.replace('0', task_id);
                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: data,
                            success: function (response) {
                                task.removeClass('status-danger status-warning status-primary status-default');
                                if (data.urgency && data.priority) {
                                    task.addClass('status-danger');
                                } else if (data.urgency) {
                                    task.addClass('status-warning');
                                } else if (data.priority) {
                                    task.addClass('status-primary');
                                } else {
                                    task.addClass('status-default');
                                }
                            }
                        });
                    }
                }
            });

            //loop through each task card
            tasks.each(function () {
                //get the task card
                var taskCard = $(this);
                //get the task id
                var task_id = taskCard.data('task-id');
                //get the urgency
                var urgency = taskCard.data('urgency');
                //get the priority
                var priority = taskCard.data('priority');
                if (urgency && priority) {
                    //append the task card to the important and urgent ul
                    eisenhower.find('#important-urgent').append(taskCard.parent());
                } else if (priority) {
                    //append the task card to the important and not urgent ul
                    eisenhower.find('#important-not-urgent').append(taskCard.parent());
                } else if (urgency) {
                    //append the task card to the not important and urgent ul
                    eisenhower.find('#not-important-urgent').append(taskCard.parent());
                } else {
                    //append the task card to the not important and not urgent ul
                    eisenhower.find('#not-important-not-urgent').append(taskCard.parent());
                }
                console.log(task_id);
            });

        }
    }
)
;