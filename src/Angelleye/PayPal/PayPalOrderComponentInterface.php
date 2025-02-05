<?php

namespace Angelleye\PayPal;

/**
 * Interface to enforce required methods for Order components
 */
interface PayPalOrderComponentInterface {
    /**
     * Validate required properties.
     */
    public function validate(): void;

    /**
     * Convert the object to an array representation.
     */
    public function toArray(): array;
}

?>
