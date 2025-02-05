<?php

namespace Angelleye\PayPal\Components;

use Angelleye\PayPal\PayPalOrderComponentInterface;

class ExperienceContext implements PayPalOrderComponentInterface {
    private $paymentMethodPreference;
    private $locale;
    private $brandName;
    private $shippingPreference;
    private $userAction;
    private $returnUrl;
    private $cancelUrl;

    /**
     * Constructor to initialize ExperienceContext.
     */
    public function __construct($paymentMethodPreference, $locale, $brandName = null, $shippingPreference = null, $userAction = null, $returnUrl = null, $cancelUrl = null) {
        $this->paymentMethodPreference = $paymentMethodPreference;
        $this->locale = $locale;
        $this->brandName = $brandName;
        $this->shippingPreference = $shippingPreference;
        $this->userAction = $userAction;
        $this->returnUrl = $returnUrl;
        $this->cancelUrl = $cancelUrl;
    }

    /**
     * Validate required properties.
     */
    public function validate(): void {
        if (empty($this->paymentMethodPreference) || empty($this->locale)) {
            throw new \InvalidArgumentException("Payment Method Preference and Locale are required for ExperienceContext.");
        }
    }

    /**
     * Convert ExperienceContext data to an array.
     */
    public function toArray(): array {
        return array_filter([
            'payment_method_preference' => $this->paymentMethodPreference,
            'locale' => $this->locale,
            'brand_name' => $this->brandName,
            'shipping_preference' => $this->shippingPreference,
            'user_action' => $this->userAction,
            'return_url' => $this->returnUrl,
            'cancel_url' => $this->cancelUrl
        ]);
    }
}
