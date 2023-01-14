<?php 
if(!class_exists('SOV_Directory_api')){
    class SOV_Directory_api{
        function __construct(){
            add_action('rest_api_init', array($this, 'sov_directory_api'));
        }

        // Method to register new route and endpoint
        public function sov_directory_api(){
            register_rest_route( 'sov-directory/v1', 'posts', array(
                'methods' => WP_REST_SERVER::READABLE,
                'callback' => array($this, 'sov_directory_get_all_post')
            ) );
        }

        public function sov_directory_get_all_post(){
            return 'Congrats for your first custom endpoint result';
        }

    }
}