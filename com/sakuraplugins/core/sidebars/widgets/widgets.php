<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class ATLAS_WidgetManager {

	static function widgetTextFieldHelper($fieldId, $fieldName, $value, $label = '') {
		?>
		<input id="<?= esc_attr($fieldId); ?>" name="<?= esc_attr($fieldName); ?>" type="text" value="<?= $value; ?>" />
		<label for="<?= $fieldId; ?>"><?= $label; ?></label>
		<?php
    }
    
    static function registerWidgets() {
        require_once(plugin_dir_path(__FILE__) . 'PopularW.php');
        require_once(plugin_dir_path(__FILE__) . 'RecentlyUpdatedW.php');
        require_once(plugin_dir_path(__FILE__) . 'NewestArticlesW.php');
        require_once(plugin_dir_path(__FILE__) . 'NewestInCategoryW.php');
        require_once(plugin_dir_path(__FILE__) . 'RecentlyUpdatedInCategory.php');
        require_once(plugin_dir_path(__FILE__) . 'RecentlyViewedW.php');
        
        register_widget('ATLAS_PopularW');
        register_widget('ATLAS_RecentlyUpdatedW');
        register_widget('ATLAS_NewestArticlesW');
        register_widget('ATLAS_NewestInCategoryW');
        register_widget('ATLAS_RecentlyUpdatedInCategoryW');
        register_widget('ATLAS_RecentlyViewedW');
    }
}

?>