<?php
/**
 * Plugin Name:       SMS service for Twillio
 * Description:       The plugin is a custom developed solution for checking the WooCommerce order infromation and detecting whether an order has Privacy Policy agreement checkbox selected and if the client has provided Income and Bank statemens. If the data is lacking - an SMS is sent via Twillio to the client's phone with a suggestion to fill in the necessary data.
 * Version:           1.0.0
 * Author:            Bineks
 * Author URI:        https://bineks.net/
 * Text Domain:       sms-service-twilio
 * Domain Path:       /languages
 *
 * @package           Sms_Service_Twilio
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'SMS_SERVICE_TWILIO_VERSION', '1.0.0' );
define( 'SMS_SERVICE_TWILIO_TIME', '30' );
define( 'SMS_SERVICE_TWILIO_DIR', plugin_dir_path(__FILE__) );

function activate_sms_service_twilio() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-sms-service-twilio-activator.php';
    Sms_Service_Twilio_Activator::activate();
}

function deactivate_sms_service_twilio() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-sms-service-twilio-deactivator.php';
    Sms_Service_Twilio_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sms_service_twilio' );
register_deactivation_hook( __FILE__, 'deactivate_sms_service_twilio' );

require plugin_dir_path( __FILE__ ) . 'includes/class-sms-service-twilio.php';

function run_sms_service_twilio() {

    $plugin = new Sms_Service_Twilio();
    $plugin->run();

}
run_sms_service_twilio();