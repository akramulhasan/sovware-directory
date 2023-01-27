<?php
if(!is_user_logged_in()) : ?>
    <div>
        <form class="sov_reg_form" action="" method="post">
                <?php 
                if(isset($_POST['submit'])) {
                    // Check that all form fields are filled in
                    if(!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {
                        // Create a new user
                        $user_id = wp_create_user( $_POST['username'], $_POST['password'], $_POST['email'] );
                        if(is_wp_error($user_id)){
                            //show error message
                            echo '<span class="error-message">'.$user_id->get_error_message().'</span>';
                        }
                        else{
                            // Assign the custom role to the user
                            $user = new WP_User($user_id);
                            $user->set_role('directory_manager');
                            echo '<span class="success">You have successfully registered ! please <a href="'.wp_login_url($_SERVER['HTTP_REFERER']).'">Login</a> to submit your service</span>';
                            // Redirect the user to a "thank you" page
                        }
                    }
                    else {
                        // Show an error message if form fields are not filled in
                        echo '<span class="error-message">Please fill in all form fields</span>';
                    }
                }
                ?>
                <label for="username">Username</label>
                <input type="text" name="username" id="username">
                <label for="email">Email</label>
                <input type="email" name="email" id="email">
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
                <input type="submit" name="submit" value="Register">
        
      
        </form>
    </div>
    <?php else : ?>
    <!-- My Listing listing -->
    <div class="new-listing-wrapper">
        <h3>Add your service</h3>
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
            if($userListing->have_posts(  )){
                while($userListing->have_posts(  )){
                    $userListing->the_post(); ?>
                    <li data-id="<?php echo get_the_id(); ?>" class="item">
                        <div class="thumb">
                            <?php the_post_thumbnail('thumbnail'); ?>
                        </div>
                        <div class="contents">
                            <span class="post-status"><?php echo get_post_status() === 'private' ? 'This post under review' : '' ?></span>
                            <input readonly class="listing-title-field" type="text" value="<?php echo esc_attr(str_replace('Private:', '', get_the_title())); ?>" />
                            
                            <textarea class="listing-body-field" readonly name="" id="" cols="30" rows="10"><?php echo esc_attr(wp_strip_all_tags( get_the_content() )); ?></textarea>
                            <button class="update-btn">Save</button>
                        
    
                          
                        </div>
                        <div class="actions">
                            <button class="edit-btn">Edit</button>
                            <button class="delete-btn">Delete</button>
                        </div>
                    </li>
    
                <?php      }
            }else{
                echo '<span class="no-post"><span>';
            }



        ?>
    </ul>
<?php endif; ?>
