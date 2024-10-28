<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class ATLAS_GenericPostType {

    public function getCTPSettings($rewrite, $description = '') {
        $labels = array(
            'name'               => _x( 'Atlas - articles', 'post type general name', 'atlas-knb-textdomain' ),
            'singular_name'      => _x( 'Atlas - article', 'post type singular name', 'atlas-knb-textdomain' ),
            'menu_name'          => _x( 'Atlas - articles', 'admin menu', 'atlas-knb-textdomain' ),
            'name_admin_bar'     => _x( 'Atlas - article', 'add new on admin bar', 'atlas-knb-textdomain' ),
            'add_new'            => _x( 'Add new', 'atlas' , 'atlas-knb-textdomain' ),
            'add_new_item'       => esc_html__( 'Add New Article ' . 'Atlas - article', 'atlas-knb-textdomain' ),
            'new_item'           => esc_html__( 'New Atlas Article', 'atlas-knb-textdomain' ),
            'edit_item'          => esc_html__( 'Edit Atlas Article', 'atlas-knb-textdomain' ),
            'view_item'          => esc_html__( 'View Atlas Article', 'atlas-knb-textdomain' ),
            'all_items'          => esc_html__( 'All Atlas Articles', 'atlas-knb-textdomain' ),
            'search_items'       => esc_html__( 'Search Atlas Articles', 'atlas-knb-textdomain' ),
            'parent_item_colon'  => esc_html__( 'Parent Atlas Articles', 'atlas-knb-textdomain' ),
            'not_found'          => esc_html__( 'No Atlas Articles found.', 'atlas-knb-textdomain' ),
            'not_found_in_trash' => esc_html__( 'No Atlas Articles found in Trash.', 'atlas-knb-textdomain' )
        );
    
        $args = array(
            'labels'             => $labels,
            'description'        => esc_html__('Description', 'atlas-knb-textdomain' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => $rewrite ),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 80,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail')
        );
        return $args;
    }    
    
    //create custom post
	public function create($slug, $args){
        register_post_type($slug, $args);
    }    
    

    public static function createTaxonomie($postType, $rewrite, $taxSlug) {

        $labels = array(
            'name'              => _x( 'Atlas - Categories', 'taxonomy general name', 'atlas-knb-textdomain' ),
            'singular_name'     => _x( 'Atlas category', 'taxonomy singular name', 'atlas-knb-textdomain' ),
            'search_items'      => esc_html__( 'Search Atlas category', 'atlas-knb-textdomain' ),
            'all_items'         => esc_html__( 'All Atlas - Categories', 'atlas-knb-textdomain' ),
            'parent_item'       => esc_html__( 'Parent Atlas category', 'atlas-knb-textdomain' ),
            'parent_item_colon' => esc_html__( 'Parent Atlas category:', 'atlas-knb-textdomain' ),
            'edit_item'         => esc_html__( 'Edit Atlas category', 'atlas-knb-textdomain' ),
            'update_item'       => esc_html__( 'Update Atlas category', 'atlas-knb-textdomain' ),
            'add_new_item'      => esc_html__( 'Add new Atlas category', 'atlas-knb-textdomain' ),
            'new_item_name'     => esc_html__( 'New Atlas category', 'atlas-knb-textdomain' ),
            'menu_name'         => esc_html__( 'Atlas - Categories', 'atlas-knb-textdomain' ),
        );
    
        $args = array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
			'meta_box_cb'       => false,
            'show_admin_column' => true,
			'query_var'         => true,
			'show_admin_column' => true,
			'show_tagcloud' => false,
            'rewrite'           => array( 'slug' => $rewrite ),
        );
    
        register_taxonomy($taxSlug, array( $postType ), $args );
    }    
}

?>