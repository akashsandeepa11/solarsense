<?php
    $product = $data['product'] ?? null;
    if (!$product) {
        // Define a simple redirect function if not already defined
        function redirect($url) {
            header('Location: ' . URLROOT . '/' . ltrim($url, '/'));
            exit;
        }
        redirect('homeowner/shop');
    }
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/homeowner/shop.css">

<div class="product-details-container">
    <div class="product-details">
        <div class="product-image-section">
            <img src="<?php echo URLROOT; ?>/img/<?php echo htmlspecialchars($product['image']); ?>" 
                 alt="<?php echo htmlspecialchars($product['title']); ?>" 
                 class="product-detail-image">
        </div>
        <div class="product-info-section">
            <h1 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h1>
            <p class="product-company"><?php echo htmlspecialchars($product['company']); ?></p>
            <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
            <div class="product-description">
                <h3>Description</h3>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
            </div>
            <div class="product-actions">
                <button class="btn btn-primary btn-lg">Add to Cart</button>
                <button class="btn btn-secondary btn-lg" onclick="history.back()">Back to Shop</button>
            </div>
        </div>
    </div>
</div>

<style>
.product-details-container {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.product-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.product-detail-image {
    width: 100%;
    max-width: 500px;
    height: auto;
    object-fit: cover;
    border-radius: 4px;
}

.product-info-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.product-title {
    font-size: 2rem;
    margin: 0;
}

.product-company {
    color: #666;
    font-size: 1.1rem;
}

.product-price {
    font-size: 1.5rem;
    font-weight: bold;
    color: #2c3e50;
}

.product-description {
    margin: 1.5rem 0;
}

.product-description h3 {
    margin-bottom: 0.5rem;
}

.product-actions {
    display: flex;
    gap: 1rem;
    margin-top: auto;
}

.btn-lg {
    padding: 0.8rem 1.5rem;
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .product-details {
        grid-template-columns: 1fr;
    }
}
</style>