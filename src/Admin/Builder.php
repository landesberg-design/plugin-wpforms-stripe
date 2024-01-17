<?php

namespace WPFormsStripe\Admin;

use WPForms_Builder_Panel_Settings;
use WPForms\Integrations\Stripe\Admin\Builder\Enqueues;
use WPFormsStripe\Helpers;

/**
 * Stripe Form Builder related functionality.
 *
 * @since 2.0.0
 */
class Builder {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		$this->init();
	}

	/**
	 * Initialize.
	 *
	 * @since 2.0.0
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

		add_action( 'wpforms_form_settings_notifications_single_after', [ $this, 'notification_settings' ], 10, 2 );

		add_filter( 'wpforms_integrations_stripe_admin_builder_enqueues_data', [ $this, 'enqueues_data' ] );
	}

	/**
	 * Define if "Duplicate" button has to be displayed on field preview in a Form Builder.
	 *
	 * @since 2.3.0
	 * @deprecated 3.2.0
	 *
	 * @param bool  $display Display switch.
	 * @param array $field   Field settings.
	 */
	public function field_display_duplicate_button( $display, $field ) {

		_deprecated_function( __FUNCTION__, '3.2.0 of the WPForms Stripe Pro addon', 'WPForms\Integrations\Stripe\Fields\Traits\CreditCard::field_display_duplicate_button()' );

		return wpforms_stripe()->api->get_config( 'field_slug' ) === $field['type'] ? false : $display;
	}

	/**
	 * Add legacy field slug to the builder script data.
	 *
	 * @since 3.0.0
	 *
	 * @param array $data Script data.
	 */
	public function enqueues_data( $data ) {

		$data['field_slugs'] = array_filter( array_values( Helpers::get_api_classes_config( 'field_slug' ) ) );

		return $data;
	}

	/**
	 * Add our localized strings to be available in the form builder.
	 *
	 * @since 2.0.0
	 * @deprecated 3.0.0
	 *
	 * @param array $strings Form builder JS strings.
	 *
	 * @return array
	 */
	public function javascript_strings( $strings ) {

		_deprecated_function( __METHOD__, '3.0.0', '\WPForms\Integrations\Stripe\Admin\Builder\Enqueues::javascript_strings' );

		return ( new Enqueues() )->javascript_strings( $strings );
	}

	/**
	 * Enqueue assets for the builder.
	 *
	 * @since 2.0.0
	 * @deprecated 3.0.0
	 */
	public function enqueues() {

		_deprecated_function( __METHOD__, '3.0.0', '\WPForms\Integrations\Stripe\Admin\Builder\Enqueues::enqueues' );

		( new Enqueues() )->enqueues();
	}

	/**
	 * Output form builder settings panel content.
	 *
	 * @since 2.0.0
	 * @deprecated 3.0.0
	 *
	 * @param array $form_data Form data and settings.
	 */
	public static function content( $form_data ) {

		_deprecated_function( __METHOD__, '3.0.0', '\WPFormsStripe\Admin\StripePayment::builder_content' );

		( new StripePayment() )->builder_content();
	}

	/**
	 * Add checkbox to form notification settings.
	 *
	 * @since 2.5.0
	 *
	 * @param WPForms_Builder_Panel_Settings $settings WPForms_Builder_Panel_Settings class instance.
	 * @param int                            $id       Subsection ID.
	 */
	public function notification_settings( $settings, $id ) {

		wpforms_panel_field(
			'toggle',
			'notifications',
			'stripe',
			$settings->form_data,
			esc_html__( 'Enable for Stripe completed payments', 'wpforms-stripe' ),
			[
				'parent'      => 'settings',
				'class'       => ! Helpers::is_payments_enabled( $settings->form_data ) ? 'wpforms-hidden' : '',
				'input_class' => 'wpforms-radio-group wpforms-radio-group-' . $id . '-notification-by-status wpforms-radio-group-item-stripe wpforms-notification-by-status-alert',
				'subsection'  => $id,
				'tooltip'     => wp_kses(
					__( 'When enabled this notification will <em>only</em> be sent when a Stripe payment has been successfully <strong>completed</strong>.', 'wpforms-stripe' ),
					[
						'em'     => [],
						'strong' => [],
					]
				),
				'data'        => [
					'radio-group'    => $id . '-notification-by-status',
					'provider-title' => esc_html__( 'Stripe completed payments', 'wpforms-stripe' ),
				],
			]
		);
	}
}
