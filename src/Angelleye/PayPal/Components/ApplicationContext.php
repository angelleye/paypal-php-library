<?php

namespace Angelleye\PayPal\Components;

use Angelleye\PayPal\PayPalOrderComponentInterface;

class ApplicationContext implements PayPalOrderComponentInterface {
    private $returnUrl;
    private $cancelUrl;
    private $brandName;
    private $locale;
    private $landingPage;
    private $shippingPreference;
    private $userAction;

    /**
     * Constructor to initialize ApplicationContext with required parameters.
     */
    public function __construct($returnUrl, $cancelUrl) {
        $this->returnUrl = $returnUrl;
        $this->cancelUrl = $cancelUrl;
    }

    /**
     * Set the brand name.
     */
    public function setBrandName($brandName) {
        $this->brandName = $brandName;
    }

    /**
     * Set the locale.
     */
    public function setLocale($locale) {
        $this->locale = $locale;
    }

    /**
     * Set the landing page.
     */
    public function setLandingPage($landingPage) {
        $this->landingPage = $landingPage;
    }

    /**
     * Set the shipping preference.
     */
    public function setShippingPreference($shippingPreference) {
        $this->shippingPreference = $shippingPreference;
    }

    /**
     * Set the user action.
     */
    public function setUserAction($userAction) {
        $this->userAction = $userAction;
    }

    /**
     * Validate required properties.
     */
    public function validate(): void {
        if (empty($this->returnUrl) || empty($this->cancelUrl)) {
            throw new \InvalidArgumentException("Both return URL and cancel URL are required for ApplicationContext.");
        }
    }

    /**
     * Convert the ApplicationContext data to an array.
     */
    public function toArray(): array {
        return array_filter([
            'return_url' => $this->returnUrl,
            'cancel_url' => $this->cancelUrl,
            'brand_name' => $this->brandName,
            'locale' => $this->locale,
            'landing_page' => $this->landingPage,
            'shipping_preference' => $this->shippingPreference,
            'user_action' => $this->userAction,
        ]);
    }
}

