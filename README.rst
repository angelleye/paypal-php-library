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

-  `AddBankAccount <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-accounts/AddBankAccount_API_Operation/>`_
-  `AddPaymentCard <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-accounts/AddPaymentCard_API_Operation/>`_
-  `AddressVerify <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/AddressVerify_API_Operation_NVP/>`_
-  `BMButtonSearch <https://developer.paypal.com/webapps/developer/docs/classic/api/button-manager/BMButtonSearch_API_Operation_NVP/>`_
-  BillAgreementUpdate
-  `BillOutstandingAmount <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/BillOutstandingAmount_API_Operation_NVP/>`_
-  `CancelInvoice <https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/CancelInvoice_API_Operation/>`_
-  `CancelPermissions <https://developer.paypal.com/webapps/developer/docs/classic/api/permissions/CancelPermissions_API_Operation/>`_
-  `CancelPreapproval <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/CancelPreapproval_API_Operation/>`_
-  `ConvertCurrency <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/ConvertCurrency_API_Operation/>`_
-  `CreateAccount <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-accounts/CreateAccount_API_Operation/>`_
-  `CreateAndSendInvoice <https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/CreateAndSendInvoice_API_Operation/>`_
-  `CreateBillingAgreement <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/CreateBillingAgreement_API_Operation_NVP/>`_
-  `CreateInvoice <https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/CreateInvoice_API_Operation/>`_
-  `CreateRecurringPaymentsProfile <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/CreateRecurringPaymentsProfile_API_Operation_NVP/>`_
-  `DoAuthorization <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoAuthorization_API_Operation_NVP/>`_
-  `DoCapture <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoCapture_API_Operation_NVP/>`_
-  `DoDirectPayment <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoDirectPayment_API_Operation_NVP/>`_
-  `DoExpressCheckoutPayment <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoExpressCheckoutPayment_API_Operation_NVP/>`_
-  DoMobileCheckoutPayment
-  `DoNonReferencedCredit <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoNonReferencedCredit_API_Operation_NVP/>`_
-  `DoReauthorization <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoReauthorization_API_Operation_NVP/>`_
-  `DoReferenceTransaction <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoReferenceTransaction_API_Operation_NVP/>`_
-  `DoVoid <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoVoid_API_Operation_NVP/>`_
-  `ExecutePayment <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/ExecutePayment_API_Operation/>`_
-  `ExpressCheckoutCallback <https://developer.paypal.com/docs/classic/express-checkout/integration-guide/ECInstantUpdateAPI/>`_
-  FinancingBannerEnrollment
-  GetAccessPermissionsDetails
-  `GetAccessToken <https://developer.paypal.com/webapps/developer/docs/classic/api/permissions/GetAccessToken_API_Operation/>`_
-  `GetAdvancedPersonalData <https://developer.paypal.com/webapps/developer/docs/classic/api/permissions/GetAdvancedPersonalData_API_Operation/>`_
-  GetAuthDetails
-  `GetBalance <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetBalance_API_Operation_NVP/>`_
-  `GetBasicPersonalData <https://developer.paypal.com/webapps/developer/docs/classic/api/permissions/GetBasicPersonalData_API_Operation/>`_
-  `GetBillingAgreementCustomerDetails <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetBillingAgreementCustomerDetails_API_Operation_NVP/>`_
-  `GetExpressCheckoutDetails <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetExpressCheckoutDetails_API_Operation_NVP/>`_
-  `GetFundingPlans <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/GetFundingPlans_API_Operation/>`_
-  `GetInvoiceDetails <https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/GetInvoiceDetails_API_Operation/>`_
-  `GetPalDetails <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetPalDetails_API_Operation_NVP/>`_
-  `GetPaymentOptions <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/GetPaymentOptions_API_Operation/>`_
-  `GetPermissions <https://developer.paypal.com/webapps/developer/docs/classic/api/permissions/GetPermissions_API_Operation/>`_
-  `GetRecurringPaymentsProfileDetails <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetRecurringPaymentsProfileDetails_API_Operation_NVP/>`_
-  GetRecurringPaymentsProfileStatus
-  `GetShippingAddresses <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/GetShippingAddresses_API_Operation/>`_
-  `GetTransactionDetails <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetTransactionDetails_API_Operation_NVP/>`_
-  `GetVerifiedStatus <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-accounts/GetVerifiedStatus_API_Operation/>`_
-  `ManagePendingTransactionStatus <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/ManagePendingTransactionStatus_API_Operation_NVP/>`_
-  `ManageRecurringPaymentsProfileStatus <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/ManageRecurringPaymentsProfileStatus_API_Operation_NVP/>`_
-  `MarkInvoiceAsPaid <https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/MarkInvoiceAsPaid_API_Operation/>`_
-  `MassPay <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/MassPay_API_Operation_NVP/>`_
-  `Pay <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/Pay_API_Operation/>`_
-  `PayFlowTransaction <https://www.paypalobjects.com/webstatic/en_US/developer/docs/pdf/payflowgateway_guide.pdf>`_
-  PayWithOptions
-  `PaymentDetails <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/PaymentDetails_API_Operation/>`_
-  `Preapproval <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/Preapproval_API_Operation/>`_
-  `PreapprovalDetails <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/PreapprovalDetails_API_Operation/>`_
-  `Refund <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/Refund_API_Operation/>`_
-  `RefundTransaction <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/RefundTransaction_API_Operation_NVP/>`_
-  `RequestPermissions <https://developer.paypal.com/webapps/developer/docs/classic/api/permissions/RequestPermissions_API_Operation/>`_
-  `SearchInvoices <https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/SearchInvoices_API_Operation/>`_
-  `SendInvoice <https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/SendInvoice_API_Operation/>`_
-  SetAccessPermissions
-  SetAuthFlowParam
-  `SetCustomerBillingAgreement <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/SetCustomerBillingAgreement_API_Operation_NVP/>`_
-  `SetExpressCheckout <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/SetExpressCheckout_API_Operation_NVP/>`_
-  `SetFundingSourceConfirmed <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-accounts/SetFundingSourceConfirmed_API_Operation/>`_
-  SetMobileCheckout
-  `SetPaymentOptions <https://developer.paypal.com/webapps/developer/docs/classic/api/adaptive-payments/SetPaymentOptions_API_Operation/>`_
-  `TransactionSearch <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/TransactionSearch_API_Operation_NVP/>`_
-  UpdateAccessPermissions
-  `UpdateInvoice <https://developer.paypal.com/webapps/developer/docs/classic/api/invoicing/UpdateInvoice_API_Operation/>`_
-  `UpdateRecurringPaymentsProfile <https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/UpdateRecurringPaymentsProfile_API_Operation_NVP/>`_

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
