<?php
// Get url parameters and extract current page for pagination
$current_url = $_SERVER['REQUEST_URI'];
$parts = explode('/', $current_url);
$page_number = str_replace('page/', '', $parts[count($parts) - 1]);
$currentPage = get_query_var('paged');

// Insert the $currentPage into an array for sending to custom endpoint
$args = array(
  'paged' => $currentPage,
);

// api endpoint url
$api_endpoint = get_rest_url().'sov-directory/v1/posts';

// Make a GET request to the plugin's custom endpoint with arguments
$response = wp_remote_get( add_query_arg( $args, $api_endpoint ) );

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
      <?php //var_dump($data) ?>
    <div class="service">
        <img src="<?php echo esc_url($data['image']); ?>" alt="">
        <h2><?php esc_html_e($data['title'], 'sov-directory'); ?></h2>
        <?php //_e(sanitize_text_field($data['content']['rendered']),'sov-directory'); ?>
        
    </div>
    <?php endforeach; ?>
    <div class="pagination">
      <?php next_posts_link('Next', $data['totalPages']) ?>
    </div>
</div>