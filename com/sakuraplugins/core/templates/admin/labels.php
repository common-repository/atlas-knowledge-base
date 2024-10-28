<?php
require_once(plugin_dir_path(__FILE__) . '../../config.php');
require_once(plugin_dir_path(__FILE__) . '../../utils/OptionUtil.php');
?>
<div class="tab-pane fade show skp-tab-inner-content" id="labels" role="tabpanel" aria-labelledby="home-tab">
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="skp-admin-panel">
                <div class="panel-title">
                    <?php esc_html_e('Available front-end labels.', 'atlas-knb-textdomain'); ?>
                </div>
                <div class="skp-separator"></div>
                <div class="panel-content">
                    <div class="alert alert-info" role="alert">
                        <?php esc_html_e('Labels used across the front-end for all layouts.', 'atlas-knb-textdomain'); ?>
                    </div>
                    <?php
                    $localesKeys = ATLAS_Config::LOCALES();
                    ?>
                    <?php foreach ($localesKeys as $key => $lObject): ?>
                    <div class="md-form">
                        <input type="text"
                        value="<?= esc_attr(ATLAS_OptionUtil::getInstance()->getLocale($key)); ?>" 
                        name="<?= esc_attr(ATLAS_Config::getOptionsGroupSlug());?>[locales][<?= $key; ?>]"
                        class="form-control">
                        <label for="form1"><?= esc_html($lObject['desc']); ?></label>
                    </div>
                    <?php endforeach; ?>
                    <p><button type="submit" class="btn btn-primary btn-md float-right"><?php esc_html_e('Save', 'atlas-knb-textdomain'); ?></button></p>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="alert alert-warning" role="alert">
                <b><?php esc_html_e('NOTE!', 'atlas-knb-textdomain'); ?></b> <?php esc_html_e('Some of the labels contain a variable (Ex "$articlesNo"). That allows you to re-arrange a sentence that contains dynamic data. Ex: "$articlesNo articles found in this collection" can be changed to "We found $articlesNo articles within this collection". Please make sure you don\'t remove the variables.', 'atlas-knb-textdomain'); ?>
            </div>
        </div>
    </div>
</div>
