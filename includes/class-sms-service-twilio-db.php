<?php
/**
 * A class BD.
 *
 * @package    Sms_Service_Twilio
 * @subpackage Sms_Service_Twilio/includes
 */
class Sms_Service_Twilio_DB {

    /**
     * @var      string    $time    The string used to determine the time (in minutes).
     */
    protected $time;

    /**
     * Define the core functionality of the plugin.
     *
     *  @param string $time
     */
    public function __construct( $time ) {

        if ( defined( 'SMS_SERVICE_TWILIO_TIME' ) ) {
            $this->time = SMS_SERVICE_TWILIO_TIME;
        } else {
            $this->time = $time;
        }
    }

    /**
     * Selects all orders that do not have echosign_iframe_status, bank_iframe_status and _sms_twilio_cron metadata
     * for a certain time.
     *
     * @return array.
     */
    public function get_shop_order_not() {

        $args = array(
            'post_type'         => 'shop_order',
            'post_status'       => array('wc-processing'),
            'posts_per_page'    => -1,
            'fields'            => 'ids',
            'meta_query'        => array(
                'relation' => 'AND',
                array(
                    'key'     => '_sms_twilio_cron',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key'     => 'echosign_iframe_status',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key'     => 'bank_iframe_status',
                    'compare' => 'NOT EXISTS'
                )
            ),
            'date_query' => array(
                array(
                    'after'  => $this->time.' minute ago',
                )
            ),
            'no_found_rows'             => true,
            'update_post_term_cache'    => false,
            'update_post_meta_cache'    => false,
            'cache_results'             => false
        );

        $query = new WP_Query($args);

        $result = $query->posts;

        wp_reset_postdata();

        return $result;
    }

    /**
     * Selects all orders that do not have bank_iframe_status and _sms_twilio_cron metadata,
     * and there is a echosign_iframe_status mark for a certain time.
     *
     * @return array.
     */
    public function get_shop_order_eis() {

        $args = array(
            'post_type'         => 'shop_order',
            'post_status'       => array('wc-processing'),
            'posts_per_page'    => -1,
            'fields'            => 'ids',
            'meta_query'        => array(
                'relation' => 'AND',
                array(
                    'key'     => '_sms_twilio_cron',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key'     => 'echosign_iframe_status',
                    'compare' => 'EXISTS'
                ),
                array(
                    'key'     => 'bank_iframe_status',
                    'compare' => 'NOT EXISTS'
                )
            ),
            'date_query' => array(
                array(
                    'after'  => $this->time.' minute ago',
                )
            ),
            'no_found_rows' => true,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
            'cache_results'          => false
        );

        $query = new WP_Query($args);

        $result = $query->posts;

        wp_reset_postdata();

        return $result;
    }

    /**
     * Selects all orders that do not have echosign_iframe_status and _sms_twilio_cron metadata,
     * and there is a bank_iframe_status mark for a certain time.
     *
     * @return array.
     */
    public function get_shop_order_bis() {

        $args = array(
            'post_type'         => 'shop_order',
            'post_status'       => array('wc-processing'),
            'posts_per_page'    => -1,
            'fields'            => 'ids',
            'meta_query'        => array(
                'relation' => 'AND',
                array(
                    'key'     => '_sms_twilio_cron',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key'     => 'echosign_iframe_status',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key'     => 'bank_iframe_status',
                    'compare' => 'EXISTS'
                )
            ),
            'date_query' => array(
                array(
                    'after'  => $this->time.' minute ago',
                )
            ),
            'no_found_rows'             => true,
            'update_post_term_cache'    => false,
            'update_post_meta_cache'    => false,
            'cache_results'             => false
        );

        $query = new WP_Query($args);

        $result = $query->posts;

        wp_reset_postdata();

        return $result;
    }

}