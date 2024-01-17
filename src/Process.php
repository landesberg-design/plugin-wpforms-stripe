<?php

namespace WPFormsStripe;

/**
 * Stripe payment processing.
 *
 * @since 2.0.0
 */
class Process extends \WPForms\Integrations\Stripe\Process {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		$this->init( wpforms_stripe()->api );
	}

	/**
	 * Process a single payment.
	 *
	 * @since 3.1.0
	 */
	public function process_payment_single() {

		if ( ! $this->is_single_payment_conditional_logic_ok() ) {
			return;
		}

		parent::process_payment_single();
	}

	/**
	 * Validate plan before process.
	 *
	 * @since 3.1.0
	 *
	 * @param array $plan Plan settings.
	 *
	 * @return bool
	 */
	protected function is_subscription_plan_valid( $plan ) {

		if ( ! $this->is_conditional_logic_ok( $plan ) ) {
			return false;
		}

		return parent::is_subscription_plan_valid( $plan );
	}

	/**
	 * Process a subscription payment for forms with old payments interface.
	 *
	 * @since 3.1.0
	 */
	protected function process_legacy_payment_subscription() {

		// Check for conditional logic.
		if ( ! $this->is_conditional_logic_ok( $this->settings['recurring'] ) ) {
			$this->process_payment_single();

			return;
		}

		// Check for single conditional logic.
		if ( ! $this->is_single_payment_conditional_logic_ok() ) {
			return;
		}

		parent::process_legacy_payment_subscription();
	}

	/**
	 * Check if conditional logic check passes for the given settings.
	 *
	 * @since 2.3.0
	 *
	 * @param array $settings Conditional logic settings to process.
	 *
	 * @return bool
	 */
	protected function is_conditional_logic_ok( $settings ) {

		// Check conditional logic settings.
		if (
			empty( $settings['conditional_logic'] ) ||
			empty( $settings['conditional_type'] ) ||
			empty( $settings['conditionals'] )
		) {
			return true;
		}

		// All conditional logic checks passed, continue with processing.
		$process = wpforms_conditional_logic()->process( $this->fields, $this->form_data, $settings['conditionals'] );

		if ( $settings['conditional_type'] === 'stop' ) {
			$process = ! $process;
		}

		return $process;
	}

	/**
	 * Check if single payment conditional logic is passed.
	 *
	 * @since 3.1.0
	 *
	 * @return bool
	 */
	private function is_single_payment_conditional_logic_ok() {

		// Check for conditional logic.
		if ( $this->is_conditional_logic_ok( $this->settings ) ) {
			return true;
		}

		$this->log_error(
			esc_html__( 'Stripe payment stopped by conditional logic.', 'wpforms-stripe' ),
			$this->fields,
			'conditional_logic'
		);

		return false;
	}
}
