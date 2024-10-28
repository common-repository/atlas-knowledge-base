<?php
if ( ! defined( 'ABSPATH' ) ) exit;
require_once(plugin_dir_path(__FILE__) . '../utils/SessionUtils.php');
require_once(plugin_dir_path(__FILE__) . '../utils/OptionUtil.php');
class ATLAS_RatingService {

    const VALID_RATINGS_VALUES = [
        'disappointed', 'neutral', 'happy'
    ];

    static function setRating() {
        if (!isset($_POST['postId']) || !isset($_POST['rating'])) {
            return self::respondError();
        }

        if (!in_array($_POST['rating'], self::VALID_RATINGS_VALUES)) {
            return self::respondError();
        }

        $existingPostRating = ATLAS_SessionUtils::getInstance()->getValue($_POST['postId']);
        if ($existingPostRating && $existingPostRating !== $_POST['rating']) {
            ATLAS_SessionUtils::getInstance()->setValue($_POST['postId'], $_POST['rating']);
            ATLAS_OptionUtil::getInstance()->incrementPostRating($_POST['postId'], $_POST['rating']);
            ATLAS_OptionUtil::getInstance()->decrementPostRating($_POST['postId'], $existingPostRating);
        }

        if (!$existingPostRating) {
            ATLAS_SessionUtils::getInstance()->setValue($_POST['postId'], $_POST['rating']);
            ATLAS_OptionUtil::getInstance()->incrementPostRating($_POST['postId'], $_POST['rating']);
        }

        $postId = intval($_POST['postId']);

        echo json_encode(array(
            'status' => 'OK',
            'rating' => $_POST['rating'],
        ));
        wp_die();
    }

    static function getRating() {
        if (!isset($_POST['postId'])) {
            return self::respondError();
        }

        $sessionPostRating = ATLAS_SessionUtils::getInstance()->getValue($_POST['postId']);

        echo json_encode(array(
            'status' => 'OK',
            'rating' => $sessionPostRating,
        ));
        wp_die();
    }

    static function respondError($msg = 'Something went wrong') {
        echo json_encode(array(
            'status' => 'OK',
            'message' => $msg,
        ));
        wp_die();
    }
}
?>