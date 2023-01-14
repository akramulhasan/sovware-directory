<?php 
if(!class_exists('SOV_Listing_shortcode')){
    class SOV_Listing_shortcode{
        public function __construct(){
            add_shortcode('sov-listing', array($this, 'sov_listing_shortcode'));
            add_shortcode('sov-my-listing', array($this, 'sov_my_listing_shortcode'));
        }

        // output all the listings
        public function sov_listing_shortcode(){

            ob_start();
            require_once(SOV_DIREC_PATH.'views/sov-listing-front.php');
            return ob_get_clean();

        }

        // output current user listings
        public function sov_my_listing_shortcode(){

            ob_start();
            require_once(SOV_DIREC_PATH.'views/sov-mylisting-front.php');
            return ob_get_clean();
        }
    }
}



?>