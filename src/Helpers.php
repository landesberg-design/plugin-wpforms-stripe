<?php

namespace WPFormsStripe;

use WPForms\Integrations\Stripe\Api\ApiInterface;
use WPForms\Integrations\Stripe\Api\PaymentIntents;

/**
 * Stripe related helper methods.
 *
 * @since 2.0.0
 */
class Helpers extends \WPForms\Integrations\Stripe\Helpers {

	/**
	 * Get API version.
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */
	public static function get_api_version() {

		return \apply_filters( 'wpforms_stripe_helpers_get_api_class_api_version', \wpforms_setting( 'stripe-api-version' ) );
	}

	/**
	 * Get API classes list.
	 *
	 * @since 2.3.0
	 *
	 * @return array
	 */
	public static function get_api_classes() {

		$classes = array(
			2 => '\WPFormsStripe\API\Charges',
			3 => '\WPForms\Integrations\Stripe\Api\PaymentIntents',
		);

		return \apply_filters( 'wpforms_stripe_helpers_get_api_classes', $classes );
	}

	/**
	 * Get API classes configuration arrays or just the specific keys.
	 *
	 * @since 2.3.0
	 *
	 * @param string $key Name of the key to retrieve.
	 *
	 * @return array
	 */
	public static function get_api_classes_config( $key = '' ) {

		$api_classes = self::get_api_classes();
		$configs     = array();

		foreach ( $api_classes as $api_class ) {

			/**
			 * Instance of API class.
			 *
			 * @var ApiInterface $instance
			 */
			$instance = new $api_class();
			$instance->set_config();

			$configs[ $api_class ] = $instance->get_config( $key );
		}

		return $configs;
	}

	/**
	 * Get API class object.
	 *
	 * @since 2.3.0
	 *
	 * @return ApiInterface
	 */
	public static function get_api_class() {

		$api_version = self::get_api_version();
		$api_classes = self::get_api_classes();

		if ( array_key_exists( absint( $api_version ), $api_classes ) ) {
			$class = new $api_classes[ $api_version ]();
		}

		if ( empty( $class ) ) {
			$class = new PaymentIntents();
		}

		/**
		 * Filter for Stripe Api class.
		 *
		 * @since 2.3.0
		 *
		 * @param ApiInterface $class Api class.
		 */
		return apply_filters( 'wpforms_stripe_helpers_get_api_class', $class ); // phpcs:ignore WPForms.PHP.ValidateHooks.InvalidHookName
	}

	/**
	 * Get forms using Stripe with a specific payment collection type.
	 *
	 * @since 2.3.0
	 *
	 * @param string $type Payment collection type, legacy or elements.
	 *
	 * @return array
	 */
	public static function get_forms_by_payment_collection_type( $type = 'legacy' ) {

		$field_type = 'credit-card';
		$forms      = wpforms()->get( 'form' )->get();

		if ( $type === 'elements' || absint( $type ) === 3 ) {
			$field_type = 'stripe-credit-card';
		}

		if ( empty( $forms ) ) {
			return [];
		}

		$payment_forms = [];

		foreach ( $forms as $form ) {

			$form_data = wpforms_decode( $form->post_content );

			if ( ! self::is_payments_enabled( $form_data ) ) {
				continue;
			}

			if ( wpforms_has_field_type( $field_type, $form_data ) ) {
				$payment_forms[] = $form;
			}
		}

		return $payment_forms;
	}

	/**
	 * Determine if Payment element mode is enabled and valid.
	 *
	 * @since 2.10.0
	 *
	 * @return bool
	 */
	public static function is_payment_element_enabled() {

		return wpforms_setting( 'stripe-card-mode' ) === 'payment' && ! self::is_legacy_api_version();
	}

	/**
	 * Determine if Legacy API version is used.
	 *
	 * @since 2.10.0
	 *
	 * @return bool
	 */
	public static function is_legacy_api_version() {

		return absint( self::get_api_version() ) === 2;
	}
}
