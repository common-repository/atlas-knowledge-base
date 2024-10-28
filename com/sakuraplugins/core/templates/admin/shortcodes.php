<?php
require_once(plugin_dir_path(__FILE__) . '../../config.php');
require_once(plugin_dir_path(__FILE__) . '../../utils/OptionUtil.php');
?>
<div class="tab-pane fade show skp-tab-inner-content" id="shortcodes" role="tabpanel" aria-labelledby="home-tab">
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="skp-admin-panel">
                <div class="panel-title">
                    <?php esc_html_e('Available shortcodes.', 'atlas-knb-textdomain'); ?>
                </div>
                <div class="skp-separator"></div>
                <div class="panel-content">
                    <p style="font-size: 17px;"><b>[atlas_knb]</b></p>
                    <div class="alert alert-info" role="alert">
                        <?php esc_html_e('Displays Atlas content.', 'atlas-knb-textdomain'); ?>
                    </div>
                    <div class="alert alert-warning" role="alert">
                        <b><?php esc_html_e('NOTE!', 'atlas-knb-textdomain'); ?></b> <?php esc_html_e('Select below the page where the shortcode it\'s placed. This is needed for both the navigation and search results.', 'atlas-knb-textdomain'); ?>
                    </div>
                    <?php
                    $pages = get_pages(array('post_type' => 'page'));
                    $selectedPage = ATLAS_OptionUtil::getInstance()->getOption('knbShortcodePage', false);
                    ?>
                    <select style="max-width: 250px;" class="browser-default custom-select" name="<?= ATLAS_Config::getOptionsGroupSlug();?>[knbShortcodePage]">
                        <option value="none" <?= $selectedPage ? '' : 'selected' ?>><?php esc_html_e('Select page ...', 'atlas-knb-textdomain'); ?></option>
                        <?php foreach ($pages as $page): ?>
                            <option value="<?= esc_attr($page->ID); ?>" <?= intval($selectedPage) === $page->ID ? 'selected' : '' ?>><?= esc_html($page->post_title); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p><button type="submit" class="btn btn-primary btn-md float-right"><?php esc_html_e('Save', 'atlas-knb-textdomain'); ?></button></p>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="skp-admin-panel">
                <div class="panel-title">
                    <?php esc_html_e('Layouts', 'atlas-knb-textdomain'); ?>
                </div>
                <div class="skp-separator"></div>
                <div class="panel-content">
                    <div class="alert alert-info" role="alert">
                        <?php esc_html_e('How to display your knowledge base within the front-end. Note! that functionality might be different from layout to layout.', 'atlas-knb-textdomain'); ?>
                    </div>
                    <?php
                    $layouts = ATLAS_Config::getAvailableLayoutsWithLabels();
                    $currentLayout = ATLAS_OptionUtil::getInstance()->getLayout();
                    ?>
                    <select style="max-width: 250px;" class="browser-default custom-select" name="<?= ATLAS_Config::getOptionsGroupSlug();?>[layout]">
                        <?php foreach ($layouts as $key => $layout): ?>
                            <option value="<?= esc_attr($key); ?>" <?= $currentLayout === $key ? 'selected' : '' ?>><?= $layout['label']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p><button type="submit" class="btn btn-primary btn-md float-right"><?php esc_html_e('Save', 'atlas-knb-textdomain'); ?></button></p>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>
