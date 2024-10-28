<?php
require_once(plugin_dir_path(__FILE__) . '../../config.php');
require_once(plugin_dir_path(__FILE__) . '../../utils/OptionUtil.php');
?>
<div class="tab-pane fade show skp-tab-inner-content" id="custom-css" role="tabpanel" aria-labelledby="home-tab">
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="skp-admin-panel">
                <div class="panel-title">
                    <?php esc_html_e('Custom CSS.', 'atlas-knb-textdomain'); ?>
                </div>
                <div class="skp-separator"></div>
                <div class="panel-content">
                    <textarea style="width: 100%; min-height: 300px;" 
                    name="<?= esc_attr(ATLAS_Config::getOptionsGroupSlug());?>[customCSS]"><?= esc_textarea(ATLAS_OptionUtil::getInstance()->getOption('customCSS')); ?></textarea>
                    <p><button type="submit" class="btn btn-primary btn-md float-right"><?php esc_html_e('Save', 'atlas-knb-textdomain'); ?></button></p>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="alert alert-info" role="alert">
                <?php esc_html_e('Add custom CSS, best option to alter existing styles.', 'atlas-knb-textdomain'); ?>
            </div>
        </div>
    </div>
</div>
