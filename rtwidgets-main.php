<?php
/**
 * Plugin Name: rtWidgets
 * Plugin URI: https://rtcamp.com/rtwidgets/
 * Description: This installs multiple custom widgets in one activation. All the custom widgets are translation ready.
 * Version: 1.2.2
 * Author: rtCamp
 * Author URI: https://rtcamp.com/
 * Text Domain: rtwidgets
 */

/**
 * Main file, includes plugin classes and registers constants
 * 
 * @package rtWidgets
 * 
 * @since rtWidgets 1.0
 */

/**
 * Absolute path to plugin
 */
if ( !defined( 'RTWIDGETS_ABSPATH' ) ) {
    define( 'RTWIDGETS_ABSPATH', __DIR__ );
}

/**
 * Path to plugins root folder
 */
if ( !defined( 'RTWIDGETS_ROOT' ) ) {
    define( 'RTWIDGETS_ROOT', plugin_dir_path( __FILE__ ) );
}

/**
 * Base URL of plugin
 */
if ( !defined( 'RTWIDGETS_BASEURL' ) ) {
    define( 'RTWIDGETS_BASEURL', plugin_dir_url( __FILE__ ) );
}

/**
 * Base Name of plugin
 */
if ( !defined( 'RTWIDGETS_BASENAME' ) ) {
    define( 'RTWIDGETS_BASENAME', plugin_basename( __FILE__ ) );
}

/**
 * Directory Name of plugin
 */
if ( !defined( 'RTWIDGETS_DIRNAME' ) ) {
    define( 'RTWIDGETS_DIRNAME', dirname( plugin_basename( __FILE__ ) ) );
}

/**
 * Define assets path
 */
define( 'RTWIDGETS_ASSETS', RTWIDGETS_BASEURL . 'assets/' );

/**
 * Define admin assets path
 */
define( 'RTWIDGETS_ADMIN_ASSETS', RTWIDGETS_BASEURL . 'admin/assets/' );

/**
 * rtWidgets
 * 
 * The starting point of rtWidgets.
 * 
 * @package rtWidgets
 * @since rtWidgets 1.0
 */
class rtWidgets {
    
    /**
     * @var string
     */
    public $version = '1.2';
    
    /**
     * Define Text Domain
     * 
     * @since rtWidgets 1.0
     */
    public $rtwidgets_text_domain = 'rtwidgets';
    
    public $rt_widgets = array();

    /**
     * Class Constructor
     * 
     * @since rtWidgets 1.0
     */
    public function __construct() {
        
        // Define version constant
        define( 'RTWIDGETS_VERSION', $this->version );
        
        // Load Plugin Text Domain
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
        
        // Add plugin jQuery and Style
        add_action( 'wp_enqueue_scripts', array( $this, 'rtw_enqueue_assets' ), 999 );
        
        // Include all files from admin directory
        foreach ( glob( RTWIDGETS_ROOT . 'admin/*.php' ) as $lib_filename ) {
            require_once $lib_filename;
        }
        
        // Include all files from lib directory
        foreach ( glob( RTWIDGETS_ROOT . 'lib/*.php' ) as $lib_filename ) {
            require_once $lib_filename;
        }

        $classnames = get_declared_classes();
        foreach ( $classnames as $classname ) {
            if ( strpos( $classname, 'rtw_' ) !== false ) {
                $this->rt_widgets[] = $classname;
            }
        }
        
        // Add Settings Link
        add_filter( 'plugin_action_links_' . RTWIDGETS_BASENAME,  array( $this, 'rtw_settings_link' ) );
        
        // Register Widgets
        add_action( 'widgets_init', array( $this, 'rtw_register' ) );
    }
    
    /**
     * Add settings link on plugin page
     *
     * @since rtWidgets 1.0
     */
    public function rtw_settings_link( $links ) {
        $settings_link = '<a href="' . admin_url( 'options-general.php?page=rtwidgets-options' ) . '" title="' . __( 'rtWidgets Settings', $this->rtwidgets_text_domain ) . '">' . __( 'Settings', $this->rtwidgets_text_domain ) . '</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }
    
    /**
     * Enqueues plugin scripts and styles
     * 
     * @since rtWidgets 1.0
     */
    public function rtw_enqueue_assets() {
        if ( !is_admin() ) {
            wp_enqueue_style( 'rtw-icon-fonts', RTWIDGETS_ASSETS . 'icon-font/css/rtw-fontello.css', array(), '1.0' );
            wp_enqueue_style( 'rtw-plugin-css', RTWIDGETS_ASSETS . 'rtwidgets-style.css', array( 'rtw-icon-fonts' ), '1.0' );
        }
    }
    
    /**
     * Registers all rtWidgets
     * 
     * @since rtWidget 1.0
     */
    public function rtw_register() {
        $rtw_options = get_option( 'rtw_options' );
        if ( is_array( $this->rt_widgets ) && count( $this->rt_widgets ) > 0 ) {
            foreach ( $this->rt_widgets as $classname ) {
                if ( !isset( $rtw_options[$classname] ) || $rtw_options[$classname] ) {
                    register_widget( $classname );
                }
            }
        }
    }
   
    /**
     * Load the translation file for current language.
     * 
     * @since rtWidget 1.0
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain( $this->rtwidgets_text_domain, FALSE, RTWIDGETS_DIRNAME . '/languages/' );
    }
}

/**
 * Instantiate Main Class
 */
global $rtwidgets;
$rtwidgets = new rtWidgets();