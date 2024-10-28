<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
require_once plugin_dir_path( __FILE__ ) . '../BaseLayout.php';
require_once plugin_dir_path( __FILE__ ) . '../IBaseLayout.php';
require_once plugin_dir_path( __FILE__ ) . '../../utils/OptionUtil.php';
require_once plugin_dir_path( __FILE__ ) . '../../utils/Utils.php';
require_once plugin_dir_path( __FILE__ ) . '../../utils/SessionUtils.php';
class ATLAS_StartupLayout extends ATLAS_BaseLayout implements  ATLAS_IBaseLayout 
{
    private  $category ;
    protected function getCollorIndicator( $termMeta )
    {
        $hasColor = isset( $termMeta['atlas_color_field_id'] );
        
        if ( $hasColor ) {
            return '<div class="category-color-info" style="background: ' . esc_attr( $termMeta['atlas_color_field_id'][0] ) . ';"></div>';
        } else {
            return '';
        }
    
    }
    
    private function getSearchNav()
    {
        return '
            <ul class="knb-nav">
                <li><a href="' . ATLAS_OptionUtil::getInstance()->getNavHomepageUrl() . '">' . esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'homeNavLabel' ) ) . '</a></li>
                <li><span class="unf-keyboard_arrow_right"></span></li>
                <li class="inactive">' . esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'searchResultNavLabel' ) ) . '</li>
            </ul>
        ';
    }
    
