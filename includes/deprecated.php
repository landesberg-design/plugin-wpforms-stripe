<?php
/**
 * Deprecated functions.
 * This file is used to keep backward compatibility with older versions of the plugin.
 * The functions and classes listed below will be removed in December 2023.
 *
 * @since 3.1.0
 */

/**
 * Check addon requirements.
 *
 * @since 2.5.0
 * @deprecated 3.1.0
 */
function wpforms_stripe_required() {

	_deprecated_function( __FUNCTION__, '3.1.0 of the WPForms Stripe Pro addon' );
}

/**
 * Deactivate the plugin.
 *
 * @since 2.5.0
 * @deprecated 3.1.0
 */
function wpforms_stripe_deactivate() {

	_deprecated_function( __FUNCTION__, '3.1.0 of the WPForms Stripe Pro addon' );
}

/**
 * Admin notice for a minimum PHP version.
 *
 * @since 2.5.0
 * @deprecated 3.1.0
 */
function wpforms_stripe_fail_php_version() {

	_deprecated_function( __FUNCTION__, '3.1.0 of the WPForms Stripe Pro addon' );
}

/**
 * Admin notice for minimum WPForms version.
 *
 * @since 2.5.0
 * @deprecated 3.1.0
 */
function wpforms_stripe_fail_wpforms_version() {

	_deprecated_function( __FUNCTION__, '3.1.0 of the WPForms Stripe Pro addon' );
}

/**
 * Load the plugin updater.
 *
 * @since 2.5.0
 * @deprecated 3.1.0
 *
 * @param string $key License key.
 */
function wpforms_stripe_updater( $key ) {

	_deprecated_function( __FUNCTION__, '3.1.0 of the WPForms Stripe Pro addon' );
}
