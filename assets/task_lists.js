import $ from 'jquery';
import 'jquery-ui/ui/widgets/sortable';

$(() => {
    // Click handler for links with data-wuwei-load="tasklist-card"
    $('a[data-wuwei-load="tasklist-card"]').on('click', function (e) {
        e.preventDefault();

        const url = $(this).attr('href');
        const $targetDiv = $('#tasklist-card');

        // Add loading indicator
        $targetDiv.html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');

        // Make AJAX request
        $.get(url)
            .done(function (response) {
                $targetDiv.html(response);
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                $targetDiv.html('<div class="alert alert-danger">Error loading content</div>');
                console.error('AJAX request failed:', textStatus, errorThrown);
            });
    });

    // Make the task lists sortable
    $('#sortable-task-lists').sortable({
        handle: '.handlebar', // Use the handlebar as the drag handle
        update: function (event, ui) {
            const sortedIDs = $(this).sortable('toArray', { attribute: 'data-tasklist-id' });

            // Send the new order to the server
            $.ajax({
                url: '/tasklists/update-order',
                method: 'POST',
                data: JSON.stringify({ order: sortedIDs }),
                contentType: 'application/json',
                success: function () {
                    console.log('Order updated successfully');
                },
                error: function (xhr, status, error) {
                    console.error('Error updating order:', error);
                },
            });
        },
    });
});