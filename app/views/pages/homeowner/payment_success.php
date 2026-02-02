<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/homeowner/shop.css">
<?php
$config = ['title' => 'Payment Successful!', 'description' => 'Your order has been placed'];
include __DIR__ . '/../../inc/components/page_header.php';
?>
<div class="shop-container">
    <div class="payment-result-container">
        <div class="payment-result-card success">
            <div class="result-icon success"><i class="fa-solid fa-circle-check"></i></div>
            <h2 class="result-title">Payment Successful!</h2>
            <p class="result-message">Thank you for your purchase. Your order has been confirmed.</p>
            <div class="order-details-box">
                <div class="order-detail-row">
                    <span class="detail-label">Order ID:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($data['orderId'] ?? 'N/A'); ?></span>
                </div>
                <div class="order-detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value status-badge success">Confirmed</span>
                </div>
            </div>
            <div class="result-actions">
                <a href="<?php echo URLROOT; ?>/homeowner/shop" class="btn btn-primary"><i class="fa-solid fa-store"></i> Continue Shopping</a>
                <a href="<?php echo URLROOT; ?>/homeowner/dashboard" class="btn btn-secondary"><i class="fa-solid fa-home"></i> Dashboard</a>
            </div>
        </div>
    </div>
</div>
