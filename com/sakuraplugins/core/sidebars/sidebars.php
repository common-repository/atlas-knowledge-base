<?php
if ( ! defined( 'ABSPATH' ) ) exit;
require_once(plugin_dir_path(__FILE__) . 'SidebarManager.php');

/**
 * sidebars
 */
class ATLAS_Sidebars {
	
	static function register() {
		ATLAS_SidebarManager::getInstance()->registerSidebar(array(
			'name' => esc_html__( 'Atlas - Article Sidebar', 'atlas-knb-textdomain'),
			'id' => ATLAS_SidebarManager::SIDEBAR_SINGLE_ID,
			'description' => esc_html__('Atlas - Article Sidebar, shows within the single article pages (only for certain layouts).', 'atlas-knb-textdomain'),
			'before_widget' => '<div id="%1$s" class="atlas_widget widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="atlas_widget-title">',
			'after_title' => '</h3>'
		));

		ATLAS_SidebarManager::getInstance()->registerSidebar(array(
			'name' => esc_html__( 'Atlas - Category Sidebar', 'atlas-knb-textdomain'),
			'id' => ATLAS_SidebarManager::SIDEBAR_CATEGORY_ID,
			'description' => esc_html__('Atlas - Category Sidebar, shows within Atlas category pages (only for certain layouts).', 'atlas-knb-textdomain'),
			'before_widget' => '<div id="%1$s" class="atlas_widget widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="atlas_widget-title">',
			'after_title' => '</h3>'			
		));

		ATLAS_SidebarManager::getInstance()->registerSidebar(array(
			'name' => esc_html__( 'Atlas - Search Result Sidebar', 'atlas-knb-textdomain'),
			'id' => ATLAS_SidebarManager::SIDEBAR_SEARCH_RESULTS_ID,
			'description' => esc_html__('Atlas - Search Result Sidebar, shows within the Atlas search result pages (only for certain layouts).', 'atlas-knb-textdomain'),
			'before_widget' => '<div id="%1$s" class="atlas_widget widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="atlas_widget-title">',
			'after_title' => '</h3>'			
		));

		ATLAS_SidebarManager::getInstance()->registerSidebar(array(
			'name' => esc_html__( 'Atlas - Home Sidebar', 'atlas-knb-textdomain'),
			'id' => ATLAS_SidebarManager::SIDEBAR_HOME_ID,
			'description' => esc_html__('Atlas - Home Sidebar, shows on Atlas shortcode page, right under Atlas categories (only for certain layouts).', 'atlas-knb-textdomain'),
			'before_widget' => '<div id="%1$s" class="atlas_widget widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="atlas_widget-title">',
			'after_title' => '</h3>'
		));
	}
}
?>
