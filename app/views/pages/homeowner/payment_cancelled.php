<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/homeowner/shop.css">
<?php
$config = ['title' => 'Payment Cancelled', 'description' => 'Your payment was not completed'];
include __DIR__ . '/../../inc/components/page_header.php';
?>
<div class="shop-container">
    <div class="payment-result-container">
        <div class="payment-result-card cancelled">
            <div class="result-icon cancelled"><i class="fa-solid fa-circle-xmark"></i></div>
            <h2 class="result-title">Payment Cancelled</h2>
            <p class="result-message">Your payment was not completed. No charges have been made.</p>
            <div class="result-actions">
                <a href="<?php echo URLROOT; ?>/homeowner/shop/checkout" class="btn btn-primary"><i class="fa-solid fa-redo"></i> Try Again</a>
                <a href="<?php echo URLROOT; ?>/homeowner/shop/cart" class="btn btn-secondary"><i class="fa-solid fa-cart-shopping"></i> Back to Cart</a>
            </div>
        </div>
    </div>
</div>
