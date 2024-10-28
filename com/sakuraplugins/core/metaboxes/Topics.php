<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
require_once plugin_dir_path( __FILE__ ) . '../config.php';
class ATLAS_TopicsMbx
{
    public static function addTopicsMeta()
    {
        
        if ( is_admin() ) {
            $prefix = ATLAS_Config::customMetaFieldPrefix();
            /* 
             * configure your meta box
             */
            $config = array(
                'id'             => 'topics_meta_box',
                'title'          => esc_html__( 'Category Meta Box', 'atlas-knb-textdomain' ),
                'pages'          => array( ATLAS_Config::getTopicsSlug() ),
                'context'        => 'normal',
                'fields'         => array(),
                'local_images'   => false,
                'use_with_theme' => false,
            );
            $my_meta = new Tax_Meta_Class( $config );
            $my_meta->addText( $prefix . 'awesome_css_id', array(
                'name' => esc_html__( 'Category icon ', 'atlas-knb-textdomain' ),
                'desc' => esc_html__( 'Font Awesome CSS class, Ex: "fas fa-sticky-note". <b>NOTE! You have to enable Font Awesome from the settings page</b>.', 'atlas-knb-textdomain' ),
            ) );
            $my_meta->addText( $prefix . 'color_field_id', array(
                'name' => esc_html__( 'Color ', 'atlas-knb-textdomain' ),
                'desc' => esc_html__( 'Hex color code, used within the Startup layout. Ex: #CCCCCC', 'atlas-knb-textdomain' ),
            ) );
            $my_meta->Finish();
        }
    
    }
    
    public static function renderTopicSelection()
    {
        $parentTopics = get_terms( ATLAS_Config::getTopicsSlug(), [
            'hide_empty' => FALSE,
            'parent'     => 0,
        ] );
        global  $post ;
        $postTerms = wp_get_post_terms( $post->ID, ATLAS_Config::getTopicsSlug() );
        ?>
        <?php 
        
        if ( sizeof( $parentTopics ) === 0 ) {
            ?>
        <p><?php 
            esc_html_e( 'Currently, you haven\'t created any Atlas category. Start by creating one first!', 'atlas-knb-textdomain' );
            ?></p>
        <?php 
        }
        
        ?>
        <?php 
        
        if ( sizeof( $parentTopics ) > 0 ) {
            ?>
            <p><?php 
            esc_html_e( 'You can assign an article to multiple categories using CTRL/CMD and click. Note! Note! Atlas doesn\'t support nested categories.', 'atlas-knb-textdomain' );
            ?></p>
            <select class="atlas-multiple-select" multiple id="atlas-topics" name='topics_ids[]' style="width: 100%; margin-top: 20px;">
                <option value="none"> <?php 
            esc_html_e( '-- Select category', 'atlas-knb-textdomain' );
            ?></option>
                <?php 
            foreach ( $parentTopics as $topic ) {
                ?>                
                    <?php 
                $hasTerm = self::postHasTerm( $topic->term_id, $postTerms );
                ?>
                    <option <?php 
                echo  ( $hasTerm ? 'selected ' : '' ) ;
                ?>value="<?php 
                echo  esc_attr( $topic->term_id ) ;
                ?>"><?php 
                echo  esc_html( $topic->name ) ;
                ?></option>
                <?php 
            }
            ?>
            </select>
        <?php 
        }
        
        ?>
        <?php 
    }
    
    private static function postHasTerm( $term_id, $postTerms )
    {
        $out = false;
        for ( $i = 0 ;  $i < sizeof( $postTerms ) ;  $i++ ) {
            
            if ( $postTerms[$i]->term_id === $term_id ) {
                $out = true;
                break;
            }
        
        }
        return $out;
    }
    
    static function renderPostShortDescription()
    {
        global  $post ;
        ?>
        <p><?php 
        esc_html_e( 'Short description of the article\'s content, will be used within the frontend.', 'atlas-knb-textdomain' );
        ?></p>
        <textarea name="shortDescription" style="width: 100%; min-height: 130px;"><?php 
        echo  esc_html( get_post_meta( $post->ID, 'shortDescription', true ) ) ;
        ?></textarea>
        <?php 
    }

}