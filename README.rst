###################
Introduction
###################

This PHP class library for PayPal makes it easy to integrate nearly every PayPal API they provide.

All of the web services included in PayPal's NVP documentation are included, as well as Adaptive Accounts, 
Adaptive Payments, Permissions, Invoicing, PayFlow, and more.

*******************
Server Requirements
*******************

-  PHP version 5.3.0 or newer.

************
Installation
************

--------------
Manual Install
--------------

Place all of the library files on your web server in a directory of your choice.  Then, 
open /includes/config-sample.php, fill in your own config details, and then save this file
as config.php in the same directory.

You may refer to this `overview video <http://www.angelleye.com/overview-of-php-class-library-for-paypal/>`_ of how to use the library, 
and there are also samples provided in the /samples directory.

You may also `contact me directly <http://www.angelleye.com/contact-us/>`_ if you need additional help getting started.  I offer 30 min of free training for using this library, 
which is generally plenty to get you up-and-running.

----------------
Composer Install
----------------

::

    composer require angelleye/paypal-php-library dev-master

----------------
Without Composer
----------------

::

    include_once('paypal-php-library/autoload.php');

***************
Supported API's
***************

-  `AddBankAccount.php`_
-  `AddPaymentCard.php`_
-  `AddressVerify.php`_
-  `BMButtonSearch.php`_
-  `BillAgreementUpdate.php`_
-  `BillOutstandingAmount.php`_
-  `CancelInvoice.php`_
-  `CancelPermissions.php`_
-  `CancelPreapproval.php`_
-  `ConvertCurrency.php`_
-  `CreateAccount.php`_
-  `CreateAndSendInvoice.php`_
-  `CreateBillingAgreement.php`_
-  `CreateInvoice.php`_
-  `CreateRecurringPaymentsProfile.php`_
-  `DoAuthorization.php`_
-  `DoCapture.php`_
-  `DoDirectPayment.php`_
-  `DoExpressCheckoutPayment.php`_
-  `DoMobileCheckoutPayment.php`_
-  `DoNonReferencedCredit.php`_
-  `DoReauthorization.php`_
-  `DoReferenceTransaction.php`_
-  `DoVoid.php`_
-  `ExecutePayment.php`_
-  `ExpressCheckoutCallback.php`_
-  `FinancingBannerEnrollment.php`_
-  `GetAccessPermissionsDetails.php`_
-  `GetAccessToken.php`_
-  `GetAdvancedPersonalData.php`_
-  `GetAuthDetails.php`_
-  `GetBalance.php`_
-  `GetBasicPersonalData.php`_
-  `GetBillingAgreementCustomerDetails.php`_
-  `GetExpressCheckoutDetails.php`_
-  `GetFundingPlans.php`_
-  `GetInvoiceDetails.php`_
-  `GetPalDetails.php`_
-  `GetPaymentOptions.php`_
-  `GetPermissions.php`_
-  `GetRecurringPaymentsProfileDetails.php`_
-  `GetRecurringPaymentsProfileStatus.php`_
-  `GetShippingAddresses.php`_
-  `GetTransactionDetails.php`_
-  `GetVerifiedStatus.php`_
-  `ManagePendingTransactionStatus.php`_
-  `ManageRecurringPaymentsProfileStatus.php`_
-  `MarkInvoiceAsPaid.php`_
-  `MassPay.php`_
-  `Pay.php`_
-  `PayFlowTransaction.php`_
-  `PayWithOptions.php`_
-  `PaymentDetails.php`_
-  `Preapproval.php`_
-  `PreapprovalDetails.php`_
-  `Refund.php`_
-  `RefundTransaction.php`_
-  `RequestPermissions.php`_
-  `SearchInvoices.php`_
-  `SendInvoice.php`_
-  `SetAccessPermissions.php`_
-  `SetAuthFlowParam.php`_
-  `SetCustomerBillingAgreement.php`_
-  `SetExpressCheckout.php`_
-  `SetFundingSourceConfirmed.php`_
-  `SetMobileCheckout.php`_
-  `SetPaymentOptions.php`_
-  `TransactionSearch.php`_
-  `UpdateAccessPermissions.php`_
-  `UpdateInvoice.php`_
-  `UpdateRecurringPaymentsProfile.php`_

*********
Resources
*********

-  `PayPal Name-Value Pair API Developer Guide <https://cms.paypal.com/cms_content/US/en_US/files/developer/PP_NVPAPI_DeveloperGuide.pdf>`_
-  `Adaptive Accounts Developer Guide <https://cms.paypal.com/cms_content/US/en_US/files/developer/PP_AdaptiveAccounts.pdf>`_
-  `Adaptive Payments Developer Guide <https://cms.paypal.com/cms_content/US/en_US/files/developer/PP_AdaptivePayments.pdf>`_
-  `Express Checkout Integration Guide <https://cms.paypal.com/cms_content/US/en_US/files/developer/PP_ExpressCheckout_IntegrationGuide.pdf>`_
-  `Invoice Service API Guide <https://cms.paypal.com/cms_content/US/en_US/files/developer/PP_InvoicingAPIGuide.pdf>`_
-  `Mass Payments User Guide <https://cms.paypal.com/cms_content/US/en_US/files/developer/PP_MassPayment_Guide.pdf>`_
-  `PayPal Merchant Setup and Administration Guide <https://www.x.com/developers/paypal/development-and-integration-guides#msa>`_
-  `PayPal Payments Pro Documentation <https://www.x.com/developers/paypal/development-and-integration-guides#wpp>`_
-  `PayPal Recurring Billing / Recurring Payments Guide <https://www.x.com/developers/paypal/development-and-integration-guides#recurring>`_
