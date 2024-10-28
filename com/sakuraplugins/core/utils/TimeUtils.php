<?php
class ATLAS_TimeUtils {
    static function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => esc_html__('year', 'atlas-knb-textdomain'),
            'm' => esc_html__('month', 'atlas-knb-textdomain'),
            'w' => esc_html__('week', 'atlas-knb-textdomain'),
            'd' => esc_html__('day', 'atlas-knb-textdomain'),
            'h' => esc_html__('hour', 'atlas-knb-textdomain'),
            'i' => esc_html__('minute', 'atlas-knb-textdomain'),
            's' => esc_html__('second', 'atlas-knb-textdomain'),
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . esc_html__(' ago', 'atlas-knb-textdomain') : esc_html__('just now', 'atlas-knb-textdomain');
    }
}
?>