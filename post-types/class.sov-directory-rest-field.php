<?php 
if(!class_exists('SOV_Directory_Rest_Field')){
    class SOV_Directory_Rest_Field{
        function __construct(){
            add_action('rest_api_init', array($this, 'sov_new_rest_fields'));
        }

        //Method to add output of new custom rest filed
        public function sov_new_rest_fields(){
            register_rest_field( 'sov_dirlist', 'authorName', array(
                'get_callback' => function(){
                    return get_the_author();
                }
            ) );
        }

    }
}