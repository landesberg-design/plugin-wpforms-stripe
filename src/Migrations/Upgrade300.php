<?php

namespace WPFormsStripe\Migrations;

use WPForms\Integrations\Stripe\Admin\Connect;

/**
 * Class Stripe addon v3.0.0 upgrade.
 *
 * @since 3.0.0
 *
 * @noinspection PhpUnused
 */
class Upgrade300 {

	/**
	 * Run upgrade.
	 *
	 * @since 3.0.0
	 *
	 * @return bool|null Upgrade result:
	 *                   true  - the upgrade completed successfully,
	 *                   false - in the case of failure,
	 *                   null  - upgrade started but not yet finished (background task).
	 */
	public function run() {

		( new Connect() )->update_account_meta();

		return true;
	}
}
