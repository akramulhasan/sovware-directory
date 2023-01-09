<?php 
if(!class_exists('SOV_Listing_shortcode')){
    class SOV_Listing_shortcode{
        public function __construct(){
            add_shortcode('sov-listing', array($this, 'sov_listing_shortcode'));
        }

        public function sov_listing_shortcode(){

            ob_start();
            require_once(SOV_DIREC_PATH.'views/sov-listing-front.php');
            return ob_get_clean();

        }
    }
}



?>