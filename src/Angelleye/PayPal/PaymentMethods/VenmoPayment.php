<?php

namespace Angelleye\PayPal\PaymentMethods;

use Angelleye\PayPal\PayPalOrderComponentInterface;

class VenmoPayment implements PayPalOrderComponentInterface {
    private $username;

    /**
     * Constructor to initialize Venmo payment source.
     */
    public function __construct($username) {
        $this->username = $username;
    }

    /**
     * Validate required properties.
     */
    public function validate(): void {
        if (empty($this->username)) {
            throw new \InvalidArgumentException("Username is required for Venmo payment.");
        }
    }

    /**
     * Convert Venmo payment source data to an array.
     */
    public function toArray(): array {
        return [
            'username' => $this->username
        ];
    }
}

?>
