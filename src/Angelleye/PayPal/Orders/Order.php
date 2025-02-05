<?php

namespace Angelleye\PayPal\Orders;

use Angelleye\PayPal\PayPalClient;
use Angelleye\PayPal\Components\PurchaseUnit;
use Angelleye\PayPal\Components\ApplicationContext;
use Angelleye\PayPal\Components\PaymentSource;

class Order {
    private $paypalClient;
    private $intent;
    private $purchaseUnits = [];
    private $applicationContext;
    private $paymentSource;
    private $baseEndpoint = '/v2/checkout/orders';

    /**
     * Constructor to initialize Order with PayPalClient and intent.
     */
    public function __construct(PayPalClient $paypalClient, $intent = 'CAPTURE') {
        $this->paypalClient = $paypalClient;
        $this->intent = $intent;
    }

    /**
     * Add a purchase unit to the order.
     */
    public function addPurchaseUnit(PurchaseUnit $purchaseUnit) {
        $purchaseUnit->validate();
        $this->purchaseUnits[] = $purchaseUnit;
    }

    /**
     * Set the application context using the ApplicationContext object.
     */
    public function setApplicationContext(ApplicationContext $applicationContext) {
        $applicationContext->validate();
        $this->applicationContext = $applicationContext;
    }

    /**
     * Set the payment source using the PaymentSource object.
     */
    public function setPaymentSource(PaymentSource $paymentSource) {
        $paymentSource->validate();
        $this->paymentSource = $paymentSource;
    }

    /**
     * Create Order.
     */
    public function create() {
        $this->validate();
        $orderData = $this->toArray();
        return $this->paypalClient->post($this->baseEndpoint, $orderData);
    }

    /**
     * Show Order Details.
     */
    public function showOrderDetails($orderId) {
        return $this->paypalClient->get("{$this->baseEndpoint}/{$orderId}");
    }

    /**
     * Update Order.
     */
    public function updateOrder($orderId, array $updateRequests) {
        foreach ($updateRequests as $updateRequest) {
            if (!$updateRequest instanceof OrderUpdateRequest) {
                throw new \InvalidArgumentException("All update requests must be instances of OrderUpdateRequest class.");
            }
            $updateRequest->validate();
        }

        $updateData = array_map(fn($updateRequest) => $updateRequest->toArray(), $updateRequests);
        return $this->paypalClient->patch("{$this->baseEndpoint}/{$orderId}", $updateData);
    }

    /**
     * Confirm Payment Source for the Order.
     */
    public function confirmOrder($orderId, PaymentSource $paymentSource) {
        $paymentSource->validate();
        $payload = [
            'payment_source' => $paymentSource->toArray()
        ];
        return $this->paypalClient->post("{$this->baseEndpoint}/{$orderId}/confirm-payment-source", $payload);
    }

    /**
     * Authorize Payment for Order.
     */
    public function authorizePayment($orderId) {
        return $this->paypalClient->post("{$this->baseEndpoint}/{$orderId}/authorize");
    }

    /**
     * Capture Payment for Order.
     */
    public function capturePayment($orderId) {
        return $this->paypalClient->post("{$this->baseEndpoint}/{$orderId}/capture");
    }

    /**
     * Validate required properties.
     */
    public function validate(): void {
        if (empty($this->purchaseUnits)) {
            throw new \InvalidArgumentException("At least one purchase unit is required for the Order.");
        }
    }

    /**
     * Convert the Order data to an array.
     */
    public function toArray(): array {
        return [
            'intent' => $this->intent,
            'purchase_units' => array_map(fn($unit) => $unit->toArray(), $this->purchaseUnits),
            'application_context' => $this->applicationContext ? $this->applicationContext->toArray() : null,
            'payment_source' => $this->paymentSource ? $this->paymentSource->toArray() : null,
        ];
    }
}

?>
