<?php
if ( ! defined( 'ABSPATH' ) ) exit;
require_once(plugin_dir_path(__FILE__) . '../utils/OptionUtil.php');
class ATLAS_AssetsService {

    const SKP_ADMIN_CSS_KEY = 'skp-admin-css';
    const SKP_FRONTEND_CSS_KEY = 'skp-frontend-css';
    const SKP_ICON_FONTS_CSS = 'skp-iconfonts-css';

    static function loadBootstrapAll() {
        wp_enqueue_script('jquery');
        $bootstrapJS = plugins_url('', dirname( __FILE__ )) . '../../assets/js/vendor/bootstrap.min.js';
        wp_enqueue_style('bootstrap.min', plugins_url('', dirname( __FILE__ )) . '../../assets/css/vendor/bootstrap.min.css');
        wp_enqueue_script('bootstrap.min', $bootstrapJS, array('jquery'), 'v1', true);
        self::enqueRemoteStyle('font-awsome', '://use.fontawesome.com/releases/v5.8.2/css/all.css');
    }

    static function loadSKPAdminCSS() {
        wp_enqueue_style(self::SKP_ADMIN_CSS_KEY, plugins_url('', dirname( __FILE__ )) . '../../assets/css/skp-admin.css');
    }

    static function loadfrontendCSS() {
        $layout = ATLAS_OptionUtil::getInstance()->getLayout();
        wp_enqueue_style(self::SKP_FRONTEND_CSS_KEY, plugins_url('', dirname( __FILE__ )) . '../../assets/css/' . $layout . '-atlas-front.css');
        wp_add_inline_style(self::SKP_FRONTEND_CSS_KEY, ATLAS_OptionUtil::getInstance()->getOption('customCSS'));
        wp_enqueue_style(self::SKP_ICON_FONTS_CSS, plugins_url('', dirname( __FILE__ )) . '../../assets/css/icon-fonts/style.css');
    }

    static function loadIconFontsCSS() {
        wp_enqueue_style(self::SKP_ICON_FONTS_CSS, plugins_url('', dirname( __FILE__ )) . '../../assets/css/icon-fonts/style.css');
    }

    static function addInlineStyle($handler, $style = '') {
        wp_add_inline_style($handler, $style);
    }

    // jqueryui
    public static function enqueJqueryUI() {
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-widget');
        wp_enqueue_script('jquery-ui-selectable');
        wp_enqueue_script('jquery-ui-button');
        wp_enqueue_script('jquery-ui-mouse');
        wp_enqueue_script('jquery-ui-spinner');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-dialog');
    }

    // load thinkbox 
    public static function enqueueThickbox() {
        wp_enqueue_script('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_style('thickbox');
    }

	//enque required fonts
	static function enqueRemoteStyle($key, $url) {
	    $protocol = is_ssl() ? 'https' : 'http';
	    wp_enqueue_style($key, $protocol . $url);
    }

    static function enqueRemoteScript($key, $url) {
        $protocol = is_ssl() ? 'https' : 'http';
        wp_enqueue_script($key, $protocol . $url, array(), '3', true);
    }

    static function loadFrontendScripts() {
        wp_enqueue_script('jquery');
        wp_register_script('atlas-js', plugins_url('', dirname( __FILE__ )) . '../../assets/js/atlas.js', array('jquery'), 'v1', true);
        wp_localize_script('atlas-js', 'atlas_ajax_object', array('AJAX_URL' => admin_url( 'admin-ajax.php')));
        wp_enqueue_script('atlas-js');
    }
}
?>
