<?php
$payhereUrl = $data['payhere_url'];
$merchantId = $data['merchant_id'];
$orderId    = $data['order_id'];
$amount     = $data['amount'];
$currency   = $data['currency'];
$hash       = $data['hash'];
$returnUrl  = $data['return_url'];
$cancelUrl  = $data['cancel_url'];
$notifyUrl  = $data['notify_url'];
$cart       = $data['cart'];
?>

<style>
.checkout-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 50vh;
    gap: 1.5rem;
    color: #6b7280;
}
.spinner {
    width: 50px; height: 50px;
    border: 4px solid #e5e7eb;
    border-top-color: #fe9630;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
.checkout-summary {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.5rem 2rem;
    max-width: 400px;
    width: 100%;
    text-align: center;
    margin-bottom: 1rem;
}
.checkout-summary h3 { margin: 0 0 0.5rem; font-size: 1.1rem; }
.checkout-amount { font-size: 1.8rem; font-weight: 700; color: #fe9630; }
.checkout-id { font-size: 0.8rem; color: #9ca3af; margin-top: 0.5rem; }
</style>

<div class="checkout-loading">
    <div class="checkout-summary">
        <h3>Redirecting to PayHere...</h3>
        <div class="checkout-amount">Rs. <?php echo number_format((float)$amount, 2); ?></div>
        <p class="checkout-id">Order #<?php echo htmlspecialchars($orderId); ?></p>
    </div>
    <div class="spinner"></div>
    <p>Please wait, you are being redirected to the payment gateway.</p>
</div>

<!-- Hidden PayHere form — auto-submits -->
<form id="payhereForm" method="POST" action="<?php echo $payhereUrl; ?>" style="display:none;">
    <input type="hidden" name="merchant_id"  value="<?php echo htmlspecialchars($merchantId); ?>">
    <input type="hidden" name="return_url"   value="<?php echo htmlspecialchars($returnUrl); ?>">
    <input type="hidden" name="cancel_url"   value="<?php echo htmlspecialchars($cancelUrl); ?>">
    <input type="hidden" name="notify_url"   value="<?php echo htmlspecialchars($notifyUrl); ?>">
    <input type="hidden" name="order_id"     value="<?php echo htmlspecialchars($orderId); ?>">
    <input type="hidden" name="items"        value="SolarSense Order <?php echo htmlspecialchars($orderId); ?>">
    <input type="hidden" name="currency"     value="<?php echo htmlspecialchars($currency); ?>">
    <input type="hidden" name="amount"       value="<?php echo htmlspecialchars($amount); ?>">
    <input type="hidden" name="hash"         value="<?php echo htmlspecialchars($hash); ?>">
    <!-- Customer fields (can be filled from session when auth is added) -->
    <input type="hidden" name="first_name"   value="Customer">
    <input type="hidden" name="last_name"    value="">
    <input type="hidden" name="email"        value="customer@solarsense.lk">
    <input type="hidden" name="phone"        value="0771234567">
    <input type="hidden" name="address"      value="Colombo">
    <input type="hidden" name="city"         value="Colombo">
    <input type="hidden" name="country"      value="Sri Lanka">
</form>

<script>
    // Clear the cart after successful redirect initiation
    localStorage.removeItem('ss_cart');
    // Auto-submit after short delay so user sees the loading screen
    setTimeout(() => document.getElementById('payhereForm').submit(), 1500);
</script>
