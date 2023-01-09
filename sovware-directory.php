<?php 
/**
 * Plugin Name: Sovware Directory
 * Plugin Url: https://www.akramuldev.com/plugins/sovware-directory
 * Version: 1.0.0
 * Description: This is a directory plugin
 * Requires at least: 5.4
 * Author: Akramul Hasan
 * Author URI: https://www.akramuldev.com
 * Text Domain: sov-directory
 * Domain Path: /languages
 */

 //Restrict the execution of the file if try to access directly
 if(!defined ('ABSPATH')){
    exit;
}

//Define the Main class
if( !class_exists('SOV_Directory') ){
    class SOV_Directory {
        function __construct(){

            $this->define_const();

            // directory POST TYPE class file included and initialized
            require_once(SOV_DIREC_PATH.'post-types/class.sov-directory-cpt.php');
            $sov_directory_post_type = new SOV_Directory_Post_Type();


            // directory POST TYPE New Rest Field class file included and initialized
            require_once(SOV_DIREC_PATH.'post-types/class.sov-directory-rest-field.php');
            $sov_directory_rest_field = new SOV_Directory_Rest_Field();


            require_once(SOV_DIREC_PATH.'shortcodes/class.sov-listing-shortcode.php');
            $sov_listing_shortcode = new SOV_Listing_shortcode();

            // require_once(WPFY_SLIDER_PATH.'post-types/class.wpfy-slider-cpt.php');
            // $wpfy_slider_post_type = new WPFY_Slider_Post_Type();

            // require_once(WPFY_SLIDER_PATH.'class.wpfy-slider-settings.php');
            // $wpfy_slider_settings = new WPFY_Slider_Settings();
            
        }

        // will fire this method when plugin activated
        public static function activate(){

            // update 'rewrite_rules' fileds on options table to save the permalinks automatically when plugin activated to avoid 404 issue for Custom Post Type archve page
            update_option( 'rewrite_rules', '' );
        }

        // will fire this method when plugin deactivated
        public static function deactivate(){
            flush_rewrite_rules();
        }

        // will fire this method when plugin uninstalled
        // public static function uninstall(){
            
        // }

        public function define_const(){
            define('SOV_DIREC_PATH', plugin_dir_path( __FILE__ ));
            define('SOV_DIREC_URL', plugin_dir_url( __FILE__ ));
            define('SOV_DIREC_VERSION', '1.0');
        }
    }
}

// activate, deactivate, uninstall method hooked with corresponsed hook
if(class_exists('SOV_Directory')){
    register_activation_hook( __FILE__, array('SOV_Directory', 'activate'));
    register_deactivation_hook( __FILE__, array('SOV_Directory', 'deactivate'));
    register_uninstall_hook( __FILE__, array('SOV_Directory', 'uninstall'));
    $sov_directory= new SOV_Directory();

    
}

?>