<?php

namespace WPFormsStripe\Migrations;

use WPForms\Migrations\UpgradeBase;
use WPForms\Tasks\Actions\Migration175Task;

/**
 * Class Stripe addon v2.7.0 upgrade.
 *
 * @since 2.6.0
 * @since 2.7.0 Renamed from Upgrade250 to Upgrade270.
 *
 * @noinspection PhpUnused
 */
class Upgrade270 extends UpgradeBase {

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

		return $this->run_async( Migration175Task::class );
	}
}
