<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
require_once plugin_dir_path( __FILE__ ) . '../BaseLayout.php';
require_once plugin_dir_path( __FILE__ ) . '../IBaseLayout.php';
require_once plugin_dir_path( __FILE__ ) . '../../utils/OptionUtil.php';
require_once plugin_dir_path( __FILE__ ) . '../../utils/Utils.php';
require_once plugin_dir_path( __FILE__ ) . '../../utils/SessionUtils.php';
class ATLAS_IntercomLayout extends ATLAS_BaseLayout implements  ATLAS_IBaseLayout 
{
    private  $category ;
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
            <div class="knb-category-info">
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
            <div class="knb-category-info">
                <div class="knb-avatarts" style="margin-right: 14px;">
                ' . $avatars . '
                </div>
                <div class="knb-c-info">
                    <p class="articles-found">' . $this->formatAuthorsDisplayNames( $authors ) . '</p>
                    <p class="articles-found">
                    ' . esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'articleUpdatedAt' ) ) . ' ' . esc_html( ATLAS_OptionUtil::getInstance()->getArticleUpdateTime( $postEntry ) ) . '
                    </p>
                </div>
            </div>
        ';
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
    
    private function getSearchNav()
    {
        return '
            <ul class="knb-nav">
                <li><a href="' . esc_url( ATLAS_OptionUtil::getInstance()->getNavHomepageUrl() ) . '">' . esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'homeNavLabel' ) ) . '</a></li>
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
            $articles = '<div class="category-articles">';
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
                        ' . '' . '
                        <div class="atlas-read-more"><span>' . esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'readMore' ) ) . '</span></div>
                    </a>' . $separator . '
                ';
            }
            $articles .= '</div>';
        } else {
            $notFound = '<p class="not-found">' . esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'resultsNotFound' ) ) . '</p>';
        }
        
        return '
        <div class="knb-container">
        ' . $this->getSearchContent() . '
        ' . $this->getSearchNav() . '
        <div class="knb-category-content">
            <div class="knb-category-info knb-search">
                <div class="content">
                    <h1 class="search-title" href=#><span>' . esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'searchResultTitle' ) ) . '</span> ' . $s . '</h1>
                </div>
            </div>
            ' . $articles . '
            ' . $notFound . '
            ' . $this->getContactLink() . $this->getSocialLinks() . '
        </div>
        </div>
        ';
    }
    
    public function getCategoriesContent()
    {
        $searchTerm = get_query_var( ATLAS_Config::getSearchQVarKey() );
        if ( $searchTerm ) {
            return $this->getSearchResult( $searchTerm );
        }
        $atlasCategories = get_terms( ATLAS_Config::getTopicsSlug(), [
            'hide_empty' => TRUE,
            'parent'     => 0,
        ] );
        $categListings = '';
        foreach ( $atlasCategories as $category ) {
            $termMeta = get_term_meta( $category->term_id );
            $catDescription = '';
            if ( $category->description ) {
                $catDescription = '<p class="short-description">' . $category->description . '</p>';
            }
            $catInfo = '';
            $categListings .= '
                <a href="' . get_term_link( $category->term_id ) . '" class="knb-category">
                    ' . $this->getCategoryIcon( $termMeta ) . '
                    <div class="content">
                        <h2 class="title" href=#>' . $category->name . '</h2>
                        ' . $catDescription . '
                        ' . $catInfo . '
                    </div>
                </a>
            ';
        }
        return '
        <div class="knb-container">
        ' . $this->getSearchContent() . '
            <div class="knb-categories">' . $categListings . $this->getContactLink() . $this->getSocialLinks() . '</div>
        </div>
        ';
    }
    
    public function renderCategory()
    {
        $this->category = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
        $posts = $this->getTaxPosts( $this->category->slug );
        $this->openMainContainer();
        ?>
        <?php 
        echo  $this->getSearchContent() ;
        ?>
        <?php 
        $this->renderCategoryNav();
        ?>
        <div class="knb-category-single">
            <div class="knb-category-content">
                <div class="knb-category-info">
                    <?php 
        $termMeta = get_term_meta( $this->category->term_id );
        ?>
                    <?php 
        echo  $this->getCategoryIcon( $termMeta ) ;
        ?>
                    <div class="content">
                        <h1 class="title" href=#><?php 
        echo  esc_html( $this->category->name ) ;
        ?></h1>
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
        echo '' ;
        ?>
                    </div>
                </div>
                <div class="category-articles">
                    <?php 
        $count = 0;
        ?>
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
                            <h2 class="title" href=#><?php 
            echo  esc_html( $postEntry->post_title ) ;
            ?></h2>
                            <p class="short-description"><?php 
            echo  esc_html( get_post_meta( $postEntry->ID, 'shortDescription', true ) ) ;
            ?></p>
                            <?php 
            echo  '' ;
            ?>
                            <div class="atlas-read-more"><span><?php 
            echo  esc_html( ATLAS_OptionUtil::getInstance()->getLocale( 'readMore' ) ) ;
            ?></span></div>
                        </a>
                        <?php 
            if ( $count < sizeof( $posts ) ) {
                ?>
                            <div class="separator"></div>
                        <?php 
            }
            ?>
                    <?php 
        }
        ?>
                </div>
            </div>
            <?php 
        echo  $this->getContactLink() ;
        ?>
            <?php 
        echo  $this->getSocialLinks() ;
        ?>
        </div>
        <?php 
        $this->closeMainContainer();
    }
    
    public function renderArticle()
    {
        ATLAS_OptionUtil::getInstance()->incrementPostVisits( get_the_ID() );
        ATLAS_SessionUtils::getInstance()->addViewedArticle( get_the_ID() );
        $isMultipleCategory = ( get_query_var( ATLAS_Config::getCategoryQVarKey() ) ? true : false );
        $postCategories = wp_get_post_terms( get_the_ID(), ATLAS_Config::getTopicsSlug() );
        if ( $isMultipleCategory ) {
            $this->category = ATLAS_Utils::getObjectFromCollectionByKey( $postCategories, 'slug', get_query_var( ATLAS_Config::getCategoryQVarKey() ) );
        }
        if ( is_array( $postCategories ) && sizeof( $postCategories ) === 1 ) {
            $this->category = $postCategories[0];
        }
        global  $post ;
        $this->openMainContainer();
        ?>
            <?php 
        echo  $this->getSearchContent() ;
        ?>
            <?php 
        $this->renderArticleNav();
        ?>
            <div class="knb-article-single">
                <div class="knb-article-content">
                    <div class="knb-article-info">
                        <div class="content">
                            <h1 class="title"><?php 
        echo  get_the_title() ;
        ?></h1>
                            <p class="short-description"><?php 
        echo  esc_html( get_post_meta( get_the_ID(), 'shortDescription', true ) ) ;
        ?></p>
                            <?php 
        echo  '' ;
        ?>
                        </div>
                    </div>
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
                    <?php 
        ?>
                </div>
                <?php 
        echo  $this->getContactLink() ;
        ?>
                <?php 
        echo  $this->getSocialLinks() ;
        ?>
            </div>
        <?php 
        $this->closeMainContainer();
    }

}