<?php
/**
 * The Cron functionality of the plugin.
 *
 * @package    Sms_Service_Twilio
 * @subpackage Sms_Service_Twilio/includes class-sms-service-twilio-db.php
 */
require_once( SMS_SERVICE_TWILIO_DIR.'vendor/autoload.php' );

use Twilio\Rest\Client;

class Sms_Service_Twilio_Cron {

    /**
     * @var      string    $time    The string used to determine the time (in minutes).
     */
    protected $time;

    /**
     * Define the core functionality of the plugin.
     *
     * @param string $time
     */
    public function __construct( $time = '30' ) {

        if ( defined( 'SMS_SERVICE_TWILIO_TIME' ) ) {
            $this->time = SMS_SERVICE_TWILIO_TIME;
        } else {
            $this->time = $time;
        }
    }

    /**
     * Register interval
     *
     * @param $schedules
     * @return array $schedules
     */
    public function cron_add_time( $schedules ) {

        $minute = (int)$this->time;

        $schedules['twilotime'] = array(
            'interval' => 60 * $minute,
            'display' => 'Every '.$this->time.' minutes'
        );

        return $schedules;
    }

    /**
     * Create a reusable crown task
     */
    public function job_cron_order_twilio_activation(){

        if( !wp_next_scheduled( 'job_cron_order_twilio' ) ) {
            wp_schedule_event( time(), 'twilotime', 'job_cron_order_twilio');
        }
    }

    /**
     * Compose data for sms
     */
    public function sms_twilio_order_report() {

        $twilio_db = new Sms_Service_Twilio_DB( $this->time );
        $sms_twilio_details = get_option('sms-service-twilio');

        $api_sid =  $sms_twilio_details['api_sid'];
        $api_auth_token =  $sms_twilio_details['api_auth_token'];
        $sender_id =  $sms_twilio_details['api_twilio_number'];

        $order_not = $twilio_db->get_shop_order_not();

        if(!empty($order_not)){

            foreach($order_not as $key => $val){

                $to         = $this->get_billing_phone_sms($val);
                $not_filled = $sms_twilio_details['not_filled'];

                $full_name  = $this->get_full_name_sms($val);
                $order_url  = $this->get_order_url_sms($val);

                $message = $this->get_message_sms($not_filled, $full_name, $order_url);

                $this->sending_messages_sms($to, $sender_id, $message, $api_sid, $api_auth_token, $val );

            }
        }

        $order_not_bis = $twilio_db->get_shop_order_eis();

        if(!empty($order_not_bis)){

            foreach($order_not_bis as $key_bis => $val_bis){

                $to         = $this->get_billing_phone_sms($val_bis);
                $not_filled = $sms_twilio_details['not_filled_bis'];

                $full_name  = $this->get_full_name_sms($val_bis);
                $order_url  = $this->get_order_url_sms($val_bis);

                $message = $this->get_message_sms($not_filled, $full_name, $order_url);

                $this->sending_messages_sms($to, $sender_id, $message, $api_sid, $api_auth_token, $val_bis );

            }
        }

        $order_not_eis = $twilio_db->get_shop_order_bis();

        if(!empty($order_not_eis)){

            foreach($order_not_eis as $key_eis => $val_eis){

                $to         = $this->get_billing_phone_sms($val_eis);
                $not_filled = $sms_twilio_details['not_filled_eis'];

                $full_name  = $this->get_full_name_sms($val_eis);
                $order_url  = $this->get_order_url_sms($val_eis);

                $message = $this->get_message_sms($not_filled, $full_name, $order_url);

                $this->sending_messages_sms($to, $sender_id, $message, $api_sid, $api_auth_token, $val_eis );

            }
        }
    }

    /**
     * The Unique URL Link.
     *
     * @param int $ids
     * @return string  $unique_url  Unique URL Link.
     */
    public function get_order_url_sms( $ids ) {

        $unique_url = '';

        $order_key = get_post_meta($ids,  '_order_key', true);

        if(isset($order_key) && $order_key !== ''){

            $unique_url = home_url('/checkout/order-received').'/'.$ids.'/?key='.$order_key;
        }

        return $unique_url;
    }

    /**
     * The value billing phone.
     *
     * @param int $ids
     * @return string  $billing_phone  The value billing phone.
     */
    public function get_billing_phone_sms( $ids ) {

        $billing_phone = '';
        $phone = get_post_meta($ids, '_billing_phone', true);

        $phone = str_replace(" ", "", $phone);
        $phone_len = strlen($phone);

        if(preg_match("/^\+.[61]\d{9}$/", $phone) && $phone_len == 12){
            $billing_phone = $phone;
        }else if(preg_match("/^[0]\d{9}$/", $phone) && $phone_len == 10){

            $phone = substr($phone, 1 );
            $billing_phone = '+61'.$phone;
        }

        return $billing_phone;
    }

    /**
     * The value full name.
     *
     * @param int $ids
     * @return string  $full_name  The value full name.
     */
    public function get_full_name_sms( $ids ) {

        $full_name = '';
        $first_name = get_post_meta($ids, '_billing_first_name', true);
        $last_name = get_post_meta($ids, '_billing_last_name', true);

        if(isset($first_name) && $first_name !== ''){
            $full_name .= $first_name. ' ';
        }

        if(isset($last_name) && $last_name !== ''){
            $full_name .= $last_name;
        }

        return $full_name;
    }

    /**
     * SMS text message.
     *
     * @param string $message
     * @param string $full_name
     * @param string $order_url
     * @return string  $message  SMS text message.
     */
    public function get_message_sms( $message, $full_name, $order_url ) {

        $message = preg_replace('/{{.*full_name.*}}/', $full_name, $message);
        $message = preg_replace('/{{.*unique_url .*}}/', $order_url, $message);

        return $message;
    }

    /**
     * Twilio sending messages sms.
     *
     * @param string $to
     * @param string $sender_id
     * @param string $message
     * @param string $api_sid
     * @param string $api_auth_token
     * @param int $ids
     */
    public function sending_messages_sms( $to, $sender_id, $message, $api_sid, $api_auth_token, $ids ) {

        if((isset($to) && $to !== '') && (isset($sender_id) && $sender_id !== '')) {

            if((isset($api_sid) AND $api_sid !== '') AND (isset($api_auth_token) AND $api_auth_token !== '')) {

                $TWILIO_SID     = $api_sid;
                $TWILIO_TOKEN   = $api_auth_token;
            }
            try{

                $client = new Client($TWILIO_SID, $TWILIO_TOKEN);

                $response = $client->messages->create(
                    $to ,
                    array(
                        'from' => $sender_id,
                        'body' => $message
                    )
                );

                self::twilio_success( $ids );

            } catch (Exception $e) {

                self::twilio_error( $e->getMessage() );
            }
        }
    }


    /**
     * Error
     *
     * @param string $message
     */
    public static function twilio_error($message = "Aww!, there was an error." ){

//        echo $message;

    }

    /**
     * Success
     *
     * @param int $ids
     */
    public static function twilio_success( $ids ) {

//        echo "Successful!";

        update_post_meta($ids, '_sms_twilio_cron', '1');
    }
}