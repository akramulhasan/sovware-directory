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

            // Enquery scripts
            add_action('wp_enqueue_scripts', array($this, 'load_assets'));

            // directory POST TYPE class file included and initialized
            require_once(SOV_DIREC_PATH.'post-types/class.sov-directory-cpt.php');
            $sov_directory_post_type = new SOV_Directory_Post_Type();


            // directory POST TYPE New Rest Field class file included and initialized
            require_once(SOV_DIREC_PATH.'post-types/class.sov-directory-rest-field.php');
            $sov_directory_rest_field = new SOV_Directory_Rest_Field();

            // directory POST TYPE custom endpoint api class file included and initialized
            require_once(SOV_DIREC_PATH.'inc/class.sov-directory-api.php');
            $sov_directory_api = new SOV_Directory_api();

            // including shortcode class file and initialized
            require_once(SOV_DIREC_PATH.'shortcodes/class.sov-listing-shortcode.php');
            $sov_listing_shortcode = new SOV_Listing_shortcode();

            // directory lists in admin file included
            require_once(SOV_DIREC_PATH.'admin/sov-directory-admin-list.php');

            // including admin menu class file and initialized
            require_once(SOV_DIREC_PATH.'admin/class.sov-directory-admin-menu.php');
            $sov_admin_menu = new SOV_admin_menu();
            
        }

        // enqueue method
        public function load_assets(){
            wp_enqueue_style( 'listing', SOV_DIREC_URL.'assets/css/listing.css', null, null);
            wp_enqueue_script( 'rest-ajax', SOV_DIREC_URL.'assets/js/rest-ajax.js', array('jquery'), null, true );
            wp_localize_script( 'rest-ajax', 'sovObj', array(
                'restURL' => rest_url(),
                'restNonce' => wp_create_nonce('wp_rest'),
                'pluginUrl' => SOV_DIREC_URL,
                'ajaxurl' => admin_url( 'admin-ajax.php' )

            ) );
        }

        // will fire this method when plugin activated
        public static function activate(){

            // Add a custom role
            add_role( 'directory_manager', 'Directory Manager');

            // Add custom capabilities to new Role
            $directory_manager = get_role('directory_manager');
            $directory_manager->add_cap('edit_service');
            $directory_manager->add_cap('edit_services');
            $directory_manager->add_cap('edit_published_services');
            $directory_manager->add_cap('publish_services');
            $directory_manager->add_cap('delete_services' );
            $directory_manager->add_cap('delete_published_services' );
            $directory_manager->add_cap('delete_service' );
            $directory_manager->add_cap('upload_files');
            $directory_manager->remove_cap('read_service');
            $directory_manager->remove_cap('read_services');

            // Add custom caps to Admin
            $getAdmin = get_role('administrator');
            $getAdmin->add_cap('edit_service');
            $getAdmin->add_cap('edit_services');
            $getAdmin->add_cap('edit_others_service');
            $getAdmin->add_cap('edit_others_services');
            $getAdmin->add_cap('edit_published_services');
            $getAdmin->add_cap('publish_services');
            $getAdmin->add_cap('delete_others_services');
            $getAdmin->add_cap('delete_private_services');
            $getAdmin->add_cap('delete_published_services');


            // update 'rewrite_rules' fileds on options table to save the permalinks automatically when plugin activated to avoid 404 issue for Custom Post Type archve page
            update_option( 'rewrite_rules', '' );
 
        }

        // will fire this method when plugin deactivated
        public static function deactivate(){
            // remove the custom role                               
            remove_role( 'directory_manager' );
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