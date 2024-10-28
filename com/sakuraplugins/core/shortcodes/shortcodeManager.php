<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
require_once(plugin_dir_path(__FILE__) . '../utils/OptionUtil.php');

class ATLAS_ShortcodeManager {
    
	public function run() {
        add_shortcode('atlas_knb', array($this, 'atlas_knb'));
    }

    public function atlas_knb($atts, $content = null) {
        $layout = ATLAS_OptionUtil::getInstance()->getLayout();

        if (!in_array($layout, ATLAS_Config::getAvailableLayouts())) {
            return esc_html__('Error! - Unknown layout', 'atlas-knb-textdomain');
        }

        require_once(plugin_dir_path(__FILE__) . $layout . '/' . $layout . 'Layout.php');

        $class = 'ATLAS_' . $layout . 'Layout';
        $renderEngine = new $class($layout);
        return $renderEngine->getCategoriesContent();
    }
}