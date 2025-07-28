console.log('Days script loaded successfully.');
$('.day-check').each(function () {
    console.log('Day checkbox initialized:', this);
    var $this = $(this);
    $this.on('click', function () {
        console.log('Checkbox clicked:', this);
        var dayId = $this.data('id');
        var isChecked = $this.is(':checked');

        // Send AJAX request to update the day status
        $.ajax({
            url: 'days/' + dayId + '/update_complete',
            method: 'POST',
            data: {
                complete: isChecked
            },
            success: function (response) {
                if (response.success) {
                    if (isChecked) {
                        $this.closest('.day').addClass('completed');
                    } else {
                        $this.closest('.day').removeClass('completed');
                    }
                    console.log('Day status updated successfully.');
                } else {
                    console.error('Failed to update day status:', response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX request failed:', error);
            }
        });
    });
});
