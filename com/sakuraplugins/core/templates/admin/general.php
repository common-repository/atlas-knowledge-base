<?php
require_once(plugin_dir_path(__FILE__) . '../../config.php');
require_once(plugin_dir_path(__FILE__) . '../../utils/OptionUtil.php');
?>
<div class="tab-pane fade show active skp-tab-inner-content" id="general" role="tabpanel" aria-labelledby="home-tab">
    <div class="row">
        <div class="col md">
            <div class="skp-admin-panel">
                <div class="panel-title">
                <?php esc_html_e('Permalinks', 'atlas-knb-textdomain'); ?>
                </div>
                <div class="skp-separator"></div>
                <div class="panel-content">
                    <div class="md-form">
                        <input type="text"
                        value="<?= esc_attr(ATLAS_Config::getReWriteSlug()); ?>" 
                        name="<?= esc_attr(ATLAS_Config::getOptionsGroupSlug());?>[<?= esc_attr(ATLAS_Config::ATLAS_ARTICLE_REWRITE_KEY); ?>]"
                        class="form-control">
                        <label for="form1"><?php esc_html_e('Atlas article rewrite', 'atlas-knb-textdomain'); ?></label>
                    </div>
                    <div class="alert alert-info" role="alert">
                        <?php esc_html_e('Ex: https://yourwebsite.com/', 'atlas-knb-textdomain'); ?><?= ATLAS_Config::getReWriteSlug(); ?><?php esc_html_e('/article-name', 'atlas-knb-textdomain'); ?>
                    </div>
                    <div class="md-form">
                        <input type="text" 
                        value="<?= esc_attr(ATLAS_Config::getCategoryRewrite()); ?>" 
                        name="<?= esc_attr(ATLAS_Config::getOptionsGroupSlug());?>[<?= esc_attr(ATLAS_Config::ATLAS_CATEGORY_REWRITE_KEY); ?>]"
                        class="form-control">
                        <label for="form1"><?php esc_html_e('Atlas category rewrite', 'atlas-knb-textdomain'); ?></label>
                    </div>
                    <div class="alert alert-info" role="alert">
                        <?php esc_html_e('Ex: https://yourwebsite.com/', 'atlas-knb-textdomain'); ?><?= ATLAS_Config::getCategoryRewrite(); ?>
                    </div>
                    <div class="alert alert-warning" role="alert">
                        <?php esc_html_e('NOTE! Once saved, if permalink changes don\'t work, go to WP Admin > Settings > Permalinks and click "Save changes"', 'atlas-knb-textdomain'); ?>.
                    </div>
                    <p><button type="submit" class="btn btn-primary btn-md float-right"><?php esc_html_e('Save', 'atlas-knb-textdomain'); ?></button></p>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col-md">
            <div class="skp-admin-panel">
                <div class="panel-title">
                    <?php esc_html_e('Gutenberg blocks', 'atlas-knb-textdomain'); ?>
                </div>
                <div class="skp-separator"></div>
                <div class="panel-content">
                    <?php
                    $hasGTBEditor = ATLAS_OptionUtil::getInstance()->getOption('show_gutenberg_editor', true);
                    ?>
                    <div class="custom-control custom-switch" style="margin-bottom: 20px;">
                        <input type="checkbox" class="custom-control-input" 
                        id="usegblocks"
                        value="<?= esc_attr($hasGTBEditor); ?>"
                        name="<?= esc_attr(ATLAS_Config::getOptionsGroupSlug());?>[show_gutenberg_editor]" 
                        <?= $hasGTBEditor === '1' ? "checked" : "" ?>
                        >
                        <label class="custom-control-label" for="usegblocks"><?php esc_html_e('Use gutenberg blocks', 'atlas-knb-textdomain'); ?></label>
                    </div>
                    <div class="alert alert-info" role="alert">
                        <?php esc_html_e('Use gutenberg blocks within Admin > Atlas articles.', 'atlas-knb-textdomain'); ?>
                    </div>
                    <p><button type="submit" class="btn btn-primary btn-md float-right"><?php esc_html_e('Save', 'atlas-knb-textdomain'); ?></button></p>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="skp-admin-panel">
                <div class="panel-title">
                    <?php esc_html_e('Font Awesome', 'atlas-knb-textdomain'); ?>
                </div>
                <div class="skp-separator"></div>
                <div class="panel-content">
                    <?php
                    $faEnabled = ATLAS_OptionUtil::getInstance()->getOption('enable_font_awesome', true);
                    ?>
                    <div class="custom-control custom-switch" style="margin-bottom: 20px;">
                        <input type="checkbox" class="custom-control-input" 
                        id="usefa"
                        value="<?= esc_attr($faEnabled ? $faEnabled : ''); ?>"
                        name="<?= esc_attr(ATLAS_Config::getOptionsGroupSlug());?>[enable_font_awesome]" 
                        <?= $faEnabled === '1' ? "checked" : "" ?>
                        >
                        <label class="custom-control-label" for="usefa"><?php esc_html_e('Enable Font Awesome', 'atlas-knb-textdomain'); ?></label>
                    </div>
                    <div class="alert alert-info" role="alert">
                        <?php esc_html_e('Enables Font Awesome within your website front-end. Font Awesome it\'s used for Atlas category icons. Find out more within the documentation.', 'atlas-knb-textdomain'); ?>
                    </div>
                    <p><button type="submit" class="btn btn-primary btn-md float-right"><?php esc_html_e('Save', 'atlas-knb-textdomain'); ?></button></p>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col md">
            <div class="skp-admin-panel">
                <div class="panel-title">
                    <?php esc_html_e('Contact options', 'atlas-knb-textdomain'); ?>
                </div>
                <div class="skp-separator"></div>
                <div class="panel-content">
                    <div class="md-form">
                        <input type="text"
                        value="<?= esc_attr(ATLAS_OptionUtil::getInstance()->getOption('linkedin')); ?>" 
                        name="<?= esc_attr(ATLAS_Config::getOptionsGroupSlug());?>[linkedin]"
                        class="form-control">
                        <label for="form1"><?php esc_html_e('LinkedIn URL', 'atlas-knb-textdomain'); ?></label>
                    </div>
                    <div class="md-form">
                        <input type="text"
                        value="<?= esc_attr(ATLAS_OptionUtil::getInstance()->getOption('facebook')); ?>" 
                        name="<?= esc_attr(ATLAS_Config::getOptionsGroupSlug());?>[facebook]"
                        class="form-control">
                        <label for="form1"><?php esc_html_e('Facebook URL', 'atlas-knb-textdomain'); ?></label>
                    </div>
                    <div class="md-form">
                        <input type="text"
                        value="<?= esc_attr(ATLAS_OptionUtil::getInstance()->getOption('twitter')); ?>" 
                        name="<?= esc_attr(ATLAS_Config::getOptionsGroupSlug());?>[twitter]"
                        class="form-control">
                        <label for="form1"><?php esc_html_e('Twitter URL', 'atlas-knb-textdomain'); ?></label>
                    </div>
                    <div class="md-form">
                        <input type="text"
                        value="<?= esc_attr(ATLAS_OptionUtil::getInstance()->getOption('contactUrl')); ?>" 
                        name="<?= esc_attr(ATLAS_Config::getOptionsGroupSlug());?>[contactUrl]"
                        class="form-control">
                        <label for="form1"><?php esc_html_e('Contact URL', 'atlas-knb-textdomain'); ?></label>
                    </div>
                    <p><button type="submit" class="btn btn-primary btn-md float-right"><?php esc_html_e('Save', 'atlas-knb-textdomain'); ?></button></p>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col md">
            <div class="skp-admin-panel">
                <div class="panel-title">
                    <?php esc_html_e('Article updated date', 'atlas-knb-textdomain'); ?>
                </div>
                <div class="skp-separator"></div>
                <div class="panel-content">
                    <?php
                    $hasGTBEditor = ATLAS_OptionUtil::getInstance()->getOption('showWPDate', true);
                    ?>
                    <div class="custom-control custom-switch" style="margin-bottom: 20px;">
                        <input type="checkbox" class="custom-control-input" 
                        id="showWPDate"
                        value="<?= esc_attr($hasGTBEditor); ?>"
                        name="<?= esc_attr(ATLAS_Config::getOptionsGroupSlug());?>[showWPDate]" 
                        <?= $hasGTBEditor === '1' ? "checked" : "" ?>
                        >
                        <label class="custom-control-label" for="showWPDate"><?php esc_html_e('Display WordPress date format', 'atlas-knb-textdomain'); ?></label>
                    </div>
                    <div class="alert alert-info" role="alert">
                        <?php esc_html_e('Displays "last update" WordPress date format for articles. Otherwise will display "time ago" ( Ex: Updated 20 hours ago ). Unfortunately "time ago" is only available in English.', 'atlas-knb-textdomain'); ?>
                    </div>
                    <p><button type="submit" class="btn btn-primary btn-md float-right"><?php esc_html_e('Save', 'atlas-knb-textdomain'); ?></button></p>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>