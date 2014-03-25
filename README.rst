###################
Introduction
###################

This PHP class library for PayPal makes it easy to integrate PayPal APIs, including `Adaptive Accounts <https://developer.paypal.com/webapps/developer/docs/classic/api/#aa>`_, 
`Adaptive Payments <https://developer.paypal.com/webapps/developer/docs/classic/api/#ap>`_, `Invoicing <https://developer.paypal.com/webapps/developer/docs/classic/api/#invoicing>`_, 
`General Merchant APIs <https://developer.paypal.com/webapps/developer/docs/classic/api/#merchant>`_, and `Permissions <https://developer.paypal.com/webapps/developer/docs/classic/api/#permissions>`_.

*******************
Server Requirements
*******************

-  PHP version 5.3.0 or newer.

************
Installation
************

----------------
Composer Install
----------------

Create a composer.json file with the following section and run composer update.

::

    "require": {
		"php": ">=5.3.0",
		"ext-curl": "*",
		"angelleye/paypal-php-library": "2.0.*"
	}

---------------------------------
Manual Install (without Composer)
---------------------------------

- `Download <https://github.com/angelleye/paypal-php-library/archive/master.zip>`_ the class library and extract the contents do a directory in your project structure. 
- Upload the files to your web server.

*****
Setup
*****

Open /includes/config-sample.php, fill out your details accordingly, and save-as config.php.

To use the library in your project, include the following into your file(s).

- /includes/config.php (It is recommended that you move this to a directory outside your site root on the web server and use an absolute path to include it.)
- autoload.php

*****
Usage
*****

- Open the template file that corresponds to the API call you'd like to make.
    * Example: If we want to make a call to the RefundTransaction API we open up /templates/RefundTransaction.php
- You may leave the file here, or save this file to the location on your web server where you'd like this call to be made.
    * I like to save the files to a separate location and keep the ones included with the library as empty templates.
	* Note that you can also copy/paste the template code into your own file(s).
- Each template file includes PHP arrays for every parameter available to that particular API. Simply fill in the array parameters with your own dynamic (or static) data. This data may come from:
    * Session Variables
	* General Variables
	* Database Recordsets
	* Static Values
	* Etc.
- When you run the file you will get a $PayPalResult array that consists of all the response parameters from PayPal, original request parameters sent to PayPal, and raw request/response info for troubleshooting.
    * You may refer to the `PayPal API Reference Guide <https://developer.paypal.com/webapps/developer/docs/classic/api/>`_ for details about what response parameters you can expect to get back from any successful API request.
        + Example: When working with RefundTransaction, I can see that PayPal will return a REFUNDTRANSACTIONID, FEEREFUNDAMT, etc. As such, I know that those values will be included in $PayPalResult['REFUNDTRANSACTIONID'] and $PayPalResult['FEEREFUNDAMT'] respectively.
- If errors occur they will be available in $PayPalResult['ERRORS']

You may refer to this `overview video <http://www.angelleye.com/overview-of-php-class-library-for-paypal/>`_ of how to use the library, 
and there are also samples provided in the /samples directory as well as blank templates ready to use under /templates.

You may `contact me directly <http://www.angelleye.com/contact-us/>`_ if you need additional help getting started.  I offer 30 min of free training for using this library, 
which is generally plenty to get you up-and-running.

***************
Supported APIs
***************

-----------------
Adaptive Accounts
-----------------

-  `AddBankAccount <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-accounts/AddBankAccount_API_Operation/>`_
-  `AddPaymentCard <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-accounts/AddPaymentCard_API_Operation/>`_
-  `CreateAccount <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-accounts/CreateAccount_API_Operation/>`_
-  `GetVerifiedStatus <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-accounts/GetVerifiedStatus_API_Operation/>`_
-  `SetFundingSourceConfirmed <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-accounts/SetFundingSourceConfirmed_API_Operation/>`_

-----------------
Adaptive Payments
-----------------

-  `CancelPreapproval <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/CancelPreapproval_API_Operation/>`_
-  `ConvertCurrency <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/ConvertCurrency_API_Operation/>`_
-  `ExecutePayment <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/ExecutePayment_API_Operation/>`_
-  `GetFundingPlans <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/GetFundingPlans_API_Operation/>`_
-  `GetPaymentOptions <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/GetPaymentOptions_API_Operation/>`_
-  `GetShippingAddresses <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/GetShippingAddresses_API_Operation/>`_
-  `Pay <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/Pay_API_Operation/>`_
-  PayWithOptions
-  `PaymentDetails <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/PaymentDetails_API_Operation/>`_
-  `Preapproval <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/Preapproval_API_Operation/>`_
-  `PreapprovalDetails <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/PreapprovalDetails_API_Operation/>`_
-  `Refund <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/Refund_API_Operation/>`_
-  `SetPaymentOptions <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/SetPaymentOptions_API_Operation/>`_

--------------
Button Manager
--------------

-  `BMButtonSearch <https://developer.paypal.com/webapps/developer/docs/classic/api/button-manager/BMButtonSearch_API_Operation_NVP/>`_

---------
Invoicing
---------

-  `CancelInvoice <https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/CancelInvoice_API_Operation/>`_
-  `CreateAndSendInvoice <https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/CreateAndSendInvoice_API_Operation/>`_
-  `CreateInvoice <https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/CreateInvoice_API_Operation/>`_
-  `GetInvoiceDetails <https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/GetInvoiceDetails_API_Operation/>`_
-  `MarkInvoiceAsPaid <https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/MarkInvoiceAsPaid_API_Operation/>`_
-  `SearchInvoices <https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/SearchInvoices_API_Operation/>`_
-  `SendInvoice <https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/SendInvoice_API_Operation/>`_
-  `UpdateInvoice <https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/UpdateInvoice_API_Operation/>`_

