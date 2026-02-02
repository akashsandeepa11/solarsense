<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/homeowner/shop.css">

<?php
$config = [
    'title' => 'Checkout',
    'description' => 'Complete your purchase securely with PayHere',
    'show_back' => true,
    'back_url' => URLROOT . '/homeowner/shop/cart',
    'back_label' => 'Back to Cart'
];
include __DIR__ . '/../../inc/components/page_header.php';

require_once APPROOT . '/helpers/PayHere.php';

$cartItems = $data['cartItems'] ?? [];
$subtotal = $data['subtotal'] ?? 0;
$itemCount = $data['itemCount'] ?? 0;
$orderId = $data['orderId'] ?? '';
$payhereUrl = $data['payhereUrl'] ?? '';
$itemNames = implode(', ', array_column($cartItems, 'title'));
?>

<div class="shop-container">
    <div class="checkout-grid">
        <!-- Customer Details Form -->
        <div class="checkout-form-section">
            <div class="card">
                <div class="card-header">
                    <h3 class="checkout-section-title"><i class="fa-solid fa-user"></i> Customer Information</h3>
                </div>
                <div class="card-body">
                    <form id="checkoutForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="first_name">First Name *</label>
                                <input type="text" id="first_name" name="first_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="last_name">Last Name *</label>
                                <input type="text" id="last_name" name="last_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="email">Email *</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="phone">Phone *</label>
                                <input type="tel" id="phone" name="phone" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="address">Address *</label>
                            <input type="text" id="address" name="address" class="form-control" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="city">City *</label>
                                <input type="text" id="city" name="city" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="country">Country</label>
                                <input type="text" id="country" class="form-control" value="Sri Lanka" readonly>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary & Payment -->
        <div class="checkout-summary-section">
            <div class="card">
                <div class="card-header">
                    <h3 class="checkout-section-title"><i class="fa-solid fa-receipt"></i> Order Summary</h3>
                </div>
                <div class="card-body">
                    <div class="order-items">
                        <?php foreach($cartItems as $item): ?>
                        <div class="order-item">
                            <div class="order-item-info">
                                <span class="order-item-name"><?php echo htmlspecialchars($item['title']); ?></span>
                                <span class="order-item-qty">× <?php echo $item['quantity']; ?></span>
                            </div>
                            <span class="order-item-price">Rs. <?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="order-divider"></div>
                    <div class="order-totals">
                        <div class="order-total-row"><span>Subtotal (<?php echo $itemCount; ?> items)</span><span>Rs. <?php echo number_format($subtotal, 2); ?></span></div>
                        <div class="order-total-row"><span>Shipping</span><span class="text-success">Free</span></div>
                        <div class="order-divider"></div>
                        <div class="order-total-row order-grand-total"><span>Total</span><span>Rs. <?php echo number_format($subtotal, 2); ?></span></div>
                    </div>
                </div>
            </div>

            <!-- PayHere Form -->
            <div class="card payment-card">
                <div class="card-header">
                    <h3 class="checkout-section-title"><i class="fa-solid fa-credit-card"></i> Payment</h3>
                </div>
                <div class="card-body">
                    <div class="payment-badge"><i class="fa-solid fa-shield-halved"></i> Secure Payment via PayHere</div>
                    <p class="payment-description">Pay with Credit/Debit Cards, Online Banking, or Mobile Wallets.</p>
                    
                    <form id="payhereForm" method="post" action="<?php echo $payhereUrl; ?>">
                        <input type="hidden" name="merchant_id" value="<?php echo PAYHERE_MERCHANT_ID; ?>">
                        <input type="hidden" name="return_url" value="<?php echo URLROOT; ?>/homeowner/paymentReturn">
                        <input type="hidden" name="cancel_url" value="<?php echo URLROOT; ?>/homeowner/paymentCancel">
                        <input type="hidden" name="notify_url" value="<?php echo URLROOT; ?>/homeowner/paymentNotify">
                        <input type="hidden" name="order_id" value="<?php echo $orderId; ?>">
                        <input type="hidden" name="items" value="<?php echo htmlspecialchars($itemNames); ?>">
                        <input type="hidden" name="currency" value="LKR">
                        <input type="hidden" name="amount" value="<?php echo number_format($subtotal, 2, '.', ''); ?>">
                        <input type="hidden" name="first_name" id="ph_first_name" value="">
                        <input type="hidden" name="last_name" id="ph_last_name" value="">
                        <input type="hidden" name="email" id="ph_email" value="">
                        <input type="hidden" name="phone" id="ph_phone" value="">
                        <input type="hidden" name="address" id="ph_address" value="">
                        <input type="hidden" name="city" id="ph_city" value="">
                        <input type="hidden" name="country" value="Sri Lanka">
                        <input type="hidden" name="hash" value="<?php echo PayHere::generateHash(PAYHERE_MERCHANT_ID, $orderId, $subtotal, 'LKR', PAYHERE_MERCHANT_SECRET); ?>">
                        
                        <button type="submit" class="btn btn-primary btn-lg btn-block payhere-btn">
                            <i class="fa-solid fa-lock"></i> Pay Rs. <?php echo number_format($subtotal, 2); ?>
                        </button>
                    </form>
                    <div class="payment-security"><i class="fa-solid fa-lock"></i> Your payment is encrypted and secure</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('payhereForm').addEventListener('submit', function(e) {
    const form = document.getElementById('checkoutForm');
    if (!form.checkValidity()) { e.preventDefault(); form.reportValidity(); return false; }
    document.getElementById('ph_first_name').value = document.getElementById('first_name').value;
    document.getElementById('ph_last_name').value = document.getElementById('last_name').value;
    document.getElementById('ph_email').value = document.getElementById('email').value;
    document.getElementById('ph_phone').value = document.getElementById('phone').value;
    document.getElementById('ph_address').value = document.getElementById('address').value;
    document.getElementById('ph_city').value = document.getElementById('city').value;
    return true;
});
</script>
