<?php

namespace WPFormsStripe\Migrations;

use WPFormsStripe\Helpers;

/**
 * Class Stripe addon v2.6.1 upgrade.
 *
 * @since 2.6.1
 *
 * @noinspection PhpUnused
 */
class Upgrade261 {

	/**
	 * Run upgrade.
	 *
	 * @since 2.6.1
	 *
	 * @return bool|null Upgrade result:
	 *                   true  - the upgrade completed successfully,
	 *                   false - in the case of failure,
	 *                   null  - upgrade started but not yet finished (background task).
	 */
	public function run() {

		$wpforms_settings   = (array) get_option( 'wpforms_settings', [] );
		$is_api_v2          = isset( $wpforms_settings['stripe-api-version'] ) && absint( $wpforms_settings['stripe-api-version'] ) === 2;
		$has_elements_forms = ! empty( Helpers::get_forms_by_payment_collection_type( 'elements' ) );

		// If there are new payment forms (based on Stripe CC field) and the used API version is 2 (legacy),
		// most likely it was affected by v230 migration during upgrading to v2.6.0,
		// so we switch API version to 3.
		if ( $has_elements_forms && $is_api_v2 ) {
			$v230_upgrade = (array) get_option( 'wpforms_stripe_v230_upgrade', [] );

			$wpforms_settings['stripe-api-version'] = '3';
			$v230_upgrade['dismissed']              = true; // hide v2.3.0 upgrade notice (SCA/Stripe connect).

			update_option( 'wpforms_settings', $wpforms_settings );
			update_option( 'wpforms_stripe_v230_upgrade', $v230_upgrade );
		}

		return true;
	}
}
