<?php

namespace Angelleye\PayPal\Components;

use Angelleye\PayPal\PayPalOrderComponentInterface;

class AmountBreakdown implements PayPalOrderComponentInterface {
    private $itemTotal;
    private $shipping;
    private $handling;
    private $taxTotal;
    private $insurance;
    private $shippingDiscount;
    private $discount;

    /**
     * Set item total.
     */
    public function setItemTotal(Amount $itemTotal) {
        $itemTotal->validate();
        $this->itemTotal = $itemTotal;
    }

    /**
     * Set shipping amount.
     */
    public function setShipping(Amount $shipping) {
        $shipping->validate();
        $this->shipping = $shipping;
    }

    /**
     * Set handling amount.
     */
    public function setHandling(Amount $handling) {
        $handling->validate();
        $this->handling = $handling;
    }

    /**
     * Set tax total.
     */
    public function setTaxTotal(Amount $taxTotal) {
        $taxTotal->validate();
        $this->taxTotal = $taxTotal;
    }

    /**
     * Set insurance amount.
     */
    public function setInsurance(Amount $insurance) {
        $insurance->validate();
        $this->insurance = $insurance;
    }

    /**
     * Set shipping discount.
     */
    public function setShippingDiscount(Amount $shippingDiscount) {
        $shippingDiscount->validate();
        $this->shippingDiscount = $shippingDiscount;
    }

    /**
     * Set discount amount.
     */
    public function setDiscount(Amount $discount) {
        $discount->validate();
        $this->discount = $discount;
    }

    /**
     * Validate required properties.
     */
    public function validate(): void {
        if (!$this->itemTotal) {
            throw new \InvalidArgumentException("Item Total is required for Amount Breakdown.");
        }
    }

    /**
     * Convert the AmountBreakdown data to an array.
     */
    public function toArray(): array {
        return array_filter([
            'item_total' => $this->itemTotal ? $this->itemTotal->toArray() : null,
            'shipping' => $this->shipping ? $this->shipping->toArray() : null,
            'handling' => $this->handling ? $this->handling->toArray() : null,
            'tax_total' => $this->taxTotal ? $this->taxTotal->toArray() : null,
            'insurance' => $this->insurance ? $this->insurance->toArray() : null,
            'shipping_discount' => $this->shippingDiscount ? $this->shippingDiscount->toArray() : null,
            'discount' => $this->discount ? $this->discount->toArray() : null,
        ]);
    }
}
