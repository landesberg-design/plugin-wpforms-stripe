<?php

namespace WPFormsStripe\Admin;

use WPForms\Admin\Notice;

/**
 * Admin notices.
 *
 * @since 2.3.0
 */
class Notices extends \WPForms\Integrations\Stripe\Admin\Notices {

	/**
	 * Constructor.
	 *
	 * @since 2.3.0
	 */
	public function __construct() { // phpcs:ignore WPForms.PHP.HooksMethod.InvalidPlaceForAddingHooks

		add_action( 'admin_init', [ $this, 'init' ] );
		add_action( 'wpforms_settings_init', [ $this, 'deprecated_api' ] );
	}

	/**
	 * Initialize.
	 *
	 * @since 2.3.0
	 */
	public function init() { // phpcs:ignore WPForms.PHP.HooksMethod.InvalidPlaceForAddingHooks

		add_action( 'admin_notices', [ $this, 'v230_upgrade' ] );
		add_action( 'wp_ajax_wpforms_stripe_v230_dismiss', [ $this, 'v230_dismiss' ] );
	}

	/**
	 * Upgrade for v2.3.0.
	 *
	 * @since 2.3.0
	 */
	public function v230_upgrade() {

		$v230_upgrade = get_option( 'wpforms_stripe_v230_upgrade', false );

		if ( ! $v230_upgrade || ! empty( $v230_upgrade['dismissed'] ) ) {
			return;
		}

		$payment_connection_type = absint( wpforms_setting( 'stripe-api-version' ) );

		if ( $payment_connection_type !== 2 ) {
			return;
		}
		?>
		<div class="notice notice-info is-dismissible wpforms-stripe-v230">
			<p>
			<?php
			printf(
				wp_kses( /* translators: %s - WPForms.com URL to a related doc. */
					__( 'The WPForms Stripe addon now supports improved security and Strong Customer Authentication (SCA/PSD2) with the new Stripe Credit Card field. <a href="%s" target="_blank" rel="noopener noreferrer">Learn how to update your forms</a>.' ),
					[
						'a' => [
							'href'   => [],
							'target' => [],
							'rel'    => [],
						],
					]
				),
				'https://wpforms.com/docs/how-to-update-to-the-stripe-credit-card-field'
			);
			?>
			<button type="button" class="notice-dismiss"></button>
			</p>
		</div>
		<script type="text/javascript">
			jQuery( function( $ ) {
				$( document ).on( 'click', '.notice.wpforms-stripe-v230 .notice-dismiss', function ( event ) {
					event.preventDefault();
					$.post( ajaxurl, {
						action: 'wpforms_stripe_v230_dismiss'
					} );
					$( '.notice.wpforms-stripe-v230' ).remove();
				} );
			} );
		</script>
		<?php
	}

	/**
	 * Dismiss v2.3.0 upgrade notice.
	 *
	 * @since 2.3.0
	 */
	public function v230_dismiss() {

		if ( ! wpforms_current_user_can() ) {
			wp_send_json_error();
		}

		$v230_upgrade              = (array) get_option( 'wpforms_stripe_v230_upgrade', [] );
		$v230_upgrade['dismissed'] = true;

		update_option( 'wpforms_stripe_v230_upgrade', $v230_upgrade );

		wp_send_json_success();
	}

	/**
	 * Notice for deprecated API version.
	 *
	 * @since 2.9.0
	 */
	public function deprecated_api() {

		$payment_collection_type = absint( wpforms_setting( 'stripe-api-version' ) );

		if ( $payment_collection_type !== 2 ) {
			return;
		}

		$message = sprintf(
			wp_kses( /* translators: %s - Payments settings page URL. */
				__( 'The WPForms Stripe integration is currently using a deprecated payment collection type that is no longer supported and will be discontinued beginning January 1st, 2024.<br> Please <a href="%s">update your payment collection type</a> to continue processing payments successfully.' ),
				[
					'br' => [],
					'a'  => [
						'href' => [],
					],
				]
			),
			esc_url( admin_url( 'admin.php?page=wpforms-settings&view=payments#wpforms-setting-row-stripe-api-version' ) )
		);

		Notice::warning( $message );
	}
}
