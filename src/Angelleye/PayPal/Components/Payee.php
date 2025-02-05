<?php

namespace Angelleye\PayPal\Components;

use Angelleye\PayPal\PayPalOrderComponentInterface;

class Payee implements PayPalOrderComponentInterface {
    private $emailAddress;
    private $merchantId;

    /**
     * Constructor to set the required parameters for Payee.
     */
    public function __construct($emailAddress, $merchantId) {
        $this->emailAddress = $emailAddress;
        $this->merchantId = $merchantId;
    }

    /**
     * Validate required properties.
     */
    public function validate(): void {
        if (empty($this->emailAddress) || empty($this->merchantId)) {
            throw new \InvalidArgumentException("Both email address and merchant ID are required for Payee.");
        }
    }

    /**
     * Convert the Payee data to an array.
     */
    public function toArray(): array {
        return [
            'email_address' => $this->emailAddress,
            'merchant_id' => $this->merchantId
        ];
    }
}
