<?php

namespace WPFormsStripe;

use WPForms\Integrations\Stripe\Admin\Connect;
use WPForms\Integrations\Stripe\Api\ApiInterface;
use WPForms\Integrations\Stripe\Frontend;
use WPForms_Updater;
use WPFormsStripe\Migrations\Migrations;

/**
 * WPForms Stripe loader class.
 *
 * @since 2.0.0
 */
final class Loader {

	/**
	 * Payment API.
	 *
	 * @since 2.3.0
	 *
	 * @var ApiInterface
	 */
	public $api;

	/**
	 * Stripe Connect.
	 *
	 * @since 2.3.0
	 *
	 * @var Connect
	 */
	public $connect;

	/**
	 * Stripe processing instance.
	 *
	 * @since 1.0.0
	 *
	 * @var Process
	 */
	public $process;

	/**
	 * URL to a plugin directory. Used for assets.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $url = '';

	/**
	 * Path to a plugin directory. Used for loading Stripe PHP library.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $path = '';

	/**
	 * Initiate main plugin instance.
	 *
	 * @since 2.0.0
	 *
	 * @return Loader
	 */
	public static function get_instance() {

		static $instance;

		if ( ! $instance ) {
			$instance = new self();

			$instance->init();
		}

		return $instance;
	}

	/**
	 * All the actual plugin loading is done here.
	 *
	 * @since 2.0.0
	 *
	 * @return Loader
	 */
	public function init() {

		$this->url  = WPFORMS_STRIPE_URL;
		$this->path = WPFORMS_STRIPE_PATH;

		( new Migrations() )->init();

		$this->api = Helpers::get_api_class()->init();

		if ( wpforms_is_admin_page( 'builder' ) ) {
			new Admin\StripePayment();
		}

		if ( wpforms_is_admin_page( 'builder' ) || $this->is_new_field_ajax() ) {
			new Admin\Builder();
		}

		if ( wpforms_is_admin_page( 'settings', 'payments' ) ) {
			( new Admin\Settings() )->init();

			// Initialize class for backward compatibility.
			$this->connect = new Connect();
		}

		if ( is_admin() ) {
			new Admin\Notices();
		}

		( new Frontend() )->init( $this->api );

		$this->process = new Process();

		$this->hooks();

		return $this;
	}

	/**
	 * Add hooks.
	 *
	 * @since 3.1.0
	 */
	private function hooks() {

		add_action( 'wpforms_updater', [ $this, 'updater' ] );
	}

	/**
	 * Check if the new field is being added via AJAX call.
	 *
	 * @since 2.3.0
	 */
	protected function is_new_field_ajax() {

		if ( ! \defined( 'DOING_AJAX' ) || ! \DOING_AJAX ) {
			return false;
		}

		if ( ! isset( $_POST['nonce'] ) || ! \wp_verify_nonce( \sanitize_key( $_POST['nonce'] ), 'wpforms-builder' ) ) {
			return false;
		}

		if ( empty( $_POST['action'] ) ) {
			return false;
		}

		$action = 'wpforms_new_field_' . $this->api->get_config( 'field_slug' );

		if ( $action !== $_POST['action'] ) {
			return false;
		}

		return true;
	}

	/**
	 * Load the plugin updater.
	 *
	 * @since 2.5.0
	 *
	 * @param string $key License key.
	 */
	public function updater( $key ) {

		new WPForms_Updater(
			[
				'plugin_name' => 'WPForms Stripe',
				'plugin_slug' => 'wpforms-stripe',
				'plugin_path' => plugin_basename( WPFORMS_STRIPE_FILE ),
				'plugin_url'  => trailingslashit( WPFORMS_STRIPE_URL ),
				'remote_url'  => WPFORMS_UPDATER_API,
				'version'     => WPFORMS_STRIPE_VERSION,
				'key'         => $key,
			]
		);
	}
}
