<?php
// Make a GET request to the WordPress REST API to fetch the Directory Post Type data
$response = wp_remote_get( get_rest_url().'sov-directory/v1/posts' );

// Check for errors
if ( is_wp_error( $response ) ) {
  return 'Something went wrong';
}

// Decode the body of the response
$post_data = json_decode( wp_remote_retrieve_body( $response ), true );

//var_dump($post_data);

?>
<!-- All listing -->
<div class="listing-wrapper">
    <?php foreach($post_data as $data) : ?>
    <div class="service">
        <img src="<?php echo esc_url($data['image']); ?>" alt="">
        <h2><?php esc_html_e($data['title'], 'sov-directory'); ?></h2>
        <?php //_e(sanitize_text_field($data['content']['rendered']),'sov-directory'); ?>
        
    </div>
    <?php endforeach; ?>
</div>