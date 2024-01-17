<?php

namespace WPFormsStripe\Migrations;

use WPForms\Migrations\Base;

/**
 * Class Migrations handles addon upgrade routines.
 *
 * @since 2.6.0
 */
class Migrations extends Base {

	/**
	 * WP option name to store the migration versions.
	 *
	 * @since 2.6.0
	 */
	const MIGRATED_OPTION_NAME = 'wpforms_stripe_versions';

	/**
	 * Current plugin version.
	 *
	 * @since 2.6.0
	 */
	const CURRENT_VERSION = WPFORMS_STRIPE_VERSION;

	/**
	 * Name of plugin used in log messages.
	 *
	 * @since 2.6.0
	 */
	const PLUGIN_NAME = 'WPForms Stripe Pro';

	/**
	 * Upgrade classes.
	 *
	 * @since 2.6.0
	 * @since 2.6.1 Registered the Upgrade261 class.
	 * @since 2.7.0 Renamed Upgrade250 to Upgrade270.
	 */
	const UPGRADE_CLASSES = [
		'Upgrade230',
		'Upgrade261',
		'Upgrade270',
		'Upgrade300',
	];
}
