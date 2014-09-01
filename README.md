# Angell EYE PayPal PHP Library

This PHP class library for PayPal makes it easy to integrate PayPal APIs, including [Adaptive Accounts](https://developer.paypal.com/webapps/developer/docs/classic/api/#aa), 
[Adaptive Payments](https://developer.paypal.com/webapps/developer/docs/classic/api/#ap), [Invoicing](https://developer.paypal.com/webapps/developer/docs/classic/api/#invoicing), 
[General Merchant APIs](https://developer.paypal.com/webapps/developer/docs/classic/api/#merchant), and [Permissions](https://developer.paypal.com/webapps/developer/docs/classic/api/#permissions).

-----------------------

## Server Requirements

- PHP version 5.3.0 or newer.
- cURL

## Installation

### Video Overview

<a href="http://www.youtube.com/watch?feature=player_embedded&v=f9wi8m7_FDc" target="_blank">
<img src="http://img.youtube.com/vi/f9wi8m7_FDc/0.jpg"
alt="Install via Composer or Manual Download Overview Video" width="240" height="180" border="10" /></a>

### Composer Install

Create a composer.json file with the following section and run composer update.

```json
    "require": {
		"php": ">=5.3.0",
		"ext-curl": "*",
		"angelleye/paypal-php-library": "2.0.*"
	}
```

### Manual Install (without Composer)

- [Download](https://github.com/angelleye/paypal-php-library/archive/master.zip) the class library and extract the contents do a directory in your project structure. 
- Upload the files to your web server.

## Setup

Open /samples/config/config-sample.php, fill out your details accordingly, and save-as config.php to a location of your choice.

To use the library in your project, include the following into your file(s).

- /path/to/config.php (It is recommended that you move this to a directory outside your site root on the web server and use an absolute path to include it.)
- autoload.php

## Usage

- Open the template file that corresponds to the API call you'd like to make.
    * Example: If we want to make a call to the RefundTransaction API we open up /templates/RefundTransaction.php
	
- You may leave the file here, or save this file to the location on your web server where you'd like this call to be made.
    * I like to save the files to a separate location and keep the ones included with the library as empty templates.
	* Note that you can also copy/paste the template code into your own file(s).
	
- Each template file prepares the PayPal class object for you and includes PHP arrays for every parameter available to that particular API. Simply fill in the array parameters with your own dynamic (or static) data. This data may come from:
    * Session Variables
	* General Variables
	* Database Recordsets
	* Static Values
	* Etc.
	
- When you run the file you will get a $PayPalResult array that consists of all the response parameters from PayPal, original request parameters sent to PayPal, and raw request/response info for troubleshooting.
    * You may refer to the [PayPal API Reference Guide](https://developer.paypal.com/webapps/developer/docs/classic/api/) for details about what response parameters you can expect to get back from any successful API request.
        + Example: When working with RefundTransaction, I can see that PayPal will return a REFUNDTRANSACTIONID, FEEREFUNDAMT, etc. As such, I know that those values will be included in $PayPalResult['REFUNDTRANSACTIONID'] and $PayPalResult['FEEREFUNDAMT'] respectively.

- If errors occur they will be available in $PayPalResult['ERRORS']

You may refer to this [overview video](http://www.angelleye.com/overview-of-php-class-library-for-paypal/) of how to use the library, 
and there are also samples provided in the /samples directory as well as blank templates ready to use under /templates.

If you need additional help you may [place an order for premium support](http://www.angelleye.com/product/premium-support/).

## Tutorials

- [How to install the Angell EYE PHP Class Library for PayPal](http://www.angelleye.com/install-angell-eye-php-class-library-paypal/)


## Supported APIs

### Adaptive Accounts

-  [AddBankAccount](https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-accounts/AddBankAccount_API_Operation/)
-  [AddPaymentCard](https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-accounts/AddPaymentCard_API_Operation/)
-  [CreateAccount](https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-accounts/CreateAccount_API_Operation/)
-  [GetVerifiedStatus](https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-accounts/GetVerifiedStatus_API_Operation/)
-  [SetFundingSourceConfirmed](https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-accounts/SetFundingSourceConfirmed_API_Operation/)

### Adaptive Payments

-  [CancelPreapproval](https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/CancelPreapproval_API_Operation/)
-  [ConvertCurrency](https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/ConvertCurrency_API_Operation/)
-  [ExecutePayment](https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/ExecutePayment_API_Operation/)
-  [GetFundingPlans](https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/GetFundingPlans_API_Operation/)
-  [GetPaymentOptions](https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/GetPaymentOptions_API_Operation/)
-  [GetShippingAddresses](https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/GetShippingAddresses_API_Operation/)
-  [Pay](https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/Pay_API_Operation/)
-  PayWithOptions
-  [PaymentDetails](https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/PaymentDetails_API_Operation/)
-  [Preapproval](https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/Preapproval_API_Operation/)
-  [PreapprovalDetails](https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/PreapprovalDetails_API_Operation/)
-  [Refund](https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/Refund_API_Operation/)
-  [SetPaymentOptions](https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/SetPaymentOptions_API_Operation/)

### Button Manager

-  [BMButtonSearch](https://developer.paypal.com/webapps/developer/docs/classic/api/button-manager/BMButtonSearch_API_Operation_NVP/)

### Invoicing

-  [CancelInvoice](https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/CancelInvoice_API_Operation/)
-  [CreateAndSendInvoice](https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/CreateAndSendInvoice_API_Operation/)
-  [CreateInvoice](https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/CreateInvoice_API_Operation/)
-  [DeleteInvoice](https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/DeleteInvoice_API_Operation/)
-  [GenerateInvoiceNumber](https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/GenerateInvoiceNumber_API_Operation/)
-  [GetInvoiceDetails](https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/GetInvoiceDetails_API_Operation/)
-  [MarkInvoiceAsPaid](https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/MarkInvoiceAsPaid_API_Operation/)
-  [MarkInvoiceAsRefunded](https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/MarkInvoiceAsRefunded_API_Operation/)
-  [MarkInvoiceAsUnpaid](https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/MarkInvoiceAsUnpaid_API_Operation/)
-  [RemindInvoice](https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/RemindInvoice_API_Operation/)
-  [SearchInvoices](https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/SearchInvoices_API_Operation/)
-  [SendInvoice](https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/SendInvoice_API_Operation/)
-  [UpdateInvoice](https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/UpdateInvoice_API_Operation/)

### Merchant

-  [AddressVerify](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/AddressVerify_API_Operation_NVP/)
-  [BAUpdate](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/BAUpdate_API_Operation_NVP/)
-  [BillOutstandingAmount](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/BillOutstandingAmount_API_Operation_NVP/)
-  [Callback (Express Checkout)](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/Callback_API_Operation_NVP/)
-  [CreateBillingAgreement](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/CreateBillingAgreement_API_Operation_NVP/)
-  [CreateRecurringPaymentsProfile](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/CreateRecurringPaymentsProfile_API_Operation_NVP/)
-  [DoAuthorization](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoAuthorization_API_Operation_NVP/)
-  [DoCapture](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoCapture_API_Operation_NVP/)
-  [DoDirectPayment](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoDirectPayment_API_Operation_NVP/)
-  [DoExpressCheckoutPayment](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoExpressCheckoutPayment_API_Operation_NVP/)
-  [DoNonReferencedCredit](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoNonReferencedCredit_API_Operation_NVP/)
-  [DoReauthorization](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoReauthorization_API_Operation_NVP/)
-  [DoReferenceTransaction](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoReferenceTransaction_API_Operation_NVP/)
-  [DoVoid](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoVoid_API_Operation_NVP/)
-  [GetBalance](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetBalance_API_Operation_NVP/)
-  [GetBillingAgreementCustomerDetails](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetBillingAgreementCustomerDetails_API_Operation_NVP/)
-  [GetExpressCheckoutDetails](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetExpressCheckoutDetails_API_Operation_NVP/)
-  [GetPalDetails](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetPalDetails_API_Operation_NVP/)
-  [GetRecurringPaymentsProfileDetails](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetRecurringPaymentsProfileDetails_API_Operation_NVP/)
-  GetRecurringPaymentsProfileStatus
-  [GetTransactionDetails](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetTransactionDetails_API_Operation_NVP/)
-  [ManagePendingTransactionStatus](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/ManagePendingTransactionStatus_API_Operation_NVP/)
-  [ManageRecurringPaymentsProfileStatus](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/ManageRecurringPaymentsProfileStatus_API_Operation_NVP/)
-  [MassPay](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/MassPay_API_Operation_NVP/)
-  [RefundTransaction](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/RefundTransaction_API_Operation_NVP/)
-  [SetCustomerBillingAgreement](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/SetCustomerBillingAgreement_API_Operation_NVP/)
-  [SetExpressCheckout](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/SetExpressCheckout_API_Operation_NVP/)
-  [TransactionSearch](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/TransactionSearch_API_Operation_NVP/)
-  [UpdateRecurringPaymentsProfile](https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/UpdateRecurringPaymentsProfile_API_Operation_NVP/)

### Permissions

-  [CancelPermissions](https://developer.paypal.com/webapps/developer/docs/classic/api/permissions/CancelPermissions_API_Operation/)
-  [GetAccessToken](https://developer.paypal.com/webapps/developer/docs/classic/api/permissions/GetAccessToken_API_Operation/)
-  [GetAdvancedPersonalData](https://developer.paypal.com/webapps/developer/docs/classic/api/permissions/GetAdvancedPersonalData_API_Operation/)
-  [GetBasicPersonalData](https://developer.paypal.com/webapps/developer/docs/classic/api/permissions/GetBasicPersonalData_API_Operation/)
-  [GetPermissions](https://developer.paypal.com/webapps/developer/docs/classic/api/permissions/GetPermissions_API_Operation/)
-  [RequestPermissions](https://developer.paypal.com/webapps/developer/docs/classic/api/permissions/RequestPermissions_API_Operation/)

### PayPal Manager (PayFlow Gateway)

-  [PayFlowTransaction](https://www.paypalobjects.com/webstatic/en_US/developer/docs/pdf/payflowgateway_guide.pdf)


### Financing Banners

-  FinancingBannerEnrollment


### Deprecated

-  DoMobileCheckoutPayment
-  GetAccessPermissionsDetails
-  GetAuthDetails
-  SetAccessPermissions
-  SetAuthFlowParam
-  SetMobileCheckout
-  UpdateAccessPermissions


## Resources

-  [Adaptive Accounts Developer Guide](https://developer.paypal.com/webapps/developer/docs/classic/adaptive-accounts/integration-guide/ACIntroduction/)
-  [Adaptive Payments Developer Guide](https://developer.paypal.com/webapps/developer/docs/classic/adaptive-payments/integration-guide/APIntro/)
-  [Express Checkout Integration Guide](https://developer.paypal.com/webapps/developer/docs/classic/express-checkout/integration-guide/ECGettingStarted/)
-  [Invoice Service API Guide](https://developer.paypal.com/webapps/developer/docs/classic/invoicing/IntroInvoiceAPI/)
-  [Mass Payments User Guide](https://developer.paypal.com/webapps/developer/docs/classic/mass-pay/integration-guide/MassPayOverview/)
-  [PayPal Merchant Setup and Administration Guide](https://developer.paypal.com/webapps/developer/docs/classic/admin/)
-  [PayPal Payments Pro Documentation](https://developer.paypal.com/webapps/developer/docs/classic/products/#wpp)
-  [PayPal Recurring Billing / Recurring Payments Guide](https://developer.paypal.com/webapps/developer/docs/classic/products/#recurring)