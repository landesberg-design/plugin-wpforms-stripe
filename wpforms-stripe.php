<?php
/**
 * Plugin Name:       WPForms Stripe Pro
 * Plugin URI:        https://wpforms.com
 * Description:       Stripe integration with WPForms.
 * Requires at least: 5.5
 * Requires PHP:      7.0
 * Author:            WPForms
 * Author URI:        https://wpforms.com
 * Version:           3.4.0
 * Text Domain:       wpforms-stripe
 * Domain Path:       languages
 *
 * WPForms is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WPForms is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WPForms. If not, see <https://www.gnu.org/licenses/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WPFormsStripe\Install;
use WPFormsStripe\Loader;

/**
 * Plugin version.
 *
 * @since 1.0.0
 */
const WPFORMS_STRIPE_VERSION = '3.4.0';

/**
 * Plugin file.
 *
 * @since 1.0.0
 */
const WPFORMS_STRIPE_FILE = __FILE__;

/**
 * Plugin path.
 *
 * @since 1.0.0
 */
define( 'WPFORMS_STRIPE_PATH', plugin_dir_path( WPFORMS_STRIPE_FILE ) );

/**
 * Plugin URL.
 *
 * @since 1.0.0
 */
define( 'WPFORMS_STRIPE_URL', plugin_dir_url( WPFORMS_STRIPE_FILE ) );

/**
 * Check addon requirements.
 *
 * @since 2.5.0
 * @since 3.1.0 Uses requirements feature.
 */
function wpforms_stripe_load() {

	$requirements = [
		'file'    => WPFORMS_STRIPE_FILE,
		'wpforms' => '1.8.8',
	];

	if ( ! function_exists( 'wpforms_requirements' ) || ! wpforms_requirements( $requirements ) ) {
		return;
	}

	wpforms_stripe();
}

add_action( 'wpforms_loaded', 'wpforms_stripe_load' );

/**
 * Get the instance of the addon main class.
 *
 * @since 2.5.0
 *
 * @return Loader
 */
function wpforms_stripe() {

	return Loader::get_instance();
}

require_once WPFORMS_STRIPE_PATH . 'vendor/autoload.php';

// Load installation things immediately for a reason how activation hook works.
new Install();
