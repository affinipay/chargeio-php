chargeio-php
============

PHP Client Library for the AffiniPay Payment Gateway

## Installation

Download the PHP client library:

    git clone git://github.com/charge-io/chargeio-php.git

To use the library in your application, add the following to your PHP script:

    require_once '/path/to/chargeio-php/lib/ChargeIO.php';

### Set up ChargeIO credentials
The library's APIs require credentials to access your merchant data on the AffiniPay servers. You
can either:

- Provide credentials as arguments to each API call.
- Configure the library with default credentials.

  To set default credentials, call ChargeIO::setCredentials with an AffiniPay_Credentials object. The AffiniPay_Credentials object is instantiated with a public_key and a secret_key.

    ChargeIO::setCredentials(new AffiniPay_Credentials('<public_key>', '<secret_key>'));

### Using AffiniPay hosted fields to create a charge token
You must tokenize all sensitive payment information before you submit it to AffiniPay. On your
payment form, use AffiniPay’s hosted fields to secure payment data and call
window.AffiniPay.HostedFields.getPaymentToken to create a one-time payment token. See
["Creating payment forms using hosted fields"](https://developers.affinipay.com/collect/create-payment-form-hosted-fields.html). Then, POST the payment token ID to your PHP script.

### Making a charge
Pass an amount and the one-time token ID returned from your payment page to complete a charge.

```
    $amount = $_POST['amount'];
    $token_id = $_POST['token_id'];
    $charge = ChargeIO_Charge::create(new ChargeIO_PaymentMethodReference(array('id' => $token_id)), $amount);
```

## Documentation

The latest AffiniPay Payment Gateway API documentation is available at https://developers.affinipay.com/reference/api.html#PaymentGatewayAPI.
