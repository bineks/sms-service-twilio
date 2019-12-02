<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 * @package    Sms_Service_Twilio
 * @subpackage Sms_Service_Twilio/admin class-sms-service-twilio-cron.php
 */
class Sms_Service_Twilio_Admin {

    /**
     * The ID of this plugin.
     *
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     */
    public function enqueue_styles() {

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sms-service-twilio-admin.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the admin area.
     */
    public function enqueue_scripts() {

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sms-service-twilio-admin.js', array( 'jquery' ), $this->version, false );

    }

    /**
     *  Register the administration menu the Dashboard
     */
    public function add_sms_service_twilio_admin_setting() {

        add_options_page( __('Sms Twilio', 'sms-service-twilio'), __('Sms Twilio', 'sms-service-twilio'), 'manage_options', $this->plugin_name, array($this, 'display_sms_service_twilio_settings'));

    }

    /**
     * Render the settings page.
     */
    public function display_sms_service_twilio_settings() {

        include_once( 'partials/sms-service-twilio-admin-display.php' );

    }

    /**
     * Registers and Defines the necessary fields we need.
     *
     */
    public function sendex_admin_settings_save(){

        register_setting( $this->plugin_name, $this->plugin_name, array($this, 'plugin_options_validate') );

        add_settings_section(
            'twilio_main',
            __('Twilio Main Settings', 'sms-service-twilio'),
            array($this, 'twilio_section_text'),
            'twilio-settings-page'
        );

        add_settings_field(
            'api_sid',
            __('ACCOUNT SID', 'sms-service-twilio'),
            array($this, 'twilio_setting_sid'),
            'twilio-settings-page',
            'twilio_main',
            array( 'label_for' => 'api_sid' )
        );

        add_settings_field(
            'api_auth_token',
            __('API AUTH TOKEN', 'sms-service-twilio'),
            array($this, 'twilio_setting_token'),
            'twilio-settings-page',
            'twilio_main',
            array( 'label_for' => 'api_auth_token' )
        );

        add_settings_field(
            'api_twilio_number',
            __('A Twilio number SMS capabilities', 'sms-service-twilio'),
            array($this, 'twilio_setting_number'),
            'twilio-settings-page',
            'twilio_main',
            array( 'label_for' => 'api_twilio_number' )
        );

        add_settings_field(
            'not_filled',
            __('Bank statement & Privacy Statements SMS body.</br> For the name use: </br>{{ full_name }} </br>and for the Url: </br>{{ unique_url }}', 'sms-service-twilio'),
            array($this, 'twilio_sms_text'),
            'twilio-settings-page',
            'twilio_main',
            array( 'label_for' => 'not_filled' )
        );

        add_settings_field(
            'not_filled_bis',
            __('Bank statement SMS body. </br> For the name use: </br>{{ full_name }} </br>and for the Url: </br>{{ unique_url }}', 'sms-service-twilio'),
            array($this, 'twilio_sms_text_bis'),
            'twilio-settings-page',
            'twilio_main',
            array( 'label_for' => 'not_filled_bis' )
        );

        add_settings_field(
            'not_filled_eis',
            __('Privacy Statements SMS body. </br> For the name use: </br>{{ full_name }} </br>and for the Url: </br>{{ unique_url }}', 'sms-service-twilio'),
            array($this, 'twilio_sms_text_eis'),
            'twilio-settings-page',
            'twilio_main',
            array( 'label_for' => 'not_filled_eis' )
        );

    }

    /**
     * Displays the settings sub header
     *
     */
    public function twilio_section_text() {
        echo '<h3>'.__('Edit api details', 'sms-service-twilio').'</h3>';
    }

    /**
     * Renders the sid input field
     *
     */
    public function twilio_setting_sid() {

        $options = get_option($this->plugin_name);
        echo "<input id='api_sid' name='$this->plugin_name[api_sid]' size='40' type='text' value='{$options['api_sid']}' />";
    }

    /**
     * Renders the auth_token input field
     *
     */
    public function twilio_setting_token() {

        $options = get_option($this->plugin_name);
        echo "<input id='api_auth_token' name='$this->plugin_name[api_auth_token]' size='40' type='text' value='{$options['api_auth_token']}' />";
    }

    /**
     * Renders the api_twilio_number input field
     *
     */
    public function twilio_setting_number() {

        $options = get_option($this->plugin_name);
        echo "<input id='api_twilio_number' name='$this->plugin_name[api_twilio_number]' size='40' type='text' value='{$options['api_twilio_number']}' />";
    }

    /**
     * Renders the not_filled textarea field
     *
     */
    public function twilio_sms_text() {

        $options = get_option($this->plugin_name);
        echo "<textarea id='not_filled' name='$this->plugin_name[not_filled]' rows='10' cols='43' type='text' >".$options['not_filled']."</textarea>";
    }

    /**
     * Renders the not_filled_eis textarea field
     *
     */
    public function twilio_sms_text_bis() {

        $options = get_option($this->plugin_name);
        echo "<textarea id='not_filled_bis' name='$this->plugin_name[not_filled_bis]' rows='10' cols='43' type='text' >".$options['not_filled_bis']."</textarea>";
    }

    /**
     * Renders the not_filled_eis textarea field
     *
     */
    public function twilio_sms_text_eis() {

        $options = get_option($this->plugin_name);
        echo "<textarea id='not_filled_eis' name='$this->plugin_name[not_filled_eis]' rows='10' cols='43' type='text' >".$options['not_filled_eis']."</textarea>";
    }

    /**
     * Sanitises all input fields.
     *
     */
    public function plugin_options_validate($input) {

        $newinput['api_sid'] = trim($input['api_sid']);
        $newinput['api_auth_token'] = trim($input['api_auth_token']);
        $newinput['api_twilio_number'] = trim($input['api_twilio_number']);
        $newinput['not_filled'] = $input['not_filled'];
        $newinput['not_filled_bis'] = trim($input['not_filled_bis']);
        $newinput['not_filled_eis'] = trim($input['not_filled_eis']);

        return $newinput;
    }

}