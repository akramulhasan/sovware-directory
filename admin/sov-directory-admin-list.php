<?php 
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Sov_Directory_List extends WP_List_Table {
    function get_columns(){
        $columns = array(
            'cb'            => '<input type="checkbox" />',
            'post_image' => 'Thumbnail',
            'post_title'         => 'Title',
            'post_content'   => 'Description',
            'post_author'        => 'Author',
            'post_date'          => 'Date',
        );
        return $columns;
    }


    function prepare_items() {
        global $wpdb;
        $per_page = 5;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        $data = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = 'sov_dirlist'", ARRAY_A);
        //var_dump($data);
        //die();
        function usort_reorder($a, $b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'post_title';
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc';
            $result = strcmp($a[$orderby], $b[$orderby]);
            return ($order==='asc') ? $result : -$result;
        }
        usort($data, 'usort_reorder');
        $current_page = $this->get_pagenum();
        $total_items = count($data);
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        $this->items = $data;
        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items/$per_page)
        ) );
    }


    function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'post_image':
                $post_id = $item['ID'];
                $image_id = get_post_thumbnail_id( $post_id );
                if ( !empty($image_id) ) {
                    $image_url = wp_get_attachment_image_src( $image_id, 'thumbnail' )[0];
                    return '<img src="'.$image_url.'"/>';
                } else {
                    return 'No image';
                }
            case 'post_author':
                $author_id = $item[ $column_name ];
                $author_name = get_the_author_meta( 'display_name', $author_id );
                return $author_name;
            case 'post_title':
            case 'post_content':
          
            case 'post_date':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }
    

    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="book[]" value="%s" />', $item['ID']
        );
    }


    function column_title($item) {
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&movie=%s">Edit</a>',$_REQUEST['page'],'edit',$item['ID']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&movie=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
        );
        return sprintf('%1$s %2$s', $item['post_title'], $this->row_actions($actions) );
    }
    
    
    
    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }
    
    
    function process_bulk_action() {
        if ( 'edit' === $this->current_action() ) {
            // code for handling the edit action goes here
            $redirect = admin_url( 'admin.php?page=edit_custom_post_type&id=' . $_REQUEST['movie'] );
            wp_redirect( $redirect );
        } elseif ( 'delete' === $this->current_action() ) {
            // code for handling the delete action goes here
            wp_delete_post( $_REQUEST['movie'], true );
            $redirect = admin_url( 'admin.php?page=list_custom_post_type' );
            wp_redirect( $redirect );
        }
    }
    
}


?>