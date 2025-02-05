<?php

namespace Angelleye\PayPal\Components;

use Angelleye\PayPal\PayPalOrderComponentInterface;

class Shipping implements PayPalOrderComponentInterface {
    private $name;
    private $address;

    /**
     * Constructor to initialize Shipping with name and address.
     */
    public function __construct($name, Address $address) {
        $this->name = $name;
        $this->address = $address;
    }

    /**
     * Validate required properties.
     */
    public function validate(): void {
        if (empty($this->name) || !$this->address) {
            throw new \InvalidArgumentException("Both name and address are required for Shipping.");
        }
        $this->address->validate();
    }

    /**
     * Convert the Shipping data to an array.
     */
    public function toArray(): array {
        return [
            'name' => $this->name,
            'address' => $this->address->toArray()
        ];
    }
}

?>
