<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
require_once plugin_dir_path( __FILE__ ) . 'utils/OptionUtil.php';
class ATLAS_Config
{
    const  ATLAS_ARTICLE_REWRITE_KEY = 'atlas_rewrite' ;
    const  ATLAS_CATEGORY_REWRITE_KEY = 'atlas_rewrite_category' ;
    const  INTERCOM_LAYOUT = 'Intercom' ;
    const  STARTUP_LAYOUT = 'Startup' ;
    const  LAYOUTS = array( self::INTERCOM_LAYOUT, self::STARTUP_LAYOUT ) ;
    const  CAT_IMG_SIZE_KEY = 'atlas-topic-image' ;
    // return app specific slugs
    public static function getSlugs()
    {
        return array(
            'menuSlug' => 'atlas-dashboard',
        );
    }
    
    public static function appInfo()
    {
        return array(
            'appName' => 'Atlas - ',
        );
    }
    
    //get re-write slug
    public static function getReWriteSlug()
    {
        return ATLAS_OptionUtil::getInstance()->getOption( self::ATLAS_ARTICLE_REWRITE_KEY, 'knb-article' );
    }
    
    public static function getCustomPostMetaKey()
    {
        return 'atlas_meta_data';
    }
    
    public static function getPostType()
    {
        return 'atlas_knb';
    }
    
    public static function CPTSupports()
    {
        return array(
            'title',
            'thumbnail',
            'editor',
            'comments',
            'thumbnail'
        );
    }
    
    public static function getTopicsSlug()
    {
        return 'atlas_knb_topics_key';
    }
    
    public static function getCategoryRewrite()
    {
        return ATLAS_OptionUtil::getInstance()->getOption( self::ATLAS_CATEGORY_REWRITE_KEY, 'topics' );
    }
    
    public static function customMetaFieldPrefix()
    {
        return 'atlas_';
    }
    
    static function getSettingsPageSlug()
    {
        return 'atlas_settings_page';
    }
    
    static function getOptionsGroupSlug()
    {
        return 'atlas_options_group_sett';
    }
    
    static function getAvailableLayouts()
    {
        return self::LAYOUTS;
    }
    
    static function getAvailableLayoutsWithLabels()
    {
        return [
            self::INTERCOM_LAYOUT => [
            'key'   => self::INTERCOM_LAYOUT,
            'label' => 'Intercom style',
        ],
        ];
    }
    
    static function getDefaultLayout()
    {
        return self::INTERCOM_LAYOUT;
    }
    
    static function getSearchQVarKey()
    {
        return 'ats';
    }
    
    static function getCategoryQVarKey()
    {
        return 'atc';
    }
    
    static function LOCALES()
    {
        return [
            'searchPlaceholder'     => [
            'label' => esc_html__( 'Search', 'atlas-knb-textdomain' ),
            'desc'  => esc_html__( 'Search placeholder', 'atlas-knb-textdomain' ),
        ],
            'articlesFoundSingular' => [
            'label' => esc_html__( '$articlesNo article found in this collection', 'atlas-knb-textdomain' ),
            'desc'  => esc_html__( 'Articles fonund in collection singular', 'atlas-knb-textdomain' ),
        ],
            'articlesFoundPlural'   => [
            'label' => esc_html__( '$articlesNo articles found in this collection', 'atlas-knb-textdomain' ),
            'desc'  => esc_html__( 'Articles fonund in collection plural', 'atlas-knb-textdomain' ),
        ],
            'writtenByAuthor'       => [
            'label' => esc_html__( 'Written by', 'atlas-knb-textdomain' ),
            'desc'  => esc_html__( 'Written by', 'atlas-knb-textdomain' ),
        ],
            'writtenByAuthorAnd'    => [
            'label' => esc_html__( 'and', 'atlas-knb-textdomain' ),
            'desc'  => esc_html__( 'Written by author and', 'atlas-knb-textdomain' ),
        ],
            'articleUpdatedAt'      => [
            'label' => esc_html__( 'Updated', 'atlas-knb-textdomain' ),
            'desc'  => esc_html__( 'Article updated at', 'atlas-knb-textdomain' ),
        ],
            'readMore'              => [
            'label' => esc_html__( 'Read more', 'atlas-knb-textdomain' ),
            'desc'  => esc_html__( 'Read more label', 'atlas-knb-textdomain' ),
        ],
            'homeNavLabel'          => [
            'label' => esc_html__( 'All collections', 'atlas-knb-textdomain' ),
            'desc'  => esc_html__( 'Home navigation label', 'atlas-knb-textdomain' ),
        ],
            'contactUs'             => [
            'label' => esc_html__( 'Contact us', 'atlas-knb-textdomain' ),
            'desc'  => esc_html__( 'Contact us label', 'atlas-knb-textdomain' ),
        ],
            'ratingQuestion'        => [
            'label' => esc_html__( 'Did this answer your question?', 'atlas-knb-textdomain' ),
            'desc'  => esc_html__( 'Rating question', 'atlas-knb-textdomain' ),
        ],
            'searchResultNavLabel'  => [
            'label' => esc_html__( 'Search results', 'atlas-knb-textdomain' ),
            'desc'  => esc_html__( 'Search results nav label', 'atlas-knb-textdomain' ),
        ],
            'resultsNotFound'       => [
            'label' => esc_html__( 'No search results found', 'atlas-knb-textdomain' ),
            'desc'  => esc_html__( 'No search results found', 'atlas-knb-textdomain' ),
        ],
            'searchResultTitle'     => [
            'label' => esc_html__( 'Search results for:', 'atlas-knb-textdomain' ),
            'desc'  => esc_html__( 'Search results found label', 'atlas-knb-textdomain' ),
        ],
            'knbAtlasTitle'         => [
            'label' => esc_html__( 'Your Dose of Know-How', 'atlas-knb-textdomain' ),
            'desc'  => esc_html__( 'Atlas main title', 'atlas-knb-textdomain' ),
        ],
            'knbAtlasSubTitle'      => [
            'label' => esc_html__( 'All the information you need', 'atlas-knb-textdomain' ),
            'desc'  => esc_html__( 'Subtitle', 'atlas-knb-textdomain' ),
        ],
        ];
    }

}