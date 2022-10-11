<?php

namespace WPFormsStripe\Migrations;

use WPForms\Migrations\UpgradeBase;
use WPFormsStripe\Helpers;

/**
 * Class Stripe addon v2.3.0 upgrade.
 *
 * @since 2.6.0
 *
 * @noinspection PhpUnused
 */
class Upgrade230 extends UpgradeBase {

	/**
	 * Run upgrade.
	 *
	 * @since 2.6.0
	 *
	 * @return bool|null Upgrade result:
	 *                   true  - the upgrade completed successfully,
	 *                   false - in the case of failure,
	 *                   null  - upgrade started but not yet finished (background task).
	 */
	public function run() {

		$legacy_payment_forms = Helpers::get_forms_by_payment_collection_type();
		$wpforms_settings     = get_option( 'wpforms_settings', [] );

		if ( ! empty( $legacy_payment_forms ) || Helpers::has_stripe_keys() ) {
			$wpforms_settings['stripe-api-version'] = '2';

			update_option(
				'wpforms_stripe_v230_upgrade',
				[ 'upgraded' => time() ]
			);
		} else {
			$wpforms_settings['stripe-api-version'] = '3';
		}

		update_option( 'wpforms_settings', $wpforms_settings );

		return true;
	}
}
