<div class="wrap">
    <h1>Star Wars Starships Settings</h1>
    <form>
        <?php
        settings_fields('sws_settings_group');
        do_settings_sections('sws-settings');
        submit_button('Save Settings');
        ?>
    </form>
</div>