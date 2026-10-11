<?php
/**
 * Settings page under Settings > Reading Time Estimator.
 *
 * @package Reading_Time_Estimator
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

class RTE_Settings {

    const PAGE_SLUG    = 'reading-time-estimator';
    const OPTION_GROUP = 'rte_settings_group';

    public static function init(): void {
        add_action( 'admin_menu', [ __CLASS__, 'register_page' ] );
        add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
    }

    public static function register_page(): void {
        add_options_page(
            __( 'Reading Time Estimator', 'reading-time-estimator' ),
            __( 'Reading Time', 'reading-time-estimator' ),
            'manage_options',
            self::PAGE_SLUG,
            [ __CLASS__, 'render_page' ]
        );
    }

    public static function register_settings(): void {
        register_setting( self::OPTION_GROUP, RTE_OPTIONS_KEY, [
            'sanitize_callback' => [ __CLASS__, 'sanitize' ],
        ] );
    }

    public static function sanitize( mixed $input ): array {
        $options = get_option( RTE_OPTIONS_KEY, [] );
        $wpm     = isset( $input['wpm'] ) ? absint( $input['wpm'] ) : 200;
        $options['wpm']      = max( 50, min( 1000, $wpm ) );
        $options['position'] = in_array( $input['position'] ?? '', [ 'before', 'after' ], true )
            ? $input['position'] : 'before';
        $options['label']    = sanitize_text_field( $input['label'] ?? '{time} min read' );
        $options['post_types'] = array_map( 'sanitize_key', (array) ( $input['post_types'] ?? ['post'] ) );
        return $options;
    }

    public static function render_page(): void {
        if ( ! current_user_can( 'manage_options' ) ) { wp_die( esc_html__( 'Permission denied.', 'reading-time-estimator' ) ); }
        $options    = wp_parse_args( get_option( RTE_OPTIONS_KEY, [] ), [ 'wpm' => 200, 'position' => 'before', 'label' => '{time} min read', 'post_types' => ['post'] ] );
        $all_types  = get_post_types( [ 'public' => true ], 'objects' );
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Reading Time Estimator', 'reading-time-estimator' ); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields( self::OPTION_GROUP ); ?>
                <table class="form-table">
                    <tr>
                        <th><?php esc_html_e( 'Words per minute', 'reading-time-estimator' ); ?></th>
                        <td><input type="number" name="<?php echo esc_attr( RTE_OPTIONS_KEY ); ?>[wpm]" value="<?php echo esc_attr( $options['wpm'] ); ?>" min="50" max="1000" class="small-text" />
                        <p class="description"><?php esc_html_e( 'Average adult reading speed is 200–250 WPM.', 'reading-time-estimator' ); ?></p></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Label format', 'reading-time-estimator' ); ?></th>
                        <td><input type="text" name="<?php echo esc_attr( RTE_OPTIONS_KEY ); ?>[label]" value="<?php echo esc_attr( $options['label'] ); ?>" class="regular-text" />
                        <p class="description"><?php esc_html_e( 'Use {time} as the placeholder for the minute count.', 'reading-time-estimator' ); ?></p></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Position', 'reading-time-estimator' ); ?></th>
                        <td>
                            <label><input type="radio" name="<?php echo esc_attr( RTE_OPTIONS_KEY ); ?>[position]" value="before" <?php checked( $options['position'], 'before' ); ?> /> <?php esc_html_e( 'Before content', 'reading-time-estimator' ); ?></label><br>
                            <label><input type="radio" name="<?php echo esc_attr( RTE_OPTIONS_KEY ); ?>[position]" value="after"  <?php checked( $options['position'], 'after' );  ?> /> <?php esc_html_e( 'After content',  'reading-time-estimator' ); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Post types', 'reading-time-estimator' ); ?></th>
                        <td><?php foreach ( $all_types as $type ) : ?>
                            <label style="display:block;margin-bottom:4px">
                                <input type="checkbox" name="<?php echo esc_attr( RTE_OPTIONS_KEY ); ?>[post_types][]" value="<?php echo esc_attr( $type->name ); ?>" <?php checked( in_array( $type->name, (array) $options['post_types'], true ) ); ?> />
                                <?php echo esc_html( $type->label ); ?>
                            </label>
                        <?php endforeach; ?></td>
                    </tr>
                </table>
                <?php submit_button( __( 'Save Settings', 'reading-time-estimator' ) ); ?>
            </form>
        </div>
        <?php
    }
}
