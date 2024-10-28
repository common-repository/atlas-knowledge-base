<?php
require_once(plugin_dir_path(__FILE__) . '../../config.php');
require_once(plugin_dir_path(__FILE__) . '../../utils/OptionUtil.php');
?>
<div class="tab-pane fade show skp-tab-inner-content" id="cpt-order" role="tabpanel" aria-labelledby="home-tab">
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="skp-admin-panel">
                <div class="panel-title">
                    <?php esc_html_e('Atlas categories and posts re-order.', 'atlas-knb-textdomain'); ?>
                </div>
                <div class="skp-separator"></div>
                <div class="panel-content">
                    <div><?php _e('You might want to change the order of Atlas categories and posts. In order to do so, you can use the "<a target="_blank" href="https://wordpress.org/plugins/intuitive-custom-post-order/">Intuitive Custom Post Order</a>" free plugin.', 'atlas-knb-textdomain'); ?></div>
                    <br />
                    <br />
                    <ul>
                        <li><?php esc_html_e('1. Install and activate the plugin.', 'atlas-knb-textdomain'); ?></li>
                        <li><?php esc_html_e('2. Go to Admin > Settings > Intuitive CPO.', 'atlas-knb-textdomain'); ?></li>
                        <li><?php esc_html_e('3. Choose "Atlas - articles" and "Atlas - categories" and click Update.', 'atlas-knb-textdomain'); ?></li>
                        <li><?php esc_html_e('4. Now you can drag and drop (change order) on both Atlas articles and Atlas categories.', 'atlas-knb-textdomain'); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
