<?php
/**
 * Plugin Name: Ace the test - Dashboard
 * Plugin URI:  shabbar.sagit@gmail.com
 * Description: Embed the shortcode [acethetest-dashboard].
 * Version:     1.0.0
 * Author:      Shabbar Abbas
 * License:     GPL v2 or later
 * Text Domain: acethetest-dashboard
 * Required Plugins: latepoint
 */

namespace AceThetest_Dashboard;

// Exit if accessed directly.

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main Class.
 *
 * @since 1.0.0
 */
final class AceThetest_Dashboard
{
    /**
     * @var The one true instance
     * @since 1.0.0
     */
    protected static $_instance = null;

    public $version = '2.0.3';
    public $settings;

    /**
     * Addon version.
     *
     */
    public $db_version = '1.0.0';
    public $addon_name = 'acethetest-dashboard';

    /**
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->define_constants();
        $this->includes();
        $this->localisation();
        $this->init();
        do_action('acethetest_dashboard__loaded');
    }

    /**
     * Define Constants.
     * @since  1.0.0
     */
    private function define_constants()
    {
        $this->define('ACETHETEST_DASHBOARD_DIR', plugin_dir_path(__FILE__));
        $this->define('ACETHETEST_DASHBOARD_URL', plugin_dir_url(__FILE__));
        $this->define('ACETHETEST_DASHBOARD_BASENAME', plugin_basename(__FILE__));
        $this->define('ACETHETEST_DASHBOARD_VERSION', $this->version);
        //todo: need to make it to production before going live
        $this->define('ACETHETEST_DASHBOARD_ENV', 'development');
        // $this->define('ACETHETEST_DASHBOARD_ENV', 'production');
    }

    /**
     * Define constant if not already set.
     * @since  1.0.0
     */
    private function define($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }

    /**
     * Include required files.
     * @since  1.0.0
     */
    public function includes()
    {

        include_once 'vendor/autoload.php';
        include_once 'includes/user/class-metaboxes.php';
        include_once 'includes/class-setup.php';
        include_once 'includes/class-controller.php';
        include_once 'includes/class-shortcodes.php';
        include_once 'includes/class-learndash-helper.php';
        include_once 'includes/class-latepoint-helper.php';

        include_once 'includes/functions.php';
    }

    /**
     * Load Localisation files.
     * @since  1.0.0
     */
    public function localisation()
    {
        $locale = apply_filters('plugin_locale', get_locale(), 'acethetest-dashboard');

        load_textdomain('acethetest-dashboard', WP_LANG_DIR . '/acethetest-dashboard/acethetest-dashboard-' . $locale . '.mo');
        load_plugin_textdomain('acethetest-dashboard', false, plugin_basename(dirname(__FILE__)) . '/languages');
    }

    public function init()
    {
        /**
         * @see activate()
         */
        register_activation_hook(__FILE__, [$this, 'activate']);
    }

    /**
     * Main Instance.
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }


    /**
     * Throw error on object clone.
     *
     * @return void
     * @since 1.0.0
     * @access protected
     */
    public function __clone()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'acethetest-dashboard'), '1.0.0');
    }

    /**
     * Disable unserialization of the class.
     * @since 1.0.0
     */
    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'acethetest-dashboard'), '1.0.0');
    }


    /**
     * Activation tasks for quick check
     * @since  1.0.0
     */
    public function activate()
    {

    }

}

/**
 * Run the plugin.
 */
function acethetest_dashboard()
{
    return AceThetest_Dashboard::instance();
}


acethetest_dashboard();

