<?php
/**
 * Plugin Name:       Reading Time Estimator
 * Plugin URI:        https://github.com/jdyach/reading-time-estimator
 * Description:       Automatically adds a "X min read" label to posts. Configurable words-per-minute, label format, and display position via a settings page.
 * Version:           1.0.0
 * Author:            Jacqui Dyachenko
 * Author URI:        https://jacquid.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       reading-time-estimator
 * Requires at least: 6.0
 * Requires PHP:      8.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'RTE_VERSION',    '1.0.0' );
define( 'RTE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'RTE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'RTE_OPTIONS_KEY', 'rte_settings' );

require_once RTE_PLUGIN_DIR . 'includes/class-rte-calculator.php';
require_once RTE_PLUGIN_DIR . 'includes/class-rte-output.php';
require_once RTE_PLUGIN_DIR . 'includes/class-rte-settings.php';

function rte_init(): void {
    RTE_Output::init();
    RTE_Settings::init();
}
add_action( 'plugins_loaded', 'rte_init' );

register_activation_hook( __FILE__, function (): void {
    if ( false === get_option( RTE_OPTIONS_KEY ) ) {
        add_option( RTE_OPTIONS_KEY, [
            'wpm'       => 200,
            'position'  => 'before',
            'label'     => '{time} min read',
            'post_types'=> ['post'],
        ] );
    }
} );
