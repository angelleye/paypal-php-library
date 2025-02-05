<?php

namespace Angelleye\PayPal\Components;

use Angelleye\PayPal\PayPalOrderComponentInterface;

class PurchaseUnit implements PayPalOrderComponentInterface {
    private $referenceId;
    private $description;
    private $amount;
    private $payee;
    private $shipping;
    private $items = [];
    private $customId;
    private $invoiceId;
    private $softDescriptor;

    /**
     * Constructor to initialize PurchaseUnit with required reference ID and amount.
     */
    public function __construct($referenceId, Amount $amount) {
        $this->referenceId = $referenceId;
        $this->amount = $amount;
    }

    /**
     * Set description for the purchase unit.
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Set payee details using the Payee object.
     */
    public function setPayee(Payee $payee) {
        $payee->validate();
        $this->payee = $payee;
    }

    /**
     * Set shipping details using the Shipping object.
     */
    public function setShipping(Shipping $shipping) {
        $shipping->validate();
        $this->shipping = $shipping;
    }

    /**
     * Add items to the purchase unit.
     */
    public function addItem(Item $item) {
        $item->validate();
        $this->items[] = $item;
    }

    /**
     * Set custom ID.
     */
    public function setCustomId($customId) {
        $this->customId = $customId;
    }

    /**
     * Set invoice ID.
     */
    public function setInvoiceId($invoiceId) {
        $this->invoiceId = $invoiceId;
    }

    /**
     * Set soft descriptor.
     */
    public function setSoftDescriptor($softDescriptor) {
        $this->softDescriptor = $softDescriptor;
    }

    /**
     * Validate required properties.
     */
    public function validate(): void {
        if (empty($this->referenceId) || !$this->amount) {
            throw new \InvalidArgumentException("Reference ID and amount are required for PurchaseUnit.");
        }
    }

    /**
     * Convert PurchaseUnit data to an array.
     */
    public function toArray(): array {
        return [
            'reference_id' => $this->referenceId,
            'description' => $this->description,
            'amount' => $this->amount->toArray(),
            'payee' => $this->payee ? $this->payee->toArray() : null,
            'shipping' => $this->shipping ? $this->shipping->toArray() : null,
            'items' => array_map(fn($item) => $item->toArray(), $this->items),
            'custom_id' => $this->customId,
            'invoice_id' => $this->invoiceId,
            'soft_descriptor' => $this->softDescriptor,
        ];
    }
}

