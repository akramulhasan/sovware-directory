<?php 
if(!class_exists('SOV_Directory_api')){
    class SOV_Directory_api{
        function __construct(){
            add_action('rest_api_init', array($this, 'sov_directory_get_api'));
            add_action('rest_api_init', array($this, 'sov_directory_post_api'));
        }

        // Method to register new route and endpoint for fetching posts
        public function sov_directory_get_api(){
            register_rest_route( 'sov-directory/v1', 'posts', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'sov_directory_get_all_post'),
                'permission_callback' => array($this, 'sov_post_submit_permission')
            ) );
        }

        // Method for new endpoint for submit a post
        public function sov_directory_post_api(){
            register_rest_route( 'sov-directory/v1', 'posts', array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'sov_directory_submit_post'),
                'permission_callback' => array($this, 'sov_post_submit_permission')
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

        // Method to submit a post
        public function sov_directory_submit_post($request){
            $data = $request->get_json_params();
            $title = sanitize_text_field( $data['title'] );
            $content = wp_kses_post( $data['content'] );
            $image_id = $data['featured_media'];
        
            $post_id = wp_insert_post( array(
                'post_title' => $title,
                'post_content' => $content,
                'post_status' => 'publish',
                'post_type' => 'sov_dirlist',
            ) );
        
            if ( is_wp_error( $post_id ) ) {
                return $post_id;
            }
            
            if ( is_wp_error( $image_id ) ) {
                return $image_id;
            }
        
            set_post_thumbnail( $post_id, $image_id );
            return get_post( $post_id );
        }


        public function sov_post_submit_permission(){
            
            // e.g. check if current user has the necessary capability
            if( current_user_can( 'edit_posts' ) ) {
                return true;
            }
            return new WP_Error( 'rest_forbidden', __( 'You do not have permission to do this.' ), array( 'status' => 403 ) );
        }

    }
}