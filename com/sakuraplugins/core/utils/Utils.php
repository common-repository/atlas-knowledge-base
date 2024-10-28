<?php
if ( ! defined( 'ABSPATH' ) ) exit; 

class ATLAS_Utils {
    static function getObjectFromCollectionByKey($collection, $key, $value) {
        $obj = NULL;
        if (!is_array($collection)) {
            return $obj;
        }
        for ($i = 0; $i < sizeof($collection); $i++) {
            if ($collection[$i]->$key === $value) {
                $obj = $collection[$i];
                break;
            }
        }
        return $obj;
    }
}

?>