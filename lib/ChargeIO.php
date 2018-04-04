<?php

// This snippet (and some of the curl code) due to the Facebook SDK.
if (!function_exists('curl_init')) {
  throw new Exception('ChargeIO needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('ChargeIO needs the JSON PHP extension.');
}
if (!function_exists('mb_detect_encoding')) {
  throw new Exception('ChargeIO needs the Multibyte String PHP extension.');
}

// ChargeIO singleton
require(dirname(__FILE__) . '/ChargeIO/ChargeIO.php');

// Errors
require(dirname(__FILE__) . '/ChargeIO/Error.php');
require(dirname(__FILE__) . '/ChargeIO/ApiError.php');
require(dirname(__FILE__) . '/ChargeIO/AuthenticationError.php');
require(dirname(__FILE__) . '/ChargeIO/InvalidRequestError.php');

// Infrastructure
require(dirname(__FILE__) . '/ChargeIO/Credentials.php');
require(dirname(__FILE__) . '/ChargeIO/Connection.php');
require(dirname(__FILE__) . '/ChargeIO/Utils.php');

// Models
require(dirname(__FILE__) . '/ChargeIO/Object.php');
require(dirname(__FILE__) . '/ChargeIO/Merchant.php');
require(dirname(__FILE__) . '/ChargeIO/MerchantAccount.php');
require(dirname(__FILE__) . '/ChargeIO/AchAccount.php');
require(dirname(__FILE__) . '/ChargeIO/Signature.php');
require(dirname(__FILE__) . '/ChargeIO/Transaction.php');
require(dirname(__FILE__) . '/ChargeIO/Charge.php');
require(dirname(__FILE__) . '/ChargeIO/Refund.php');
require(dirname(__FILE__) . '/ChargeIO/Credit.php');
require(dirname(__FILE__) . '/ChargeIO/RecurringCharge.php');
require(dirname(__FILE__) . '/ChargeIO/RecurringChargeOccurrence.php');
require(dirname(__FILE__) . '/ChargeIO/PaymentMethod.php');
require(dirname(__FILE__) . '/ChargeIO/PaymentMethodReference.php');
require(dirname(__FILE__) . '/ChargeIO/Card.php');
require(dirname(__FILE__) . '/ChargeIO/Bank.php');
require(dirname(__FILE__) . '/ChargeIO/OneTimeToken.php');
require(dirname(__FILE__) . '/ChargeIO/List.php');
require(dirname(__FILE__) . '/ChargeIO/TransactionList.php');
require(dirname(__FILE__) . '/ChargeIO/RecurringChargeList.php');
require(dirname(__FILE__) . '/ChargeIO/RecurringChargeOccurrenceList.php');
require(dirname(__FILE__) . '/ChargeIO/BankList.php');
require(dirname(__FILE__) . '/ChargeIO/CardList.php');
