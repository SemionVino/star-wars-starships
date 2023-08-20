jQuery(document).ready(function($) {
    console.log('nigga');
    $('form').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        $.post(
            sws_ajax_object.ajax_url,
            {
                action: 'sws_save_settings',
                nonce: sws_ajax_object.nonce,
                data: formData
            },
            function(response) {
                alert(response.data.message);
            }
        );
    });
});
