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
});