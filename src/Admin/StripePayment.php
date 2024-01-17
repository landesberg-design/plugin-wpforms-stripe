<?php

namespace WPFormsStripe\Admin;

use WPForms\Integrations\Stripe\Admin\Builder\Traits\ContentTrait;

/**
 * Stripe Form Builder related functionality.
 *
 * @since 2.0.0
 */
class StripePayment extends \WPForms_Payment {

	use ContentTrait;

	/**
	 * Initialize.
	 *
	 * @since 2.0.0
	 */
	public function init() {

		$this->version     = WPFORMS_STRIPE_VERSION;
		$this->name        = 'Stripe';
		$this->slug        = 'stripe';
		$this->priority    = 0;
		$this->recommended = true;
		$this->icon        = WPFORMS_PLUGIN_URL . 'assets/images/addon-icon-stripe.png';

		$this->hooks();
	}

	/**
	 * Registering hooks.
	 *
	 * @since 3.1.0
	 */
	private function hooks() {

		add_action( 'wpforms_payment_builder_content_recurring_before', [ $this, 'builder_content_recurring_payment_before_content' ] );
	}

	/**
	 * Get single payments conditional logic for the Stripe settings panel.
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */
	private function single_payments_conditional_logic_section() {

		return wpforms_conditional_logic()->builder_block(
			[
				'form'        => $this->form_data,
				'type'        => 'panel',
				'panel'       => $this->slug,
				'parent'      => 'payments',
				'actions'     => [
					'go'   => esc_html__( 'Process', 'wpforms-stripe' ),
					'stop' => esc_html__( 'Don\'t process', 'wpforms-stripe' ),
				],
				'action_desc' => esc_html__( 'this charge if', 'wpforms-stripe' ),
				'reference'   => esc_html__( 'Stripe payment', 'wpforms-stripe' ),
			],
			false
		);
	}

	/**
	 * Get recurring payments conditional logic for the Stripe settings panel.
	 *
	 * @since 3.0.0
	 * @since 3.1.0 Added Plan ID parameter.
	 *
	 * @param string $plan_id Plan ID.
	 *
	 * @return string
	 */
	private function recurring_payments_conditional_logic_section( $plan_id = '' ) {

		return wpforms_conditional_logic()->builder_block(
			[
				'form'        => $this->form_data,
				'type'        => 'panel',
				'panel'       => 'stripe',
				'parent'      => 'payments',
				'subsection'  => 'recurring',
				'index'       => $plan_id,
				'actions'     => [
					'go'   => esc_html__( 'Process', 'wpforms-stripe' ),
					'stop' => esc_html__( 'Don\'t process', 'wpforms-stripe' ),
				],
				'action_desc' => esc_html__( 'payment as recurring if', 'wpforms-stripe' ),
				'reference'   => esc_html__( 'Stripe Recurring payment', 'wpforms-stripe' ),
			],
			false
		);
	}

	/**
	 * Display content before the recurring payment area.
	 *
	 * @since 3.1.0
	 *
	 * @param string $slug Payment slug.
	 */
	public function builder_content_recurring_payment_before_content( $slug ) {

		if ( $this->slug !== $slug ) {
			return;
		}

		$has_multiple_plans = false;

		if (
			! empty( $this->form_data['payments']['stripe']['recurring'][0] ) &&
			is_array( $this->form_data['payments']['stripe']['recurring'] ) &&
			count( $this->form_data['payments']['stripe']['recurring'] ) > 1
		) {
			$has_multiple_plans = true;
		}

		$is_hidden = $has_multiple_plans ? '' : 'wpforms-hidden';

		printf(
			'<p class="wpforms-alert wpforms-alert-warning wpforms-stripe-multiple-plans-warning %2$s">%1$s</p>',
			esc_html__( 'It\'s not possible to process multiple plans at the same time. If your conditional logic matches more than one plan, the form will process the first plan that matches your conditions.', 'wpforms-stripe' ),
			esc_attr( $is_hidden )
		);
	}
}
