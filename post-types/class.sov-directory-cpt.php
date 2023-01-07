<?php 
if(!class_exists('SOV_Directory_Post_Type')){
    class SOV_Directory_Post_Type{
        function __construct(){
            add_action('init', array($this, 'create_post_type'));
        }

        //Method to register CPT
        public function create_post_type(){
            $labels = array(
                'name'                  => _x( 'Services', 'Post Type General Name', 'sov-directory' ),
                'singular_name'         => _x( 'Service', 'Post Type Singular Name', 'sov-directory' ),
                'menu_name'             => __( 'Sov Directory', 'sov-directory' ),
                'name_admin_bar'        => __( 'Sov Directory', 'sov-directory' ),
                'archives'              => __( 'Service Archives', 'sov-directory' ),
                'attributes'            => __( 'Service Attributes', 'sov-directory' ),
                'parent_item_colon'     => __( 'Parent Service:', 'sov-directory' ),
                'all_items'             => __( 'All Services', 'sov-directory' ),
                'add_new_item'          => __( 'Add New Service', 'sov-directory' ),
                'add_new'               => __( 'Add New Service', 'sov-directory' ),
                'new_item'              => __( 'New Service', 'sov-directory' ),
                'edit_item'             => __( 'Edit service', 'sov-directory' ),
                'update_item'           => __( 'Update Service', 'sov-directory' ),
                'view_item'             => __( 'View Service', 'sov-directory' ),
                'view_items'            => __( 'View Services', 'sov-directory' ),
                'search_items'          => __( 'Search Service', 'sov-directory' ),
                'not_found'             => __( 'Not found', 'sov-directory' ),
                'not_found_in_trash'    => __( 'Not found in Trash', 'sov-directory' ),
                'featured_image'        => __( 'Featured Image', 'sov-directory' ),
                'set_featured_image'    => __( 'Set featured image', 'sov-directory' ),
                'remove_featured_image' => __( 'Remove featured image', 'sov-directory' ),
                'use_featured_image'    => __( 'Use as featured image', 'sov-directory' ),
                'insert_into_item'      => __( 'Insert into service', 'sov-directory' ),
                'uploaded_to_this_item' => __( 'Uploaded to this item', 'sov-directory' ),
                'items_list'            => __( 'Service list', 'sov-directory' ),
                'items_list_navigation' => __( 'Service list navigation', 'sov-directory' ),
                'filter_items_list'     => __( 'Filter services list', 'sov-directory' ),
            );
            $args = array(
                'label'                 => __( 'Service', 'sov-directory' ),
                'description'           => __( 'Sov directory service listing', 'sov-directory' ),
                'labels'                => $labels,
                'supports'              => array( 'title', 'editor', 'thumbnail' ),
                'taxonomies'            => array( 'category', 'post_tag' ),
                'hierarchical'          => false,
                'public'                => true,
                'show_ui'               => true,
                'show_in_menu'          => true,
                'menu_position'         => 5,
                'menu_icon'             => 'dashicons-editor-table',
                'show_in_admin_bar'     => true,
                'show_in_nav_menus'     => true,
                'can_export'            => true,
                'has_archive'           => true,
                'exclude_from_search'   => false,
                'publicly_queryable'    => true,
                'capability_type'       => 'page',
                'show_in_rest'          => true,
            );
            register_post_type( 'sov_dirlist', $args );
        }
    }
}