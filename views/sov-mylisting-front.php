<?php 
if(!is_user_logged_in()) : ?>
    <h2>You have to login first</h2>'
    <?php else : ?>
    <!-- My Listing listing -->
    <h2>User Listing Page</h2>
    <div class="new-listing-wrapper">
        <h3>Add a your service</h3>
        <input placeholder="title" class="new-listing-title" type="text" name="" id="">
        <textarea placeholder="Your service details.." class="new-listing-body" name=""></textarea>
        <label for="img">Featured Image:</label>
        <input type="file" class="post-thumb" id="img" name="img" accept="image/*">
        <input class="new-listing-submit" type="submit" value="Submit">
    </div>
    <ul class="mylisting-wrapper">
        <?php 

            $userListing = new WP_Query(array(
                'post_type' => 'sov_dirlist',
                'posts_per_page' => -1,
                'author' => get_current_user_id()
            ));

            while($userListing->have_posts(  )){
                $userListing->the_post(); ?>
                <li data-id="<?php echo get_the_id(); ?>" class="item">
                    <div class="thumb">
                        <?php the_post_thumbnail('thumbnail'); ?>
                    </div>
                    <div class="contents">
                        <input readonly class="listing-title-field" type="text" value="<?php echo esc_attr(get_the_title()); ?>" />
                        
                        <textarea class="listing-body-field" readonly name="" id="" cols="30" rows="10"><?php echo esc_attr(wp_strip_all_tags( get_the_content() )); ?></textarea>
                        <button class="update-btn">Save</button>
                    

                      
                    </div>
                    <div class="actions">
                        <button class="edit-btn">Edit</button>
                        <button class="delete-btn">Delete</button>
                    </div>
                </li>

            <?php      }


        ?>
    </ul>
<?php endif; ?>
