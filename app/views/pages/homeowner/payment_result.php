<?php
$status = $data['status'] ?? 'success';
$isSuccess = $status === 'success';
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components.css">

<div style="display:flex;align-items:center;justify-content:center;min-height:55vh;">
    <div style="text-align:center;max-width:480px;padding:3rem 2rem;background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

        <?php if ($isSuccess): ?>
            <!-- <div style="font-size:4rem;margin-bottom:1rem;">✅</div> -->
            <h2 style="color:#16a34a;margin:0 0 0.5rem;">Payment Successful!</h2>
            <p style="color:#6b7280;margin:0 0 2rem;">Your order has been placed successfully. Thank you for shopping with SolarSense.</p>
        <?php else: ?>
            <div style="font-size:4rem;margin-bottom:1rem;">❌</div>
            <h2 style="color:#dc2626;margin:0 0 0.5rem;">Payment Cancelled</h2>
            <p style="color:#6b7280;margin:0 0 2rem;">Your payment was cancelled. Your cart items are still saved — you can try again.</p>
        <?php endif; ?>

        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="<?php echo URLROOT; ?>/homeowner/shop" class="btn btn-primary">
                <i class="fas fa-shopping-bag mr-2"></i>Continue Shopping
            </a>
            <a href="<?php echo URLROOT; ?>/homeowner/dashboard" class="btn btn-secondary">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>
        </div>
    </div>
</div>
