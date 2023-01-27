<?php 
if(!class_exists('SOV_Directory_api')){
    class SOV_Directory_api{
        function __construct(){
            add_action('rest_api_init', array($this, 'sov_directory_get_api'));
            add_action('rest_api_init', array($this, 'sov_directory_post_api'));
            add_action('rest_api_init', array($this, 'custom_post_type_endpoint'));
        }
        
        function custom_post_type_endpoint() {
            register_rest_route( 'sov-directory/v1', '/posts/(?P<page>\d+)', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_dirlist_posts'),
            'permission_callback' => array($this, 'sov_post_get_permission')
            ) );
        }

        function get_dirlist_posts( $request ) {
        $page = $request['page'];
        $args = array(
        'post_type' => 'sov_dirlist',
        'posts_per_page' => 4,
        'paged' => $page,
        );
        $query = new WP_Query( $args );
        $totalPages = ceil($query->found_posts / 4);
        $posts = array();
        if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $post = array(
                'title' => get_the_title(),
                'content' => get_the_content(),
                'permalink' => get_the_permalink(),
                'image' => wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ),
                'totalPages' => $totalPages
            );
            array_push( $posts, $post );
        }
        }
        return rest_ensure_response( $posts );
        }

        // Method to register new route and endpoint for fetching posts
        public function sov_directory_get_api(){

            register_rest_route( 'sov-directory/v1', 'posts', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'sov_directory_get_all_post'),
                'permission_callback' => array($this, 'sov_post_get_permission')
            ) );
        }

        // Method for GET request permission
        public function sov_post_get_permission(){
            return true;
        }



        // Method for new endpoint for submit a post
        public function sov_directory_post_api(){
            register_rest_route( 'sov-directory/v1', 'posts', array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'sov_directory_submit_post'),
                'permission_callback' => array($this, 'sov_post_submit_permission')
            ) );
        }

        // Method for POST request permission
        public function sov_post_submit_permission(){
            
            // e.g. check if current user has the necessary capability
            if( current_user_can( 'edit_services' ) ) {
                return true;
            }
            return new WP_Error( 'rest_forbidden', __( 'You do not have permission to do this.' ), array( 'status' => 403 ) );
        }

        // Method to get all posts
        public function sov_directory_get_all_post($request){

            //$parameters = $request->get_url_params();
            $parameters = $request->get_query_params();
            //$parameters = $request->get_body_params();
            //$parameters = $request->get_json_params();
            //$parameters = $request->get_default_params();

            // Get the current page number
            $paged = $_REQUEST['paged'];
            
            // Set the $args for custom query
            $arg = array(
                'post_type' => 'sov_dirlist',
                'posts_per_page' => 4,
                'paged' => $paged
            );
            
            //$paged = get_query_var('paged');
            $directory_posts = new WP_Query($arg);
            $totalPages = ceil($directory_posts->found_posts / 4);
            $outPutObjArr = [];
            while($directory_posts->have_posts()){
                $directory_posts->the_post();
                array_push($outPutObjArr, array(
                    'title' => get_the_title(),
                    'content' => get_the_content(),
                    'author' => get_the_author(),
                    'date' => get_the_date(),
                    'status' => get_post_status(),
                    'image' => wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ),
                    'totalPages' => $totalPages
                   
                ));

                
            }



            return $outPutObjArr;
            //return $paged;
        }

        // Method to submit a post
        public function sov_directory_submit_post($request){

            // All incoming data sanitized
            $data = $request->get_json_params();
            $title = sanitize_text_field( $data['title'] );
            $content = wp_kses_post( $data['content'] );
            $image_id = absint( $data['featured_media'] );
        
            $post_id = wp_insert_post( array(
                'post_title' => $title,
                'post_content' => $content,
                'post_status' => 'private',
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

    }
}