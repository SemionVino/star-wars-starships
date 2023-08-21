jQuery(document).ready(function($) {
     // Add custom class to the menu item based on its link (href attribute)
     $('#adminmenu a[href="admin.php?page=sws_starships"]').closest('li').addClass('menu-icon-starships');

    $('form').on('submit', function(e) {
        e.preventDefault();
        $('#result').text('')
        const formData = $(this).serialize();
        $.post(
            sws_ajax_object.ajax_url,
            {
                action: 'sws_save_settings',
                nonce: sws_ajax_object.nonce,
                data: formData
            },
            function(response) {
                $('#result').text(response.data.message)
            }
        );
    });
});