    private function getSearchResult( $s )
    {
        $args = array(
            's'              => $s,
            'order'          => 'DESC',
            'posts_per_page' => -1,
            'post_type'      => ATLAS_Config::getPostType(),
            'post_status'    => 'publish',
        );
        $the_query = new WP_Query( $args );
        $articles = '';
        $count = 0;
        $notFound = '';
        
        if ( $the_query->have_posts() ) {
            $articles = '<div class="category-articles"><div class="article-separator"></div>';
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                $count++;
                $separator = ( $count < $the_query->post_count ? '<div class="separator"></div>' : '' );
                global  $post ;
                $terms = wp_get_post_terms( get_the_id(), ATLAS_Config::getTopicsSlug() );
                $permalink = get_permalink( get_the_id() );
                if ( is_array( $terms ) && sizeof( $terms ) > 1 ) {
                    $permalink = add_query_arg( ATLAS_Config::getCategoryQVarKey(), $terms[0]->slug, $permalink );
                }
                $articles .= '
                    <a href="' . $permalink . '" class="article-list-item">
                        <h2 class="title" href=#>' . get_the_title() . '</h2>
                        <p class="short-description">' . esc_html( get_post_meta( get_the_id(), 'shortDescription', true ) ) . '</p>
                        ' . $this->getArticleInfoContent( $post ) . '
                        <div class="atlas-read-more"><span>' . esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'readMore' ) ) . '</span></div>
                    </a>' . $separator . '
                ';
            }
            $articles .= '</div>';
        } else {
            $notFound = '<p class="not-found">' . esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'resultsNotFound' ) ) . '</p>';
        }
        
        require_once plugin_dir_path( __FILE__ ) . '../../sidebars/SidebarManager.php';
        return '
        <div class="knb-container">
            <div class="knb-search-ui">
                <h1 class="knb-title">' . esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'knbAtlasTitle' ) ) . '</h1>
                <p class="knb-subtitle">' . esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'knbAtlasSubTitle' ) ) . '</p>
                <div class="search-imput-ui">
                    ' . $this->getSearchContent() . '
                </div>
            </div>
            <div class="knb-container">
                <div class="knb-inner-content-top">
                    ' . $this->getSearchNav() . '
                    <div class="knb-inner-content">
                        <div class="knb-main">
                            <div class="knb-info search-info">
                                <div class="info-content">
                                    <h2 class="search-title" href=#><span>' . esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'searchResultTitle' ) ) . '</span>' . $s . '</h2>
                                </div>
                            </div>
                            ' . $articles . '
                            ' . $notFound . '
                        </div>
                        ' . ATLAS_SidebarManager::getInstance()->getSidebarContent( ATLAS_SidebarManager::SIDEBAR_SEARCH_RESULTS_ID ) . '
                    </div>
                </div>
            </div>
            <div>' . $this->getContactLink() . $this->getSocialLinks() . '</div>
        </div>
        ';
    }
    
    public function getCategoriesContent()
    {
        return '';
    }
    
    private function renderCategoryNav()
    {
        ?>
            <ul class="knb-nav">
                <li><a href="<?php 
        echo  esc_url( ATLAS_OptionUtil::getInstance()->getNavHomepageUrl() ) ;
        ?>"><?php 
        echo  esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'homeNavLabel' ) ) ;
        ?></a></li>
                <li><span class="unf-keyboard_arrow_right"></span></li>
                <li class="inactive"><?php 
        echo  esc_html( $this->category->name ) ;
        ?></li>
            </ul>
        <?php 
    }
    
    private function getCategoryInfoContent( $category )
    {
        $foundLabel = strtr( ATLAS_OptionUtil::getInstance()->getLocale( 'articlesFoundSingular' ), [
            '$articlesNo' => $category->count,
        ] );
        if ( $category->count > 1 ) {
            $foundLabel = strtr( ATLAS_OptionUtil::getInstance()->getLocale( 'articlesFoundPlural' ), [
                '$articlesNo' => $category->count,
            ] );
        }
        $authors = $this->getCategoryAuthors( $this->getTaxPosts( $category->slug ) );
        $count = 1;
        $step = 4;
        $avatars = '';
        foreach ( $authors as $author ) {
            $avatarStyle = ( $count > 1 ? '-' . $count * $step : '0' );
            $avatars .= '<img class="knb-author-avatar" style="left: ' . $avatarStyle . 'px;" src="' . $author['avatar'] . '" alt="" />';
            $count++;
        }
        $infoStyle = ( sizeof( $authors ) === 1 ? '14' : '4' );
        return '
            <div style="display: flex;">
                <div class="knb-avatarts" style="margin-right: ' . $infoStyle . 'px;">
                ' . $avatars . '
                </div>
                <div class="knb-c-info">
                    <p class="articles-found">' . esc_html( $foundLabel ) . '</p>
                    <p class="articles-found">' . $this->formatAuthorsDisplayNames( $authors ) . '</p>
                </div>
            </div>
        ';
    }
    
    private function getArticleInfoContent( $postEntry )
    {
        $authors = $this->getCategoryAuthors( [ $postEntry ] );
        $count = 0;
        $avatars = '';
        foreach ( $authors as $author ) {
            $avatarStyle = ( $count > 0 ? '-8' : '0' );
            $avatars .= '<img class="knb-author-avatar" style="left: ' . $avatarStyle . 'px;" src="' . esc_url( $author['avatar'] ) . '" alt="" />';
            $count++;
        }
        return '
            <div style="display: flex;">
                <div class="knb-avatarts" style="margin-right: 14px;">
                ' . $avatars . '
                </div>
                <div class="knb-c-info">
                    <p class="articles-found">' . $this->formatAuthorsDisplayNames( $authors ) . '</p>
                    <p class="articles-found">
                    ' . esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'articleUpdatedAt' ) ) . ' ' . ATLAS_OptionUtil::getInstance()->getArticleUpdateTime( $postEntry ) . '
                    </p>
                </div>
            </div>
        ';
    }
    
    public function renderCategory()
    {
        return;
        $this->category = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
        $posts = $this->getTaxPosts( $this->category->slug );
        ?>
        <div class="knb-container">
            <div class="knb-search-ui">
                <h1 class="knb-title"><?php 
        echo  esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'knbAtlasTitle' ) ) ;
        ?></h1>
                <p class="knb-subtitle"><?php 
        echo  esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'knbAtlasSubTitle' ) ) ;
        ?></p>
                <div class="search-imput-ui">
                <?php 
        echo  $this->getSearchContent() ;
        ?>
                </div>
            </div>
            <div class="knb-container">
                <div class="knb-inner-content-top">
                    <?php 
        $this->renderCategoryNav();
        ?>
                    <div class="knb-inner-content">
                        <div class="knb-main">
                            <div class="knb-info">
                                <?php 
        $termMeta = get_term_meta( $this->category->term_id );
        ?>
                                <?php 
        echo  $this->getCategoryIcon( $termMeta ) ;
        ?>
                                <div class="info-content">
                                    <h2 class="title" href=#><?php 
        echo  esc_html( $this->category->name ) ;
        ?></h2>
                                    <?php 
        
        if ( $this->category->description ) {
            ?>
                                        <p class="short-description"><?php 
            echo  esc_html( $this->category->description ) ;
            ?></p>
                                    <?php 
        }
        
        ?>
                                    <?php 
        echo  $this->getCategoryInfoContent( $this->category ) ;
        ?>
                                </div>
                            </div>
                            <div class="category-articles">
                                <?php 
        $count = 0;
        ?>
                                <div class="article-separator"></div>
                                <?php 
        foreach ( $posts as $postEntry ) {
            ?>
                                    <?php 
            $permalink = get_the_permalink( $postEntry->ID );
            $postMultipleCategories = wp_get_post_terms( $postEntry->ID, ATLAS_Config::getTopicsSlug() );
            if ( is_array( $postMultipleCategories ) && sizeof( $postMultipleCategories ) > 1 ) {
                $permalink = add_query_arg( ATLAS_Config::getCategoryQVarKey(), $this->category->slug, $permalink );
            }
            ?>
                                    <?php 
            $count++;
            ?>
                                    <a href="<?php 
            echo  $permalink ;
            ?>" class="article-list-item">
                                        <h3 class="title" href=#><?php 
            echo  $postEntry->post_title ;
            ?></h3>
                                        <p class="short-description"><?php 
            echo  esc_html( get_post_meta( $postEntry->ID, 'shortDescription', true ) ) ;
            ?></p>
                                        <?php 
            echo  $this->getArticleInfoContent( $postEntry ) ;
            ?>
                                        <div class="atlas-read-more"><span><?php 
            echo  esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'readMore' ) ) ;
            ?></span></div>
                                    </a>
                                    <?php 
            if ( $count < sizeof( $posts ) ) {
                ?>
                                        <div class="article-separator"></div>
                                    <?php 
            }
            ?>
                                <?php 
        }
        ?>
                            </div>
                        </div>
                        <?php 
        require_once plugin_dir_path( __FILE__ ) . '../../sidebars/SidebarManager.php';
        ATLAS_SidebarManager::getInstance()->displaySidebarContent( ATLAS_SidebarManager::SIDEBAR_CATEGORY_ID );
        ?>
                    </div>
                </div>
            </div>
            <div><?php 
        echo  $this->getContactLink() ;
        echo  $this->getSocialLinks() ;
        ?></div>
        </div>
        <?php 
    }
    
    private function renderArticleNav()
    {
        ?>
            <ul class="knb-nav">
                <li><a href="<?php 
        echo  esc_url( ATLAS_OptionUtil::getInstance()->getNavHomepageUrl() ) ;
        ?>"><?php 
        echo  esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'homeNavLabel' ) ) ;
        ?></a></li>
                <li><span class="unf-keyboard_arrow_right"></span></li>
                <?php 
        
        if ( $this->category ) {
            ?>
                    <li><a href="<?php 
            echo  get_category_link( $this->category->term_id ) ;
            ?>"><?php 
            echo  esc_html( $this->category->name ) ;
            ?></a></li>
                <?php 
        }
        
        ?>
                <li><span class="unf-keyboard_arrow_right"></span></li>
                <li class="inactive"><?php 
        echo  get_the_title() ;
        ?></li>
            </ul>
        <?php 
    }
    
    public function renderArticle()
    {
        return;
        $isMultipleCategory = ( get_query_var( ATLAS_Config::getCategoryQVarKey() ) ? true : false );
        $postCategories = wp_get_post_terms( get_the_ID(), ATLAS_Config::getTopicsSlug() );
        if ( $isMultipleCategory ) {
            $this->category = ATLAS_Utils::getObjectFromCollectionByKey( $postCategories, 'slug', get_query_var( ATLAS_Config::getCategoryQVarKey() ) );
        }
        if ( is_array( $postCategories ) && sizeof( $postCategories ) === 1 ) {
            $this->category = $postCategories[0];
        }
        global  $post ;
        ?>
        <div class="knb-container">
            <div class="knb-search-ui">
                <h1 class="knb-title"><?php 
        echo  esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'knbAtlasTitle' ) ) ;
        ?></h1>
                <p class="knb-subtitle"><?php 
        echo  esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'knbAtlasSubTitle' ) ) ;
        ?></p>
                <div class="search-imput-ui">
                <?php 
        echo  $this->getSearchContent() ;
        ?>
                </div>
            </div>
            <div class="knb-container">
                <div class="knb-inner-content-top">
                    <?php 
        $this->renderArticleNav();
        ?>
                    <div class="knb-inner-content">
                        <div class="knb-main">
                            <div class="knb-info">
                                <div class="info-content">
                                    <h2 class="title" href=#><?php 
        echo  get_the_title() ;
        ?></h2>
                                    <p class="article-short-description"><?php 
        echo  esc_html( get_post_meta( get_the_ID(), 'shortDescription', true ) ) ;
        ?></p>
                                    <?php 
        echo  $this->getArticleInfoContent( $post ) ;
        ?>
                                </div>
                            </div>
                            <div class="article-separator article-single-separator"></div>
                            <div class="article-content">
                                <div id="post-<?php 
        the_ID();
        ?>" <?php 
        post_class();
        ?>>
                                    <?php 
        // Start the Loop
        while ( have_posts() ) {
            the_post();
            the_content();
        }
        // End of the loop.
        ?>
                                </div>
                            </div>
                            <div id="atlas-rating" class="article-rating" data-articleid="<?php 
        echo  get_the_ID() ;
        ?>">
                                <p class="q"><?php 
        echo  esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'ratingQuestion' ) ) ;
        ?></p>
                                <div class="ratings">
                                    <div id="disappointed" class="rating disappointed">
                                        <span id="disappointed-off" class="unf-sad-face1 unselected"></span>
                                        <span id="disappointed-on" class="unf-sad-face selected"></span>
                                    </div>
                                    <div id="neutral" class="rating neutral">
                                        <span id="neutral-off" class="unf-neutral-face1 unselected"></span>
                                        <span id="neutral-on" class="unf-neutral-face selected"></span>
                                    </div>
                                    <div id="happy" class="rating happy">
                                        <span id="happy-off" class="unf-smiling-face1 unselected"></span>
                                        <span id="happy-on" class="unf-smiling-face selected"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
        require_once plugin_dir_path( __FILE__ ) . '../../sidebars/SidebarManager.php';
        ATLAS_SidebarManager::getInstance()->displaySidebarContent( ATLAS_SidebarManager::SIDEBAR_SINGLE_ID );
        ?>
                    </div>
                </div>
            </div>
            <div><?php 
        echo  $this->getContactLink() ;
        echo  $this->getSocialLinks() ;
        ?></div>
        </div>
        <?php 
    }

}