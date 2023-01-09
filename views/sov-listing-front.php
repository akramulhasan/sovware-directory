<?php
// Make a GET request to the WordPress REST API to fetch the Directory Post Type data
$response = wp_remote_get( 'http://fictional-university.local/wp-json/wp/v2/sov_dirlist' );

// Check for errors
if ( is_wp_error( $response ) ) {
  return 'Something went wrong';
}

// Decode the body of the response
$post_data = json_decode( wp_remote_retrieve_body( $response ), true );

// Extract the Directory Post Type data
// $title = $post_data['title']['rendered'];
// $content = $post_data['content']['rendered'];

//var_dump($post_data);
?>


<div class="listing-wrapper">
    <?php foreach($post_data as $data) : ?>
    <div class="service">
        <img src="" alt="">
        <h2><?php esc_html_e($data['title']['rendered'], 'sov-directory'); ?></h2>
        <?php _e(sanitize_text_field($data['content']['rendered']),'sov-directory'); ?>
        
    </div>
    <?php endforeach; ?>
</div>