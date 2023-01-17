<?php 
if(!class_exists('SOV_admin_menu')){
    class SOV_admin_menu{
        public function __construct(){
            add_action('admin_menu', array($this, 'add_sov_directory_submenu'));
        }



        function add_sov_directory_submenu() {
            add_submenu_page(
                'edit.php?post_type=sov_dirlist',
                'Custom Post Type Submenu',
                'Custom Submenu',
                'manage_options',
                'custom-post-type-submenu',
                array($this, 'sov_directory_admin_page_callback')
            );
        }

        function sov_directory_admin_page_callback() {

            // including admin menu class file and initialized
            require_once(SOV_DIREC_PATH.'admin/class.sov-directory-admin-menu.php');
            $sovDirectoryList = new Sov_Directory_List();
            $sovDirectoryList->prepare_items();
            ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h2>Sov Directory Service List</h2>
                <form method="post">
                    <input type="hidden" name="page" value="my_list_test" />
                    <?php $sovDirectoryList->display() ?>
                </form>
            </div>
            <?php
        }
        

    }
}



?>