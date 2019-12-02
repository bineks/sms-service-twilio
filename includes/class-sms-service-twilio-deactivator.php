<?php
/**
 * Fired during plugin deactivation.
 *
 * @package    Sms_Service_Twilio
 * @subpackage Sms_Service_Twilio/includes
 */
class Sms_Service_Twilio_Deactivator {

    public static function deactivate() {
        self::job_cron_order_twilio_remove();
    }

    /**
     * Remove a reusable crown task
     */
    public static function job_cron_order_twilio_remove(){

        if( wp_next_scheduled( 'job_cron_order_twilio' ) ) {
            wp_clear_scheduled_hook( 'job_cron_order_twilio' );
        }
    }

}