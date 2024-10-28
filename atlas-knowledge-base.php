<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Don't allow direct access
/*
Plugin Name: Atlas - Knowledge Base
Plugin URI: https://www.sakuraplugins.com/plugins/atlas-wordpress-knowledge-base
Description: Atlas - Knowledge Base
Author: SakuraPlugins
Version: 3.3.0
Author URI: https://www.sakuraplugins.com/contact
*/


require_once plugin_dir_path( __FILE__ ) . 'com/sakuraplugins/core/core.php';
$ATLAS_coreInstance = new ATLAS_Core();
$ATLAS_coreInstance->run();
