<?php

namespace WPFormsStripe\Admin;

use WPFormsStripe\Helpers;

/**
 * Stripe addon settings.
 *
 * @since 2.0.0
 */
class Settings extends \WPForms\Integrations\Stripe\Admin\Settings {

	/**
	 * Current payment forms.
	 *
	 * @since 2.3.0
	 *
	 * @var array
	 */
	private $payment_forms = [];

	/**
	 * Initialize.
	 *
	 * @since 2.0.0
	 */
	public function init() {

		parent::init();

		$this->get_payment_forms();
	}

	/**
	 * Get current payment forms.
	 *
	 * @since 2.3.0
	 */
	protected function get_payment_forms() {

		$this->payment_forms = [
			'legacy'   => Helpers::get_forms_by_payment_collection_type( 'legacy' ),
			'elements' => Helpers::get_forms_by_payment_collection_type( 'elements' ),
		];
	}

	/**
	 * Enqueue Settings scripts and styles.
	 *
	 * @since 2.3.0
	 */
	public function enqueue_assets() {

		parent::enqueue_assets();

		$min = wpforms_get_min_suffix();

		wp_enqueue_script(
			'wpforms-admin-settings-legacy-stripe',
			plugin_dir_url( WPFORMS_STRIPE_FILE ) . "assets/js/admin-settings-legacy-stripe{$min}.js",
			[ 'jquery' ],
			WPFORMS_STRIPE_VERSION,
			true
		);

		$admin_settings_stripe_l10n = [
			'has_payment_forms_legacy'                  => ! empty( $this->payment_forms['legacy'] ),
			'has_payment_forms_elements'                => ! empty( $this->payment_forms['elements'] ),
			'payment_collection_type_modal_elements_ok' => esc_html__( 'Yes, use the Stripe Credit Card field', 'wpforms-stripe' ),
			'mode_update_cancel'                        => esc_html__( 'No, continue with a current mode', 'wpforms-stripe' ),
		];

		$admin_settings_stripe_l10n['payment_collection_type_modal_elements'] = sprintf(
			wp_kses( /* translators: %s - WPForms.com Stripe documentation article URL. */
				__(
					'<p>To use the Stripe Credit Card field, any previous Stripe payment forms must be <em>manually updated</em> after the settings are saved.</p><p><strong>Stripe payments will not be processed until the form updates have been completed if you continue.</strong></p><p>Before proceeding, please <a href="%s" target="_blank" rel="noopener noreferrer">read our documentation</a> on updating and the steps involved.</p>',
					'wpforms-stripe'
				),
				[
					'p'      => [],
					'a'      => [
						'href'   => [],
						'target' => [],
						'rel'    => [],
					],
					'em'     => [],
					'strong' => [],
				]
			),
			esc_url( wpforms_utm_link( 'https://wpforms.com/docs/how-to-update-to-the-stripe-credit-card-field', 'Settings - Payments', 'Stripe Documentation' ) )
		);

		$admin_settings_stripe_l10n['payment_collection_type_modal_legacy'] = wp_kses(
			__(
				'<p>To use the legacy WPForms Credit Card field, any previous Stripe payment forms containing the Stripe Credit Card field must be <em>manually updated</em> after the settings are saved.</p><p><strong>Stripe payments will not be processed until the form updates have been completed if you continue.</strong></p>',
				'wpforms-stripe'
			),
			[
				'p'      => [],
				'em'     => [],
				'strong' => [],
			]
		);

		wp_localize_script(
			'wpforms-admin-settings-legacy-stripe',
			'wpforms_admin_settings_legacy_stripe',
			$admin_settings_stripe_l10n
		);

		wp_enqueue_style(
			'wpforms-admin-settings-legacy-stripe',
			plugin_dir_url( WPFORMS_STRIPE_FILE ) . "assets/css/admin-settings-legacy-stripe{$min}.css",
			[],
			WPFORMS_STRIPE_VERSION
		);
	}

	/**
	 * Register Settings fields.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Array of current form settings.
	 *
	 * @return array
	 */
	public function register_settings_fields( $settings ) {

		$settings = parent::register_settings_fields( $settings );

		$is_legacy_api    = Helpers::is_legacy_api_version();
		$has_legacy_forms = ! empty( $this->payment_forms['legacy'] );

		if ( $is_legacy_api || $has_legacy_forms ) {

			$settings['payments']['stripe-api-version'] = [
				'id'         => 'stripe-api-version',
				'name'       => esc_html__( 'Payment Collection Type', 'wpforms-stripe' ),
				'type'       => 'radio',
				'default'    => Helpers::has_stripe_keys() ? 2 : 3,
				'desc_after' => $this->get_payment_collection_type_desc_after(),
				'options'    => [
					3 => esc_html__( 'Stripe Credit Card Field (Recommended)', 'wpforms-stripe' ),
					2 => esc_html__( 'WPForms Credit Card Field (Deprecated, Unsupported)', 'wpforms-stripe' ),
				],
			];
		}

		if ( ( $is_legacy_api || $has_legacy_forms ) && ! Helpers::is_payment_element_enabled() ) {
			unset( $settings['payments']['stripe-card-mode'] );
		}

		$settings['payments']['stripe-test-publishable-key'] = [
			'id'   => 'stripe-test-publishable-key',
			'name' => esc_html__( 'Test Publishable Key', 'wpforms-stripe' ),
			'type' => 'text',
		];
		$settings['payments']['stripe-test-secret-key']      = [
			'id'   => 'stripe-test-secret-key',
			'name' => esc_html__( 'Test Secret Key', 'wpforms-stripe' ),
			'type' => 'text',
		];
		$settings['payments']['stripe-live-publishable-key'] = [
			'id'   => 'stripe-live-publishable-key',
			'name' => esc_html__( 'Live Publishable Key', 'wpforms-stripe' ),
			'type' => 'text',
		];
		$settings['payments']['stripe-live-secret-key']      = [
			'id'   => 'stripe-live-secret-key',
			'name' => esc_html__( 'Live Secret Key', 'wpforms-stripe' ),
			'type' => 'text',
		];

		return $settings;
	}

