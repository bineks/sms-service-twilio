<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @package    Sms_Service_Twilio
 * @subpackage Sms_Service_Twilio/includes
 */
class Sms_Service_Twilio {

    /**
     * @var      Sms_Service_Twilio_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     */
    public function __construct() {
        if ( defined( 'SMS_SERVICE_TWILIO_VERSION' ) ) {
            $this->version = SMS_SERVICE_TWILIO_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'sms-service-twilio';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Sms_Service_Twilio_Loader. Orchestrates the hooks of the plugin.
     * - Sms_Service_Twilio_i18n. Defines internationalization functionality.
     * - Sms_Service_Twilio_DB. The class for Database.
     * - Sms_Service_Twilio_Cron. The Cron functionality.
     * - Sms_Service_Twilio_Admin. Defines all hooks for the admin area.
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sms-service-twilio-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sms-service-twilio-i18n.php';

        /**
         * The class for Database of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sms-service-twilio-db.php';

        /**
         * The class responsible for defining cron functionality of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sms-service-twilio-cron.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sms-service-twilio-admin.php';

        $this->loader = new Sms_Service_Twilio_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Sms_Service_Twilio_i18n class in order to set the domain and to register the hook
     */
    private function set_locale() {

        $plugin_i18n = new Sms_Service_Twilio_i18n();

        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     */
    private function define_admin_hooks() {

        $plugin_admin = new Sms_Service_Twilio_Admin( $this->get_plugin_name(), $this->get_version() );
        $plugin_cron = new Sms_Service_Twilio_Cron();

        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_sms_service_twilio_admin_setting');
        $this->loader->add_action( 'admin_init', $plugin_admin, 'sendex_admin_settings_save' );

        $this->loader->add_filter( 'cron_schedules', $plugin_cron, 'cron_add_time', 1 );
        $this->loader->add_action('init', $plugin_cron,'job_cron_order_twilio_activation');
        $this->loader->add_action( 'job_cron_order_twilio', $plugin_cron, 'sms_twilio_order_report', 10, 0 );

    }

    /**
     * Run the loader to execute all of the hooks.
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Sms_Service_Twilio_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}