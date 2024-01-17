# Changelog
All notable changes to this project will be documented in this file, formatted via [this recommendation](https://keepachangelog.com/).

## [3.3.0] - 2024-01-09
### Added
- Compatibility with WPForms 1.8.6.

### Changed
- Minimum WPForms version supported is 1.8.6.

## [3.2.0] - 2023-11-08
### Added
- Compatibility with WPForms 1.8.5.

### Changed
- Minimum WPForms version supported is 1.8.5.

## [3.1.0] - 2023-09-26
### IMPORTANT
- Support for PHP 5.6 has been discontinued. If you are running PHP 5.6, you MUST upgrade PHP before installing WPForms Stripe Pro 3.1.0. Failure to do that will disable WPForms Stripe Pro functionality.
- Support for WordPress 5.4 and below has been discontinued. If you are running any of those outdated versions, you MUST upgrade WordPress before installing WPForms Stripe Pro 3.1.0. Failure to do that will disable WPForms Stripe Pro functionality.

### Added
- Multiple Subscription Plans can now be configured on the Builder screen!

### Changed
- Minimum WPForms version supported is 1.8.4.
- The now deprecated `wpmu_new_blog` hook was replaced with the `wp_initialize_site` hook.

### Fixed
- Stripe payment's country field was cropped off.

## [3.0.1] - 2023-06-13
### Fixed
- Stripe Credit Card field was preventing email notifications about form submission to be sent in certain scenarios.

## [3.0.0] - 2023-05-31
### IMPORTANT
- A significant part of the addon has been moved to the WPForms plugin due to the new Stripe Payments feature.

### Added
- Compatibility with WPForms 1.8.2.

### Changed
- Minimum WPForms version supported is 1.8.2.

## [2.11.0] - 2023-03-23
### Added
- Compatibility of Stripe Payment Element with the upcoming WPForms 1.8.1.

### Changed
- Improved compatibility with Lead Forms addon.

### Fixed
- Form was not scrolled to the CC Number, Date and CVV fields if validation for these fields failed.
- Stripe Credit Card with Payment Elements worked for a first payment form only, even though there was more than one form on the same page.
- Fields for verification code were not visible for Stripe Element with Link.
- Placeholders and labels were overlapped for Stripe Element field after page refresh with multipage form.
- Payment Element in Conversational Form was not set up properly on field activation.

## [2.10.0] - 2023-02-28
### Added
- Compatibility with the upcoming WPForms 1.8.1.
- Stripe Payment Element and Payment Links are supported now!

### Changed
- Minimum WPForms version supported is 1.8.0.2.

### Fixed
- The previous page on the multi-page form could not be opened without filling in the Credit Card Number field.
- Under certain circumstances, a PHP notice was raised when a payment form was submitted.

## [2.9.0] - 2023-02-02
### IMPORTANT
- Support of the legacy way to collect payments is deprecated and is no longer supported. Payments continue to be processed but will stop working in the future. Please upgrade your forms to the new Stripe Credit Card field to avoid disruptions or failed payments.

### Fixed
- The Credit Card field didn't process validation when navigating between pages in a multi-page form.
- Subscription amount could be incorrect for zero-decimal currencies.
- "Enable Stripe payments" prompt didn't work correctly for a restored form.

## [2.8.0] - 2022-10-12
### Changed
- Minimum WPForms version supported is 1.7.7.2.

### Fixed
- Stripe transaction URL for test mode had a wrong format on the Entry details page.

## [2.7.0] - 2022-10-04
### Changed
- Stripe Credit Card fields are not shown anymore if Stripe Payments are not enabled or Stripe Keys are not set.
- Stripe completed payment notifications are sent for completed payments only.

### Fixed
- Entries Search by Payments Details did not work for Stripe payments for users who upgraded from v2.5.0 to v2.6.0 or v2.6.1.

## [2.6.1] - 2022-07-28
### Changed
- Minimum WPForms version supported is 1.7.5.5.

### Fixed
- Some older migrations were running in incorrect order effectively breaking the Stripe integration by switching users to an older Stripe API version.
- Because of the migration bug, explained above, the Stripe Credit Card field was unavailable in the Builder and was ignored on the front end.

## [2.6.0] - 2022-07-20
### IMPORTANT
- Support for WordPress 5.1 has been discontinued. If you are running WordPress 5.1, you MUST upgrade WordPress before installing the new WPForms Stripe. Failure to do that will disable the new WPForms Stripe functionality.

### Added
- Compatibility with WPForms 1.6.8 and the updated Form Builder.

### Changed
- Show settings in the Form Builder only if they are enabled.
- Connect button UI improvements on Settings > Payments admin page.
- Minimum WPForms version supported is 1.7.5.

### Fixed
- Certain strings were not translatable.
- Improved performance on the WPForms Settings pages.
- The "Name on Card" placeholder value is not updated in the Form Builder preview.
- Some fonts were not working properly with the Credit Card field.
- Compatibility with WordPress Multisite installations.

## [2.5.0] - 2021-03-31
### Added
- New "Switch Accounts" link in the addon settings to change the Stripe account used.
- Filter to apply custom styles to a Credit Card field.
- Email Notifications option to limit to completed payments only.

### Changed
- Full object is now logged instead of just a message in case of a Stripe error.
- Upgrade Stripe PHP SDK to 7.72.0.

### Fixed
- Edge case when a subscription email is still being required despite changing the payment to single by conditional logic.
- Invalid characters in 'font-family' added by external CSS rules may break the Credit Card field.
- Stripe form with active Captcha fails to submit after Stripe 3DSecure validation.

## [2.4.3] - 2020-12-17
### Fixed
- Stripe Live/Test modal appears when clicking on any checkbox in WPForms settings while using jQuery 3.0.

## [2.4.2]
### Changed
- Improved the error rate limiting by adding file-based rate-limiting log storage.

## [2.4.1] - 2020-08-06
### Fixed
- Card field can be mistakenly processed as hidden under some conditional logic configurations.

## [2.4.0] - 2020-08-05
### Added
- Stripe Elements locale can be set explicitly via the filter.

### Changed
- Improved Stripe error handling during form processing.

### Fixed
- Conditionally hidden Stripe field should not be processed on form submission.

## [2.3.4] - 2020-04-30
### Fixed
- In some edge cases Stripe payment goes through without creating a form entry.

## [2.3.3] - 2020-01-15
### Fixed
- Payment form entry details are not updated despite Stripe payment completing successfully.

## [2.3.2] - 2020-01-09
### Changed
- Improved form builder messaging when Stripe plugin settings have not been configured.
- Improved messaging on Stripe plugin settings.

## [2.3.1] - 2019-10-14
### Fixed
- PHP notice in WPForms settings if user has no Stripe forms.
- Stripe Connect issues switching between Live/Test mode.

## [2.3.0]
### Added
- SCA support.
- Stripe Elements.
- Stripe Connect.
- Rate limiting for failed payments.

## [2.2.0] - 2019-07-23
### Added
- Complete translations for French and Portuguese (Brazilian).

## [2.1.2] - 2018-03-11
### Changed
- Stripe API key settings display order, to follow Stripe documentation.

## [2.1.1] - 2018-02-08
### Fixed
- Typos, grammar, and other i18n related issues.

## [2.1.0] - 2018-02-06
### Added
- Complete translations for Spanish, Italian, Japanese, and German.

### Changed
- Processing now checks to make sure order amount is above Stripe minimum (0.50) before proceeding.

### Fixed
- Typos, grammar, and other i18n related issues.

## [2.0.2] - 2018-11-27
### Added
- Include addon information when connecting to Stripe API.

## [2.0.1] - 2018-09-05
### Fixed
- Stripe API error

## [2.0.0] - 2018-09-04
### IMPORTANT
- The addon structure has been improved and refactored. If you are extending the plugin by accessing the class instance, an update to your code will be required before upgrading (use `wpforms_stripe()`).

### Added
- Recurring subscription payments! 💥🎉

### Changed
- Improved metadata sent with charge details.

### Removed
- `wpforms_stripe_instance` function and `WPForms_Stripe::instance()`.

## [1.1.3] - 2018-05-14
### Changed
- Enable Credit Card field when addon is activated; as of WPForms 1.4.6 the credit card field is now disabled/hidden unless explicitly enabled.

## [1.1.2] - 2018-04-05
### Changed
- Improved enforcement of Stripe processing with required credit card fields.

## [1.1.1] - 2017-08-24
### Changed
- Remove JS functionality adopted in core plugin

## [1.1.0] - 2017-06-13
### Changed
- Use settings API for WPForms v1.3.9.

## [1.0.9] - 2017-08-01
### Changed
- Improved performance when checking for credit card fields in the form builder

## [1.0.8] - 2017-03-30
### Changed
- Updated Stripe API PHP library
- Improved Stripe class instance accessibility

## [1.0.7] - 2017-01-17
### Changed
- Check for charge object before firing transaction completed hook

## [1.0.6] - 2016-12-08
### Added
- Support for Dropdown Items payment field
- New hook for completed transactions, `wpforms_stripe_process_complete`
- New filter stored credit card information, `wpforms_stripe_creditcard_value`

## [1.0.5] - 2016-10-07
### Fixed
- Javascript processing method to avoid conflicts with core duplicate submit prevention feature

## [1.0.4] - 2016-08-22
### Added
- Expanded support for additional currencies

### Fixed
- Localization issues/bugs

### Changed

## [1.0.3] - 2016-07-07
### Added
- Conditional logic for payments

### Changed
- Improved error logging

## [1.0.2] - 2016-06-23
### Changed
- Prevent plugin from running if WPForms Pro is not activated

## [1.0.1] - 2016-04-01
### Fixed
- PHP notices with some configurations

## [1.0.0] - 2016-03-28
### Added
- Initial release
