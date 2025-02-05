<?php

namespace Angelleye\PayPal\Components;

use Angelleye\PayPal\PayPalOrderComponentInterface;

class Amount implements PayPalOrderComponentInterface {
    private $currencyCode;
    private $value;
    private $breakdown;

    /**
     * Constructor to initialize Amount with currency code and value.
     */
    public function __construct($currencyCode, $value) {
        $this->currencyCode = $currencyCode;
        $this->value = $value;
    }

    /**
     * Set the breakdown details for the amount.
     */
    public function setBreakdown(AmountBreakdown $breakdown) {
        $breakdown->validate();
        $this->breakdown = $breakdown;
    }

    /**
     * Validate required properties.
     */
    public function validate(): void {
        if (empty($this->currencyCode) || empty($this->value)) {
            throw new \InvalidArgumentException("Currency Code and Value are required for Amount.");
        }
    }

    /**
     * Convert the Amount data to an array.
     */
    public function toArray(): array {
        return array_filter([
            'currency_code' => $this->currencyCode,
            'value' => $this->value,
            'breakdown' => $this->breakdown ? $this->breakdown->toArray() : null,
        ]);
    }
}

?>
