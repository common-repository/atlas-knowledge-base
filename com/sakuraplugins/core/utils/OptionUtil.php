<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
require_once(plugin_dir_path(__FILE__) . '../config.php');
require_once(plugin_dir_path(__FILE__) . '../utils/TimeUtils.php');

class ATLAS_OptionUtil {
    
    private static $instance = null;
    private $options;

    private function __construct() {
        $this->options = get_option(ATLAS_Config::getOptionsGroupSlug(), []);
    }

    public function getOption($optsKey, $default = null) {
        return (isset($this->options[$optsKey])) ? $this->options[$optsKey] : $default;
    }

    public function getLocale($key) {
        if (!isset(ATLAS_Config::LOCALES()[$key])) {
            return esc_html__('locale key not found', 'atlas-knb-textdomain');
        }
        if (!isset($this->options['locales']) && !isset($this->options['locales'][$key])) {
            return ATLAS_Config::LOCALES()[$key]['label'];
        }
        return isset($this->options['locales'][$key]) ? $this->options['locales'][$key] : ATLAS_Config::LOCALES()[$key]['label'];
    }

    public function getNavHomepageUrl() {
        $homePageId = $this->getOption('knbShortcodePage', '#');
        if ($homePageId === 'none') {
            return '#';
        }
        return get_permalink(intval($homePageId));
    }

    public function getArticleUpdateTime($postEntry) {
        $showTimeAgo = $this->getOption('showWPDate', false);
        return $showTimeAgo ? get_the_date('', $postEntry->ID) : ATLAS_TimeUtils::time_elapsed_string($postEntry->post_modified);
    }

    public function getSocialLink($key) {
        $socialLink = $this->getOption($key, false);
        return $socialLink !== '' ? $socialLink : false;
    }

    public function getContactUrl() {
        $contactLink = $this->getOption('contactUrl', false);
        return $contactLink !== '' ? $contactLink : false;
    }

    public function getPostVisits($postId) {
        $visits = get_post_meta($postId, 'visits', true);
        if (!$visits) {
            return 0;
        }
        return intval($visits);
    }

    public function incrementPostVisits($postId) {
        $currentVisits = $this->getPostVisits($postId);
        $newVisits = $currentVisits + 1;
        update_post_meta($postId, 'visits', $newVisits);
    }

    public function getPostRating($postId, $ratingKey) {
        $ratingNo = get_post_meta($postId, $ratingKey, true);
        if (!$ratingNo) {
            return 0;
        }
        return intval($ratingNo);
    }

    public function incrementPostRating($postId, $ratingKey) {
        $currentRating = $this->getPostRating($postId, $ratingKey);
        $newRating = $currentRating + 1;
        update_post_meta($postId, $ratingKey, $newRating);
    }

    public function decrementPostRating($postId, $ratingKey) {
        $currentRating = $this->getPostRating($postId, $ratingKey);
        $currentRating = $currentRating > 0 ? $currentRating - 1 : 0;
        update_post_meta($postId, $ratingKey, $currentRating);
    }

    public function getLayout() {
        return $this->getOption('layout', ATLAS_Config::getDefaultLayout());
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new ATLAS_OptionUtil();
        }
        return self::$instance;
    }
}
?>