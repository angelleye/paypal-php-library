# PayPal PHP Library

A PHP library for working with the PayPal Orders API.

## 📦 Installation

```bash
composer require angelleye/paypal-php-library
```

## Usage
```php
use Angelleye\PayPal\PayPalClient;
use Angelleye\PayPal\Orders\Order;

// Initialize PayPal Client
$client = new PayPalClient('CLIENT_ID', 'CLIENT_SECRET');

// Create Order
$order = new Order($client);
$response = $order->create();
print_r($response);
```
