/* global wpforms_admin_settings_legacy_stripe, wpforms_admin */

'use strict';

/**
 * WPForms Payments Stripe Legacy Settings functions.
 *
 * @since 2.3.0
 * @since 3.0.0 The file was renamed.
 */
let WPFormsSettingsLegacyStripe = window.WPFormsSettingsLegacyStripe || ( function( document, window, $ ) {

	/**
	 * Elements.
	 *
	 * @since 3.0.0
	 *
	 * @type {object}
	 */
	const $el = {
		paymentCollectionTypeInputs: $( 'input[name=stripe-api-version]' ),
		apiKeyInputs: $( '#wpforms-setting-row-stripe-test-publishable-key, #wpforms-setting-row-stripe-test-secret-key, #wpforms-setting-row-stripe-live-publishable-key, #wpforms-setting-row-stripe-live-secret-key' ),
		apiKeyToggle: $( '#wpforms-setting-row-stripe-connection-status .desc a' ),
	};

	/**
	 * Public functions and properties.
	 *
	 * @since 3.0.0
	 *
	 * @type {object}
	 */
	const app = {

		/**
		 * Start the engine.
		 *
		 * @since 3.0.0
		 */
		init: function() {

			$( app.ready );
		},

		/**
		 * Document ready.
		 *
		 * @since 3.0.0
		 */
		ready: function() {

			app.events();
		},

		/**
		 * Register JS events.
		 *
		 * @since 3.0.0
		 */
		events: function() {

			$( document ).on( 'change', 'input[name=stripe-api-version]', app.triggerPaymentCollectionAlert );

			$el.apiKeyToggle.on( 'click', function( event ) {

				event.preventDefault();

				$el.apiKeyInputs.toggle();
			} );
		},

		/**
		 * Conditionally prevent showing the settings panel.
		 *
		 * @since 3.0.0
		 */
		triggerPaymentCollectionAlert: function() {

			let type = parseInt( $( 'input[name=stripe-api-version]:checked' ).val(), 10 );

			// User selected WPForms Credit Card field.
			if ( type === 2 && wpforms_admin_settings_legacy_stripe.has_payment_forms_elements ) {
				$.alert( {
					title: wpforms_admin.heads_up,
					content: '<div id="wpforms-stripe-payment-collection-update-modal">' + wpforms_admin_settings_legacy_stripe.payment_collection_type_modal_legacy + '</div>',
					boxWidth: '425px',
					icon: 'fa fa-exclamation-circle',
					type: 'orange',
					buttons: {
						confirm: {
							text: wpforms_admin.ok,
							btnClass: 'btn-confirm',
							keys: [ 'enter' ],
						},
						cancel: {
							text: wpforms_admin.cancel,
							keys: [ 'esc' ],
							action: function() {

								$el.paymentCollectionTypeInputs.filter( '[value=3]' ).prop( 'checked', true );
							},
						},
					},
				} );

				return;
			}

			// User selected Stripe Credit Card.
			if ( type === 3 && wpforms_admin_settings_legacy_stripe.has_payment_forms_legacy ) {
				$.alert( {
					title: wpforms_admin.heads_up,
					content: '<div id="wpforms-stripe-payment-collection-update-modal">' + wpforms_admin_settings_legacy_stripe.payment_collection_type_modal_elements + '</div>',
					boxWidth: '425px',
					icon: 'fa fa-exclamation-circle',
					type: 'orange',
					buttons: {
						confirm: {
							text: wpforms_admin_settings_legacy_stripe.payment_collection_type_modal_elements_ok,
							btnClass: 'btn-confirm btn-block btn-normal-case',
							keys: [ 'enter' ],
						},
						cancel: {
							text: wpforms_admin_settings_legacy_stripe.mode_update_cancel,
							btnClass: 'btn-block btn-normal-case',
							keys: [ 'esc' ],
							action: function() {

								$el.paymentCollectionTypeInputs.filter( '[value=2]' ).prop( 'checked', true );
							},
						},
					},
				} );
			}
		},
	};

	// Provide access to public functions/properties.
	return app;

}( document, window, jQuery ) );

// Initialize.
WPFormsSettingsLegacyStripe.init();
