<?php
require_once(plugin_dir_path(__FILE__) . '../config.php');
require_once(plugin_dir_path(__FILE__) . '../utils/OptionUtil.php');


    class ATLAS_settingsPage {
        static function render() {
            // $options = get_option(ATLAS_Config::getOptionsGroupSlug());
            ?>
            <div class="container-fluid skp-pt-20 skp-pb-20">
                <form method="post" action="options.php">
                    <?php settings_fields(ATLAS_Config::getOptionsGroupSlug()); ?>	
                    <div class="row">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#general" role="tab" aria-controls="home"
                                aria-selected="true"><?php _e('General', 'atlas-knb-textdomain'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#shortcodes" role="tab" aria-controls="profile"
                                aria-selected="false"><?php _e('Shortcodes', 'atlas-knb-textdomain'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="labels-tab" data-toggle="tab" href="#labels" role="tab" aria-controls="contact"
                                aria-selected="false"><?php _e('Labels', 'atlas-knb-textdomain'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="css-tab" data-toggle="tab" href="#custom-css" role="tab" aria-controls="contact"
                                aria-selected="false"><?php _e('Custom CSS', 'atlas-knb-textdomain'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="order-info" data-toggle="tab" href="#cpt-order" role="tab" aria-controls="contact"
                                aria-selected="false"><?php _e('Articles / Categories order', 'atlas-knb-textdomain'); ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="myTabContent">
                        <!-- start general settings -->
                        <?php require_once(plugin_dir_path(__FILE__) . 'admin/general.php'); ?>
                        <!-- end general settings -->
                        <!-- start shortcodes -->
                        <?php require_once(plugin_dir_path(__FILE__) . 'admin/shortcodes.php'); ?>
                        <!-- end shortcodes -->
                        <!-- start labels -->
                        <?php require_once(plugin_dir_path(__FILE__) . 'admin/labels.php'); ?>
                        <!-- end labels -->
                        <!-- start custom CSS -->
                        <?php require_once(plugin_dir_path(__FILE__) . 'admin/custom-css.php'); ?>
                        <!-- end custom CSS -->
                        <!-- start post order -->
                        <?php require_once(plugin_dir_path(__FILE__) . 'admin/custom-order.php'); ?>
                        <!-- end post order -->
                    </div>
                </form>
            </div>
            <?php
        }
    }
?>