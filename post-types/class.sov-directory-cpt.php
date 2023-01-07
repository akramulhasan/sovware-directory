<?php 
if(!class_exists(SOV_Directory_Post_Type)){
    class SOV_Directory_Post_Type{
        function __construct(){
            add_action('init', array($this, 'create_post_type'));
        }

        public function create_post_type(){
            register_post_type('sov-directory', array(

            ));
        }
    }
}