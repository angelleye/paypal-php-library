<?php

namespace Angelleye\PayPal\PaymentMethods;

use Angelleye\PayPal\PayPalOrderComponentInterface;

class PayPalPayment implements PayPalOrderComponentInterface {
    private $emailAddress;
    private $billingAgreementId;
    private $vaultId;
    private $experienceContext;

    /**
     * Constructor to initialize PayPal payment source.
     */
    public function __construct($emailAddress = null, $billingAgreementId = null, $vaultId = null, ExperienceContext $experienceContext = null) {
        $this->emailAddress = $emailAddress;
        $this->billingAgreementId = $billingAgreementId;
        $this->vaultId = $vaultId;
        $this->experienceContext = $experienceContext;
    }

    /**
     * Validate required properties.
     */
    public function validate(): void {
        if (empty($this->emailAddress) && empty($this->billingAgreementId) && empty($this->vaultId)) {
            throw new \InvalidArgumentException("At least one of Email Address, Billing Agreement ID, or Vault ID is required for PayPal payment.");
        }

        if ($this->experienceContext) {
            $this->experienceContext->validate();
        }
    }

    /**
     * Convert PayPal payment source data to an array.
     */
    public function toArray(): array {
        return array_filter([
            'email_address' => $this->emailAddress,
            'billing_agreement_id' => $this->billingAgreementId,
            'vault_id' => $this->vaultId,
            'experience_context' => $this->experienceContext ? $this->experienceContext->toArray() : null
        ]);
    }
}

?>
