<?php

namespace Angelleye\PayPal\Components;

use Angelleye\PayPal\PayPalOrderComponentInterface;

class PaymentSource implements PayPalOrderComponentInterface {
    private $paypal;
    private $card;
    private $venmo;

    /**
     * Set PayPal payment source details.
     */
    public function setPayPal(PayPalPayment $paypal) {
        $paypal->validate();
        $this->paypal = $paypal;
    }

    /**
     * Set Card payment source details.
     */
    public function setCard(CardPayment $card) {
        $card->validate();
        $this->card = $card;
    }

    /**
     * Set Venmo payment source details.
     */
    public function setVenmo(VenmoPayment $venmo) {
        $venmo->validate();
        $this->venmo = $venmo;
    }

    /**
     * Validate that at least one payment source is provided.
     */
    public function validate(): void {
        if (empty($this->paypal) && empty($this->card) && empty($this->venmo)) {
            throw new \InvalidArgumentException("At least one payment source (PayPal, Card, Venmo) must be set.");
        }
    }

    /**
     * Convert payment source data to an array.
     */
    public function toArray(): array {
        return array_filter([
            'paypal' => $this->paypal ? $this->paypal->toArray() : null,
            'card' => $this->card ? $this->card->toArray() : null,
            'venmo' => $this->venmo ? $this->venmo->toArray() : null,
        ]);
    }
}

?>
