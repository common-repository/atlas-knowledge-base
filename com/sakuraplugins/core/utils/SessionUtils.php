<?php
if ( ! defined( 'ABSPATH' ) ) exit; 

class ATLAS_SessionUtils {

    private static $instance = null;
    const RATINGS_KEY = 'ATLAS_RATINGS_KEY';
    const LATEST_VIEVED = 'ATLAS_LATEST_VIEVED';

    public function sessionStart() {
        if(!session_id()) {
            session_start();
        }
        if (!isset($_SESSION[self::RATINGS_KEY])) {
            $_SESSION[self::RATINGS_KEY] = array();
        }
        if (!isset($_SESSION[self::LATEST_VIEVED])) {
            $_SESSION[self::LATEST_VIEVED] = array();
        }
    }

    public function setValue($key, $val) {
        $this->sessionStart();
        $_SESSION[self::RATINGS_KEY][$key] = $val;
    }

    public function unsetValue($key) {
        try {
            $this->sessionStart();
            unset($_SESSION[self::RATINGS_KEY][$key]);
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getValue($key) {
        try {
            $this->sessionStart();
            return $_SESSION[self::RATINGS_KEY][$key];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function addViewedArticle($articleId) {
        try {
            $this->sessionStart();
            if (in_array($articleId, $_SESSION[self::LATEST_VIEVED])) {
                return;
            }
            array_unshift($_SESSION[self::LATEST_VIEVED], $articleId);
            if (sizeof($_SESSION[self::LATEST_VIEVED]) === 6) {
                array_pop($_SESSION[self::LATEST_VIEVED]);
            }
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getViewedArticles() {
        try {
            $this->sessionStart();
            return $_SESSION[self::LATEST_VIEVED];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new ATLAS_SessionUtils();
        }

        return self::$instance;
    }
}

?>