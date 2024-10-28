<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
require_once plugin_dir_path( __FILE__ ) . 'config.php';
require_once plugin_dir_path( __FILE__ ) . 'cpt/Cpt.php';
require_once plugin_dir_path( __FILE__ ) . 'utils/OptionUtil.php';
require_once plugin_dir_path( __FILE__ ) . 'utils/SessionUtils.php';
require_once plugin_dir_path( __FILE__ ) . 'templates/admin-settings-page.php';
require_once plugin_dir_path( __FILE__ ) . 'vendor/Tax_Meta_Class.php';
require_once plugin_dir_path( __FILE__ ) . 'metaboxes/Topics.php';
require_once plugin_dir_path( __FILE__ ) . 'services/AssetsService.php';
require_once plugin_dir_path( __FILE__ ) . 'services/RatingService.php';
require_once plugin_dir_path( __FILE__ ) . 'shortcodes/shortcodeManager.php';
require_once plugin_dir_path( __FILE__ ) . 'sidebars/sidebars.php';
require_once plugin_dir_path( __FILE__ ) . 'sidebars/widgets/widgets.php';
class ATLAS_Core
{
    public function run()
    {
        $this->registerActions();
        $this->addImageSizes();
        ATLAS_TopicsMbx::addTopicsMeta();
        $this->addAjaxActions();
    }
    
    public function initializeHandler()
    {
        $this->registerCustomPostType();
        $this->addTaxonomy();
        $this->initShortcodes();
        ATLAS_SessionUtils::getInstance()->sessionStart();
    }
    
    // admin menu handler
    public function adminMenuHandler()
    {
        add_submenu_page(
            'edit.php?post_type=' . ATLAS_Config::getPostType(),
            esc_html__( 'Settings', 'atlas-knb-textdomain' ),
            esc_html__( 'Settings', 'atlas-knb-textdomain' ),
            'manage_options',
            ATLAS_Config::getSettingsPageSlug(),
            [ 'ATLAS_settingsPage', 'render' ]
        );
    }
    
    // set up image sizes for topics
    private function addImageSizes()
    {
        add_image_size(
            ATLAS_Config::CAT_IMG_SIZE_KEY,
            200,
            200,
            true
        );
    }
    
    public function registerCustomPostType()
    {
        $aCTP = new ATLAS_Cpt();
        $args = $aCTP->getCTPSettings( ATLAS_Config::getReWriteSlug() );
        $menuIcon = plugins_url( '', dirname( __FILE__ ) . '../' ) . '/assets/img/icon.png';
        $aCTP->create( ATLAS_Config::getPostType(), array_merge( $args, [
            'menu_icon'    => $menuIcon,
            'show_in_rest' => ATLAS_OptionUtil::getInstance()->getOption( 'show_gutenberg_editor' ) === '1',
            'supports'     => ATLAS_Config::CPTSupports(),
        ] ) );
        //re-write once
        $isReWrite = get_option( 'atlas_is_rewrite' );
        
        if ( $isReWrite !== true ) {
            flush_rewrite_rules();
            update_option( 'atlas_is_rewrite', true );
        }
    
    }
    
    //add taxonomy
    private function addTaxonomy()
    {
        ATLAS_GenericPostType::createTaxonomie( ATLAS_Config::getPostType(), ATLAS_Config::getCategoryRewrite(), ATLAS_Config::getTopicsSlug() );
    }
    
    public function loadAdminScripts( $hook )
    {
        $current_screen = get_current_screen();
        $screenID = $current_screen->id;
        // atlas_knb_page_atlas_settings_page
        
        if ( $screenID === 'edit-' . ATLAS_Config::getTopicsSlug() ) {
            wp_enqueue_script( 'jquery' );
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker' );
            $taxMetaJS = plugins_url( '', dirname( __FILE__ ) . '..' ) . '/assets/js/vendor/tax-meta-clss.js';
            wp_enqueue_script(
                'tax-meta-js',
                $taxMetaJS,
                array( 'jquery', 'wp-color-picker' ),
                'v1',
                true
            );
            wp_enqueue_style( 'tax-meta-clss', plugins_url( '', dirname( __FILE__ ) . '..' ) . '/assets/css/vendor/tax-meta-clss.css' );
            ATLAS_AssetsService::loadSKPAdminCSS();
        }
        
        if ( $screenID === ATLAS_Config::getPostType() . '_page_' . ATLAS_Config::getSettingsPageSlug() ) {
            ATLAS_AssetsService::loadBootstrapAll();
            ATLAS_AssetsService::addInlineStyle( ATLAS_AssetsService::SKP_ADMIN_CSS_KEY, 'body { background: #f8f9fd; color: #394163; }' );
        }
        
        if ( $screenID === 'edit-' . ATLAS_Config::getPostType() ) {
            ATLAS_AssetsService::loadIconFontsCSS();
        }
        ATLAS_AssetsService::loadSKPAdminCSS();
    }