--------
Merchant
--------

-  `AddressVerify <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/AddressVerify_API_Operation_NVP/>`_
-  `BAUpdate <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/BAUpdate_API_Operation_NVP/>`_
-  `BillOutstandingAmount <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/BillOutstandingAmount_API_Operation_NVP/>`_
-  `Callback (Express Checkout) <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/Callback_API_Operation_NVP/>`_
-  `CreateBillingAgreement <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/CreateBillingAgreement_API_Operation_NVP/>`_
-  `CreateRecurringPaymentsProfile <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/CreateRecurringPaymentsProfile_API_Operation_NVP/>`_
-  `DoAuthorization <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoAuthorization_API_Operation_NVP/>`_
-  `DoCapture <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoCapture_API_Operation_NVP/>`_
-  `DoDirectPayment <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoDirectPayment_API_Operation_NVP/>`_
-  `DoExpressCheckoutPayment <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoExpressCheckoutPayment_API_Operation_NVP/>`_
-  `DoNonReferencedCredit <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoNonReferencedCredit_API_Operation_NVP/>`_
-  `DoReauthorization <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoReauthorization_API_Operation_NVP/>`_
-  `DoReferenceTransaction <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoReferenceTransaction_API_Operation_NVP/>`_
-  `DoVoid <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoVoid_API_Operation_NVP/>`_
-  `GetBalance <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetBalance_API_Operation_NVP/>`_
-  `GetBillingAgreementCustomerDetails <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetBillingAgreementCustomerDetails_API_Operation_NVP/>`_
-  `GetExpressCheckoutDetails <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetExpressCheckoutDetails_API_Operation_NVP/>`_
-  `GetPalDetails <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetPalDetails_API_Operation_NVP/>`_
-  `GetRecurringPaymentsProfileDetails <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetRecurringPaymentsProfileDetails_API_Operation_NVP/>`_
-  GetRecurringPaymentsProfileStatus
-  `GetTransactionDetails <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetTransactionDetails_API_Operation_NVP/>`_
-  `ManagePendingTransactionStatus <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/ManagePendingTransactionStatus_API_Operation_NVP/>`_
-  `ManageRecurringPaymentsProfileStatus <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/ManageRecurringPaymentsProfileStatus_API_Operation_NVP/>`_
-  `MassPay <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/MassPay_API_Operation_NVP/>`_
-  `RefundTransaction <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/RefundTransaction_API_Operation_NVP/>`_
-  `SetCustomerBillingAgreement <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/SetCustomerBillingAgreement_API_Operation_NVP/>`_
-  `SetExpressCheckout <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/SetExpressCheckout_API_Operation_NVP/>`_
-  `TransactionSearch <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/TransactionSearch_API_Operation_NVP/>`_
-  `UpdateRecurringPaymentsProfile <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/UpdateRecurringPaymentsProfile_API_Operation_NVP/>`_

-----------
Permissions
-----------

-  `CancelPermissions <https://developer.paypal.com/webapps/developer/docs/classic/api/permissions/CancelPermissions_API_Operation/>`_
-  `GetAccessToken <https://developer.paypal.com/webapps/developer/docs/classic/api/permissions/GetAccessToken_API_Operation/>`_
-  `GetAdvancedPersonalData <https://developer.paypal.com/webapps/developer/docs/classic/api/permissions/GetAdvancedPersonalData_API_Operation/>`_
-  `GetBasicPersonalData <https://developer.paypal.com/webapps/developer/docs/classic/api/permissions/GetBasicPersonalData_API_Operation/>`_
-  `GetPermissions <https://developer.paypal.com/webapps/developer/docs/classic/api/permissions/GetPermissions_API_Operation/>`_
-  `RequestPermissions <https://developer.paypal.com/webapps/developer/docs/classic/api/permissions/RequestPermissions_API_Operation/>`_

------------------------
PayPal Manager (PayFlow)
------------------------

-  `PayFlowTransaction <https://www.paypalobjects.com/webstatic/en_US/developer/docs/pdf/payflowgateway_guide.pdf>`_


-----------------
Financing Banners
-----------------

-  FinancingBannerEnrollment


----------
Deprecated
----------

-  DoMobileCheckoutPayment
-  GetAccessPermissionsDetails
-  GetAuthDetails
-  SetAccessPermissions
-  SetAuthFlowParam
-  SetMobileCheckout
-  UpdateAccessPermissions

*********
Resources
*********

-  `Adaptive Accounts Developer Guide <https://developer.paypal.com/webapps/developer/docs/classic/adaptive-accounts/integration-guide/ACIntroduction/>`_
-  `Adaptive Payments Developer Guide <https://developer.paypal.com/webapps/developer/docs/classic/adaptive-payments/integration-guide/APIntro/>`_
-  `Express Checkout Integration Guide <https://developer.paypal.com/webapps/developer/docs/classic/express-checkout/integration-guide/ECGettingStarted/>`_
-  `Invoice Service API Guide <https://developer.paypal.com/webapps/developer/docs/classic/invoicing/IntroInvoiceAPI/>`_
-  `Mass Payments User Guide <https://developer.paypal.com/webapps/developer/docs/classic/mass-pay/integration-guide/MassPayOverview/>`_
-  `PayPal Merchant Setup and Administration Guide <https://developer.paypal.com/webapps/developer/docs/classic/admin/>`_
-  `PayPal Payments Pro Documentation <https://developer.paypal.com/webapps/developer/docs/classic/products/#wpp>`_
-  `PayPal Recurring Billing / Recurring Payments Guide <https://developer.paypal.com/webapps/developer/docs/classic/products/#recurring>`_