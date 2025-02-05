<?php

namespace Angelleye\PayPal\Components;

use Angelleye\PayPal\PayPalOrderComponentInterface;

class Item implements PayPalOrderComponentInterface {
    private $name;
    private $quantity;
    private $unitAmount;
    private $sku;
    private $category;
    private $description;

    /**
     * Constructor to initialize Item with required parameters.
     */
    public function __construct($name, $quantity, Amount $unitAmount) {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->unitAmount = $unitAmount;
    }

    /**
     * Set the SKU (optional).
     */
    public function setSku($sku) {
        $this->sku = $sku;
    }

    /**
     * Set the category (optional).
     */
    public function setCategory($category) {
        $this->category = $category;
    }

    /**
     * Set the description (optional).
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Validate required properties.
     */
    public function validate(): void {
        if (empty($this->name) || empty($this->quantity) || !$this->unitAmount) {
            throw new \InvalidArgumentException("Name, Quantity, and Unit Amount are required for Item.");
        }
        $this->unitAmount->validate();
    }

    /**
     * Convert the Item data to an array.
     */
    public function toArray(): array {
        return array_filter([
            'name' => $this->name,
            'quantity' => $this->quantity,
            'unit_amount' => $this->unitAmount->toArray(),
            'sku' => $this->sku,
            'category' => $this->category,
            'description' => $this->description,
        ]);
    }
}

