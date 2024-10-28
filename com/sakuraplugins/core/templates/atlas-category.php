<?php
require_once(plugin_dir_path(__FILE__) . '../utils/OptionUtil.php');
$layout = ATLAS_OptionUtil::getInstance()->getLayout();
require_once(plugin_dir_path(__FILE__) . '../shortcodes/' . $layout .'/' . $layout .'Layout.php');
$class = 'ATLAS_' . $layout . 'Layout';
$renderEngine = new $class($layout);
?>
<?php get_header(); ?>
<?php $renderEngine->renderCategory(); ?>
<?php get_footer(); ?>