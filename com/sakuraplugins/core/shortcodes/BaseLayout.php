<?php
if ( ! defined( 'ABSPATH' ) ) exit;
require_once(plugin_dir_path(__FILE__) . '../config.php');
require_once(plugin_dir_path(__FILE__) . '../utils/OptionUtil.php');

class ATLAS_BaseLayout {

    protected $layout;
	function __construct($layout) {
        $this->$layout = $layout;
    }

    protected function getCategoryIcon($termMeta) {
        $hasIconClass = isset($termMeta['atlas_awesome_css_id']) && isset($termMeta['atlas_awesome_css_id'][0]);
        $useIconClass = isset($termMeta['atlas_checkbox_field_id']);
        $hasColor = isset($termMeta['atlas_color_field_id']);

        $style = '';
        if ($hasColor) {
            $style = ' style="color: ' . esc_attr($termMeta['atlas_color_field_id'][0]) . ';"';
        }
        if ($hasIconClass && !$useIconClass) {
            return '<div class="icon"><i class="' . esc_attr($termMeta['atlas_awesome_css_id'][0]) . '"' . $style . '></i></div>';
        }
        $hasImage = isset($termMeta['atlas_image_field_id']) && isset($termMeta['atlas_image_field_id'][0]);
        if ($hasImage && $useIconClass) {
            $imageData = unserialize($termMeta['atlas_image_field_id'][0]);
            $image = wp_get_attachment_image_src($imageData['id'], ATLAS_Config::CAT_IMG_SIZE_KEY);
            return '<div class="icon"><img src="' . esc_url($image[0]) . '" alt="" /></div>';
        }
    }

    protected function getTaxPosts($termSlug) {
        $args = [
            'hide_empty'     => 1,
            'post_type'      => ATLAS_Config::getPostType(),
            'taxonomy'       => ATLAS_Config::getTopicsSlug(),
            'term'           => $termSlug,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        ];
        return get_posts($args);
    }

    protected function getCategoryAuthors($posts) {
        $authorsIds = [];
        foreach ($posts as $postEntry) {
            array_push($authorsIds, $postEntry->post_author);
        }
        $authorsIdsUnique = array_unique($authorsIds);
        $authors = [];
        foreach ($authorsIdsUnique as $authorId) {
            array_push($authors, [
                'displayName' => get_the_author_meta('display_name', $authorId),
                'avatar' => get_avatar_url(get_the_author_meta('user_email', $authorId)),
                'user_url' => get_the_author_meta('user_url', $authorId)
            ]);
        }
        return $authors;
    }

    protected function formatAuthorsDisplayNames($authors) {
        $out = esc_html(ATLAS_OptionUtil::getInstance()->getLocale('writtenByAuthor')) . ' ';
        for ($i = 0; $i < sizeof($authors); $i++) { 
            # code...
            $afterAuthor = ', ';
            if ($i === sizeof($authors) - 1 && sizeof($authors) !== 1) {
                $afterAuthor = '';
                $out .= ' ' . esc_html(ATLAS_OptionUtil::getInstance()->getLocale('writtenByAuthorAnd')) .' ';
            }
            $out .= '<span class="knb-author-name">' . esc_html($authors[$i]['displayName']) . $afterAuthor . '</span>';
        }
        return $out;
    }

    protected function openMainContainer() {
        ?><div class="knb-container"><?php
    }

    protected function closeMainContainer() {
        ?></div><?php
    }

    protected function getSocialLink($socialUrl, $socialNetKey) {
        return $socialUrl ? '<li><a href="' . esc_url($socialUrl) . '" target="_blnak"><span class="unf-social-' . esc_attr($socialNetKey) . '"></span></a></li>' : '';
    }

    protected function getSocialLinks() {
        $linkedin = ATLAS_OptionUtil::getInstance()->getSocialLink('linkedin');
        $facebook = ATLAS_OptionUtil::getInstance()->getSocialLink('facebook');
        $twitter = ATLAS_OptionUtil::getInstance()->getSocialLink('twitter');
        $hasSocialLinks = $linkedin || $facebook || $twitter;
        if (!$hasSocialLinks) {
            return '';
        }
        return '
            <div class="social-ui">
                <ul class="social-list">
                ' . $this->getSocialLink($linkedin, 'linkedin') . '
                ' . $this->getSocialLink($facebook, 'facebook') . '
                ' . $this->getSocialLink($twitter, 'twitter') . '
                </ul>
            </div>
        ';
    }

    protected function getContactLink() {
        $contactUrl = ATLAS_OptionUtil::getInstance()->getContactUrl();
        if (!$contactUrl) {
            return '';
        }
        return '<div class="contact-us-url"><a target="_blank" href="' . esc_url($contactUrl) . '">' . esc_html(ATLAS_OptionUtil::getInstance()->getLocale('contactUs')) . '</a></div>';
    }

    protected function getSearchContent() {
        $homepageNavUrl = ATLAS_OptionUtil::getInstance()->getNavHomepageUrl();
        return '
            <div class="search-ui">
                <div class="search-icon"><span class="unf-search"></span></div>
                <form action="' . $homepageNavUrl . '" method="get">
                    <input type="text" placeholder="' . esc_attr(ATLAS_OptionUtil::getInstance()->getLocale('searchPlaceholder')) . '" name="' . esc_attr(ATLAS_Config::getSearchQVarKey()) .'" />
                    <input style="display: none;" id="submit-form" type="submit" value="Submit">
                </form>
            </div>
        ';
    }
}