<?php

namespace Angelleye\PayPal\PaymentMethods;

use Angelleye\PayPal\PayPalOrderComponentInterface;

class CardPayment implements PayPalOrderComponentInterface {
    private $number;
    private $expiry;
    private $securityCode;
    private $name;
    private $billingAddress;

    /**
     * Constructor to initialize Card payment source.
     */
    public function __construct($number, $expiry, $securityCode, $name, Address $billingAddress) {
        $this->number = $number;
        $this->expiry = $expiry;
        $this->securityCode = $securityCode;
        $this->name = $name;
        $this->billingAddress = $billingAddress;
    }

    /**
     * Validate required properties.
     */
    public function validate(): void {
        if (empty($this->number) || empty($this->expiry) || empty($this->securityCode) || empty($this->name) || !$this->billingAddress) {
            throw new \InvalidArgumentException("Card number, expiry, security code, cardholder name, and billing address are required for Card payment.");
        }
        $this->billingAddress->validate();
    }

    /**
     * Convert Card payment source data to an array.
     */
    public function toArray(): array {
        return [
            'number' => $this->number,
            'expiry' => $this->expiry,
            'security_code' => $this->securityCode,
            'name' => $this->name,
            'billing_address' => $this->billingAddress->toArray()
        ];
    }
}
