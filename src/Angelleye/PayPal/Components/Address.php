<?php

namespace Angelleye\PayPal\Orders;

use Angelleye\PayPal\PayPalOrderComponentInterface;

class Address implements PayPalOrderComponentInterface {
    private $addressLine1;
    private $addressLine2;
    private $adminArea1;
    private $adminArea2;
    private $postalCode;
    private $countryCode;

    /**
     * Constructor to initialize Address with required parameters.
     */
    public function __construct($addressLine1, $adminArea1, $postalCode, $countryCode) {
        $this->addressLine1 = $addressLine1;
        $this->adminArea1 = $adminArea1;
        $this->postalCode = $postalCode;
        $this->countryCode = $countryCode;
    }

    /**
     * Set the second address line (optional).
     */
    public function setAddressLine2($addressLine2) {
        $this->addressLine2 = $addressLine2;
    }

    /**
     * Set the second administrative area (optional).
     */
    public function setAdminArea2($adminArea2) {
        $this->adminArea2 = $adminArea2;
    }

    /**
     * Validate required properties.
     */
    public function validate(): void {
        if (empty($this->addressLine1) || empty($this->adminArea1) || empty($this->postalCode) || empty($this->countryCode)) {
            throw new \InvalidArgumentException("Address Line 1, Admin Area 1, Postal Code, and Country Code are required for Address.");
        }
    }

    /**
     * Convert the Address data to an array.
     */
    public function toArray(): array {
        return array_filter([
            'address_line_1' => $this->addressLine1,
            'address_line_2' => $this->addressLine2,
            'admin_area_1' => $this->adminArea1,
            'admin_area_2' => $this->adminArea2,
            'postal_code' => $this->postalCode,
            'country_code' => $this->countryCode,
        ]);
    }
}

?>
