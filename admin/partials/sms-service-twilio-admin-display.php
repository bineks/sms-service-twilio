<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @package    Sms_Service_Twilio
 * @subpackage Sms_Service_Twilio/admin/partials
 */
?>
<form method="POST" action='options.php'>
    <?php
    settings_fields($this->plugin_name);
    do_settings_sections('twilio-settings-page');

    submit_button();
    ?>
</form>
