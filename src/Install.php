<?php

namespace WPFormsStripe;

use WP_Site;
use WPForms\Integrations\Stripe\Admin\Connect as StripeConnect;

/**
 * Plugin install / uninstall actions.
 *
 * @since 2.3.0
 */
class Install {

	/**
	 * Update account option name.
	 *
	 * @since 3.0.0
	 */
	const UPDATED_ACCOUNT_OPTION_NAME = 'wpforms_stripe_account_updated';

	/**
	 * Constructor.
	 *
	 * @since 2.3.0
	 */
	public function __construct() {

		$this->init();
	}

	/**
	 * Initialize.
	 *
	 * @since 2.3.0
	 */
	public function init() {

		$this->hooks();
	}

	/**
	 * Register hooks.
	 *
	 * @since 3.0.0
	 */
	private function hooks() {

		// When activated, trigger install method.
		register_activation_hook( WPFORMS_STRIPE_FILE, [ $this, 'install' ] );

		// When deactivated, trigger uninstall method.
		register_deactivation_hook( WPFORMS_STRIPE_FILE, [ $this, 'uninstall' ] );

		// Watch for new multisite blogs.
		add_action( 'wp_initialize_site', [ $this, 'new_multisite_blog' ], 10, 2 );

		// Update connected account meta.
		add_action( 'wpforms_loaded', [ $this, 'update_account_meta' ] );
	}

	/**
	 * Let's get the party started.
	 *
	 * @since 2.3.0
	 *
	 * @param bool $network_wide Is plugin activated network wide.
	 */
	public function install( $network_wide = false ) {

		// Check if we are on multisite and network activating.
		if ( is_multisite() && $network_wide ) {

			$sites = get_sites();

			foreach ( $sites as $site ) {
				switch_to_blog( $site->blog_id );
				$this->run();
				restore_current_blog();
			}
		} else {
			$this->run();
		}
	}

	/**
	 * Run install actions.
	 *
	 * @since 2.3.0
	 */
	public function run() {

		// Set current version, to be referenced in future updates.
		update_option( 'wpforms_stripe_version', WPFORMS_STRIPE_VERSION );
	}

	/**
	 * When a new site is created in multisite, see if we are network activated,
	 * and if so run the installer.
	 *
	 * @since 2.3.0
	 * @since 3.1.0 Added $new_site and $args parameters and removed $blog_id, $user_id, $domain, $path,
	 *        $site_id, $meta parameters.
	 *
	 * @param WP_Site $new_site New site object.
	 * @param array   $args     Arguments for the initialization.
	 *
	 * @noinspection PhpUnusedParameterInspection
	 */
	public function new_multisite_blog( $new_site, $args ) {

		if ( is_plugin_active_for_network( plugin_basename( WPFORMS_STRIPE_FILE ) ) ) {
			switch_to_blog( $new_site->blog_id );
			$this->run();
			restore_current_blog();
		}
	}

	/**
	 * Update account meta.
	 *
	 * @since 3.0.0
	 */
	public function update_account_meta() {

		// Bail out if a connected account meta has already updated.
		if ( get_option( self::UPDATED_ACCOUNT_OPTION_NAME ) ) {
			return;
		}

		// Bail out if PHP class with logic doesn't exist.
		if ( ! class_exists( StripeConnect::class ) ) {
			return;
		}

		// Update a connected account meta and store a flag option.
		( new StripeConnect() )->update_account_meta();
		add_option( self::UPDATED_ACCOUNT_OPTION_NAME, true );
	}

	/**
	 * Run uninstall actions.
	 *
	 * @since 3.0.0
	 */
	public function uninstall() {

		delete_option( self::UPDATED_ACCOUNT_OPTION_NAME );
	}
}
