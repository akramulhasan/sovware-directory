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

        // Method to get all posts
        public function sov_directory_get_all_post(){
            $arg = array(
                'post_type' => 'sov_dirlist',
                'posts_per_page' => -1,

            );
            $directory_posts = new WP_Query($arg);
            
            $outPutObjArr = [];
            while($directory_posts->have_posts()){
                $directory_posts->the_post();
                array_push($outPutObjArr, array(
                    'title' => get_the_title(),
                    'content' => get_the_content(),
                    'author' => get_the_author(),
                    'date' => get_the_date(),
                    'status' => get_post_status(),
                    'image' => wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) )
                ));
            }



            return $outPutObjArr;
        }

    }
}