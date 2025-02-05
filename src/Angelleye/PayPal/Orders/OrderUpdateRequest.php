<?php

namespace Angelleye\PayPal\Orders;

use Angelleye\PayPal\PayPalOrderComponentInterface;

class OrderUpdateRequest implements PayPalOrderComponentInterface {
    private $operation;
    private $path;
    private $value;

    /**
     * Constructor to initialize the update request.
     */
    public function __construct(string $operation, string $path, $value) {
        $this->operation = $operation;
        $this->path = $path;
        $this->value = $value;
    }

    /**
     * Validate required properties.
     */
    public function validate(): void {
        if (empty($this->operation) || empty($this->path)) {
            throw new \InvalidArgumentException("Operation and path are required for OrderUpdateRequest.");
        }
    }

    /**
     * Convert the update request data to an array.
     */
    public function toArray(): array {
        return [
            'op' => $this->operation,
            'path' => $this->path,
            'value' => $this->value
        ];
    }
}

?>
