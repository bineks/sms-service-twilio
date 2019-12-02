<?php
/**
 * Define the internationalization functionality.
 *
 * @package    Sms_Service_Twilio
 * @subpackage Sms_Service_Twilio/includes
 */
class Sms_Service_Twilio_i18n {


    /**
     * Load the plugin text domain for translation.
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
            'sms-service-twilio',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );

    }



}