	//admin bar custom
	public function adminBarCustom(){
		if(function_exists('get_current_screen')){
			$current_screen = get_current_screen();		
			if($current_screen->post_type == ATLAS_Config::getPostType()){
                require_once plugin_dir_path( __FILE__ ) . 'utils/AtlsProBanner.php';
			}
		}
	}
    
    public function loadFrontendScripts()
    {
        $faEnabled = ATLAS_OptionUtil::getInstance()->getOption( 'enable_font_awesome' ) === '1';
        if ( $faEnabled ) {
            ATLAS_AssetsService::enqueRemoteStyle( 'font-awsome', '://use.fontawesome.com/releases/v5.9.0/css/all.css' );
        }
        ATLAS_AssetsService::loadfrontendCSS();
        $postType = get_post_type();
        if ( is_single() && get_post_type() === ATLAS_Config::getPostType() ) {
            ATLAS_AssetsService::loadFrontendScripts();
        }
    }
    
    public function addMetaBoxes()
    {
        add_meta_box(
            'custom_meta_box_topic_selection',
            esc_html__( 'Select Atlas categories', 'atlas-knb-textdomain' ),
            array( 'ATLAS_TopicsMbx', 'renderTopicSelection' ),
            ATLAS_Config::getPostType(),
            'side',
            'default'
        );
        add_meta_box(
            'custom_meta_box_short_description',
            esc_html__( 'Short description', 'atlas-knb-textdomain' ),
            array( 'ATLAS_TopicsMbx', 'renderPostShortDescription' ),
            ATLAS_Config::getPostType(),
            'side',
            'default'
        );
    }
    
    // single template
    public function atlasSingleFilter( $single_template )
    {
        global  $post ;
        if ( $post->post_type == ATLAS_Config::getPostType() ) {
            $single_template = dirname( __FILE__ ) . '/templates/atlas-single.php';
        }
        return $single_template;
    }
    
    // tax template filter
    public function locadCustomTaxTemplate( $tax_template )
    {
        if ( is_tax( ATLAS_Config::getTopicsSlug() ) ) {
            $tax_template = dirname( __FILE__ ) . '/templates/atlas-category.php';
        }
        return $tax_template;
    }
    
    public function registerSettingsGroups()
    {
        register_setting( ATLAS_Config::getOptionsGroupSlug(), ATLAS_Config::getOptionsGroupSlug() );
    }
    