	/**
	 * Connection Status setting content.
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */
	protected function get_connection_status_content() {

		$output = parent::get_connection_status_content();

		if ( empty( $_GET['stripe_api_keys'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return $output;
		}

		$output .= '<p class="desc">' .
			wp_kses(
				__( 'Alternatively, you can <a href="#">manage your API keys manually</a>.', 'wpforms-stripe' ),
				[
					'a' => [
						'href'  => [],
						'class' => [],
					],
				]
			) . '</p>';

		return $output;
	}

	/**
	 * Disconnected Status setting content.
	 *
	 * @since 2.3.0
	 *
	 * @param string $mode Stripe mode (e.g. 'live' or 'test').
	 *
	 * @return string
	 */
	protected function get_disconnected_status_content( $mode = '' ) {

		$output = parent::get_disconnected_status_content( $mode );

		$account = wpforms_stripe()->connect->get_connected_account( $mode );

		if ( isset( $account->id ) || ! Helpers::has_stripe_keys( $mode ) ) {
			return $output;
		}

		$output .= sprintf(
			'<div class="wpforms-reconnect"><p>%s</p><p>%s</p></div>',
			esc_html__( 'You are currently connected to Stripe using a deprecated authentication method.', 'wpforms-stripe' ),
			esc_html__( 'Please re-authenticate using Stripe Connect to use a more secure authentication method.', 'wpforms-stripe' )
		);

		return $output;
	}

	/**
	 * Payment Collection Type setting after description.
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */
	private function get_payment_collection_type_desc_after() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		$is_legacy_api = Helpers::is_legacy_api_version();
		$display       = [];

		$update_notice = sprintf(
			wp_kses( /* translators: %s - WPForms.com Stripe documentation article URL. */
				__( '<p>This payment collection type has been deprecated and is no longer supported.</p><p>Payments continue to be processed but will stop working in the future.</p><p>To avoid disruption or failed payments, please upgrade the forms below to the Stripe Credit Card field.</p><p><a target="_blank" rel="noopener noreferrer" href="%s">Learn More</a></p>', 'wpforms-stripe' ),
				[
					'p' => [],
					'a' => [
						'href'   => [],
						'target' => [],
						'rel'    => [],
					],
				]
			),
			esc_url( wpforms_utm_link( 'https://wpforms.com/docs/how-to-update-to-the-stripe-credit-card-field', 'Settings - Payments', 'Update to the Stripe Credit Card Field Documentation' ) )
		);

		$updated_notice = '<p>' . sprintf(
			wp_kses( /* translators: %s - WPForms.com Stripe documentation article URL. */
				__( '<strong>IMPORTANT:</strong> The form(s) below need to be manually updated. Payments cannot be processed until the updates are completed. <a href="%s" target="_blank" rel="noopener noreferrer">Learn more</a>', 'wpforms-stripe' ),
				[
					'strong' => [],
					'a'      => [
						'href'   => [],
						'target' => [],
						'rel'    => [],
					],
				]
			),
			esc_url( wpforms_utm_link( 'https://wpforms.com/docs/how-to-update-to-the-stripe-credit-card-field', 'Settings - Payments', 'Update to the Stripe Credit Card Field Documentation' ) )
		) . '</p>';

		if ( $is_legacy_api && ! empty( $this->payment_forms['legacy'] ) ) {
			$display['text']  = 'update';
			$display['forms'] = 'legacy';
		} elseif ( ! $is_legacy_api && ! empty( $this->payment_forms['legacy'] ) ) {
			$display['text']  = 'updated';
			$display['forms'] = 'legacy';
		} elseif ( ! $is_legacy_api && ! empty( $this->payment_forms['elements'] ) ) {
			$display['text']  = 'update';
			$display['forms'] = 'elements';
		} elseif ( $is_legacy_api && ! empty( $this->payment_forms['elements'] ) ) {
			$display['text']  = 'updated';
			$display['forms'] = 'elements';
		}

		if ( empty( $display ) ) {
			return '';
		}

		$output  = '<div class="wpforms-' . esc_attr( $display['text'] ) . '">';
		$output .= $display['text'] === 'update' ? $update_notice : $updated_notice;
		$output .= '<ul>';

		foreach ( $this->payment_forms[ $display['forms'] ] as $form ) {
			$output .= sprintf(
				'<li><a href="%s" target="_blank">%s</a></li>',
				esc_url( admin_url( 'admin.php?page=wpforms-builder&view=fields&form_id=' . absint( $form->ID ) ) ),
				esc_html( $form->post_title )
			);
		}

		$output .= '</ul>';
		$output .= '</div>';

		return $output;
	}
}
