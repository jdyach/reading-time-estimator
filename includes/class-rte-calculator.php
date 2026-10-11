<?php
/**
 * Calculates reading time from a content string.
 *
 * @package Reading_Time_Estimator
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

class RTE_Calculator {

    /**
     * Returns the reading time in minutes (minimum 1).
     *
     * @param string $content Raw post content (HTML allowed; stripped internally).
     * @param int    $wpm     Words per minute.
     * @return int
     */
    public static function calculate( string $content, int $wpm = 200 ): int {
        $text       = wp_strip_all_tags( $content );
        $word_count = str_word_count( $text );
        $minutes    = (int) ceil( $word_count / max( 1, $wpm ) );
        return max( 1, $minutes );
    }
}