    public function saveCustomPost( $post_id )
    {
        // check autosave
        if ( wp_is_post_autosave( $post_id ) ) {
            return 'autosave';
        }
        //check post revision
        if ( wp_is_post_revision( $post_id ) ) {
            return 'revision';
        }
        // check permissions
        
        if ( isset( $_POST['post_type'] ) && ATLAS_Config::getPostType() === $_POST['post_type'] ) {
            
            if ( !current_user_can( 'edit_page', $post_id ) ) {
                return esc_html__( 'cannot edit page', 'atlas-knb-textdomain' );
            } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
                return esc_html__( 'cannot edit post', 'atlas-knb-textdomain' );
            }
            
            $selectedTopics = ( isset( $_POST['topics_ids'] ) ? $_POST['topics_ids'] : array() );
            $topicsIds = [];
            $hasNoCategory = false;
            
            if ( is_array( $selectedTopics ) && sizeof( $selectedTopics ) > 0 ) {
                for ( $i = 0 ;  $i < sizeof( $selectedTopics ) ;  $i++ ) {
                    
                    if ( $selectedTopics[$i] === 'none' ) {
                        $hasNoCategory = true;
                        unset( $selectedTopics[$i] );
                    } else {
                        array_push( $topicsIds, intval( $selectedTopics[$i] ) );
                    }
                
                }
                if ( sizeof( $topicsIds ) > 0 ) {
                    wp_set_object_terms( $post_id, $topicsIds, ATLAS_Config::getTopicsSlug() );
                }
                if ( $hasNoCategory && sizeof( $topicsIds ) === 0 ) {
                    wp_set_object_terms( $post_id, NULL, ATLAS_Config::getTopicsSlug() );
                }
            }
            
            if ( isset( $_POST['shortDescription'] ) ) {
                update_post_meta( $post_id, 'shortDescription', sanitize_textarea_field( $_POST['shortDescription'] ) );
            }
        }
    
    }
    
    public function addCustomQueryVars( $vars )
    {
        $vars[] = ATLAS_Config::getSearchQVarKey();
        $vars[] = ATLAS_Config::getCategoryQVarKey();
        return $vars;
    }
    
    private function initShortcodes()
    {
        $shortcodes = new ATLAS_ShortcodeManager();
        $shortcodes->run();
    }
    
    private function addAjaxActions()
    {
        add_action( 'wp_ajax_atlas_set_rating', array( 'ATLAS_RatingService', 'setRating' ) );
        add_action( 'wp_ajax_nopriv_atlas_set_rating', array( 'ATLAS_RatingService', 'setRating' ) );
        add_action( 'wp_ajax_atlas_get_rating', array( 'ATLAS_RatingService', 'getRating' ) );
        add_action( 'wp_ajax_nopriv_atlas_get_rating', array( 'ATLAS_RatingService', 'getRating' ) );
    }
    
    public function setCustomEditColumns( $columns )
    {
        return $columns;
    }
    
    private function getSatisfactionHtml( $val, $key )
    {
        return '
            <div style="margin: 0px 3px;">
                <span style="color: #677294; font-size: 24px; display: block;" class="' . $key . ' selected"></span>
                <span style="display: block; text-align: center;"> ' . $val . ' </span>
            </div>
        ';
    }
    
    public function manageCustomEditColumn( $column, $post_id )
    {
        switch ( $column ) {
            case 'atlas_satisfaction':
                $dc = ATLAS_OptionUtil::getInstance()->getPostRating( $post_id, 'disappointed' );
                $nc = ATLAS_OptionUtil::getInstance()->getPostRating( $post_id, 'neutral' );
                $hc = ATLAS_OptionUtil::getInstance()->getPostRating( $post_id, 'happy' );
                echo  '
                <div style="display: flex;">
                    ' . $this->getSatisfactionHtml( $dc, 'unf-sad-face' ) . $this->getSatisfactionHtml( $nc, 'unf-neutral-face' ) . $this->getSatisfactionHtml( $hc, 'unf-smiling-face' ) . '
                </div>
                ' ;
                break;
            case 'atlas_visits':
                echo  ATLAS_OptionUtil::getInstance()->getPostVisits( $post_id ) ;
                break;
        }
    }
    
    public function registerWidgets()
    {
        ATLAS_Sidebars::register();
        ATLAS_WidgetManager::registerWidgets();
    }
    
    private function registerActions()
    {
        add_action( 'init', array( $this, 'initializeHandler' ) );
        add_action( 'admin_menu', array( $this, 'adminMenuHandler' ) );
        add_action( 'add_meta_boxes', array( $this, 'addMetaBoxes' ) );
        add_action( 'save_post', array( $this, 'saveCustomPost' ) );
        add_action( 'new_to_publish', array( $this, 'saveCustomPost' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'loadAdminScripts' ) );
        add_action( "wp_enqueue_scripts", array( $this, 'loadFrontendScripts' ) );
        add_filter( 'single_template', array( $this, 'atlasSingleFilter' ) );
        add_filter( 'taxonomy_template', array( $this, 'locadCustomTaxTemplate' ) );
        add_action( 'admin_init', array( $this, 'registerSettingsGroups' ) );
        add_filter( 'query_vars', array( $this, 'addCustomQueryVars' ) );
        add_filter( 'manage_' . ATLAS_Config::getPostType() . '_posts_columns', array( $this, 'setCustomEditColumns' ) );
        add_action(
            'manage_posts_custom_column',
            array( $this, 'manageCustomEditColumn' ),
            10,
            2
        );
        add_action( 'widgets_init', array( $this, 'registerWidgets' ) );
        add_action("wp_before_admin_bar_render", array($this, 'adminBarCustom'));
    }

}