<?php
/**
 * Injects the reading time label into post content and exposes a shortcode.
 *
 * @package Reading_Time_Estimator
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

class RTE_Output {

    public static function init(): void {
        add_filter( 'the_content', [ __CLASS__, 'inject_label' ] );
        add_shortcode( 'reading_time', [ __CLASS__, 'shortcode' ] );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue' ] );
    }

    /**
     * Injects the reading time badge into the_content based on settings.
     */
    public static function inject_label( string $content ): string {
        if ( ! is_singular() || is_admin() ) {
            return $content;
        }

        $options    = self::get_options();
        $post_types = (array) ( $options['post_types'] ?? ['post'] );

        if ( ! in_array( get_post_type(), $post_types, true ) ) {
            return $content;
        }

        $badge = self::build_badge( $content, $options );

        return 'before' === $options['position']
            ? $badge . $content
            : $content . $badge;
    }

    /**
     * [reading_time] shortcode — usable anywhere in content.
     */
    public static function shortcode(): string {
        $content = get_post_field( 'post_content', get_the_ID() );
        return self::build_badge( $content, self::get_options() );
    }

    /**
     * Build the badge HTML string.
     */
    private static function build_badge( string $content, array $options ): string {
        $wpm     = (int) ( $options['wpm'] ?? 200 );
        $minutes = RTE_Calculator::calculate( $content, $wpm );
        $label   = str_replace( '{time}', $minutes, $options['label'] ?? '{time} min read' );

        return sprintf(
            '<p class="rte-badge"><span class="rte-icon" aria-hidden="true">&#128366;</span> <span class="rte-label">%s</span></p>',
            esc_html( $label )
        );
    }

    public static function enqueue(): void {
        if ( ! is_singular() ) {
            return;
        }
        wp_enqueue_style( 'rte-front', RTE_PLUGIN_URL . 'assets/css/front.css', [], RTE_VERSION );
    }

    private static function get_options(): array {
        return wp_parse_args( get_option( RTE_OPTIONS_KEY, [] ), [
            'wpm'        => 200,
            'position'   => 'before',
            'label'      => '{time} min read',
            'post_types' => ['post'],
        ] );
    }
}
