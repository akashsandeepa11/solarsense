<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/homeowner/shop.css">

<?php
// Page Header Configuration
$config = [
    'title' => 'Shopping Cart',
    'description' => 'Review your items and proceed to checkout',
    'show_back' => true,
    'back_url' => URLROOT . '/homeowner/shop',
    'back_label' => 'Continue Shopping'
];
include __DIR__ . '/../../inc/components/page_header.php';

// Get cart from session
$cartItems = $_SESSION['cart'] ?? [];
?>

<div class="shop-container">

    <div class="cart-grid">
        <div class="cart-items">
            <?php if(empty($cartItems)): ?>
                <div class="empty-cart">
                    <i class="fa-solid fa-cart-shopping" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                    <p>Your cart is empty.</p>
                    <a href="<?php echo URLROOT; ?>/homeowner/shop" class="btn btn-primary" style="margin-top: 1rem;">Browse Products</a>
                </div>
            <?php else: ?>
                <?php foreach($cartItems as $item): ?>
                <div class="cart-card" data-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>">
                    <img src="<?php echo URLROOT; ?>/img/<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" class="cart-product-image">
                    <div class="cart-product-info">
                        <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                        <p class="cart-product-company"><?php echo htmlspecialchars($item['company']); ?></p>
                        <p class="cart-product-price">Rs.<?php echo number_format($item['price'],2); ?></p>

                        <div class="cart-actions">
                            <label>
                                Qty: 
                                <input type="number" min="1" value="<?php echo $item['quantity']; ?>" class="cart-quantity">
                            </label>
                            <button class="remove-btn">Remove</button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="cart-summary">
            <h3>Order Summary</h3>
            <p>Items: <span id="summary-count"><?php echo array_sum(array_column($cartItems,'quantity')); ?></span></p>
            <p>Total: Rs.<span id="summary-total"><?php echo number_format(array_sum(array_map(fn($i)=>$i['price']*$i['quantity'],$cartItems)),2); ?></span></p>
            <?php if(!empty($cartItems)): ?>
            <a href="<?php echo URLROOT; ?>/homeowner/shop/checkout" class="btn btn-primary checkout-btn">Proceed to Checkout</a>
            <?php else: ?>
            <button class="btn btn-primary checkout-btn" disabled style="opacity: 0.5; cursor: not-allowed;">Proceed to Checkout</button>
            <?php endif; ?>
        </div>
    </div>

</div>

<style>
.cart-grid {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.cart-items {
    flex: 2;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.empty-cart {
    text-align: center;
    padding: 3rem;
    background: #fff;
    border-radius: 10px;
    border: 1px solid #ddd;
}

.cart-card {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid #ddd;
    border-radius: 10px;
    background: #fff;
    transition: box-shadow 0.2s;
}

.cart-card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.cart-product-image {
    width: 120px;
    height: 120px;
    object-fit: contain;
    border-radius: 8px;
}

.cart-product-info h4 {
    margin: 0 0 5px;
}

.cart-product-company {
    font-size: 14px;
    color: gray;
}

.cart-product-price {
    font-weight: bold;
    margin: 5px 0;
}

.cart-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.cart-actions input[type="number"] {
    width: 50px;
    padding: 3px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.remove-btn {
    padding: 5px 10px;
    border: none;
    background: #f44336;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.2s;
}

.remove-btn:hover {
    background: #d32f2f;
}

.cart-summary {
    flex: 1;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 1rem;
    background: #fff;
    height: fit-content;
}

.cart-summary h3 {
    margin-top: 0;
}

.checkout-btn {
    width: 100%;
    margin-top: 15px;
    text-align: center;
    text-decoration: none;
}
</style>

<script>
const URLROOT = '<?php echo URLROOT; ?>';

// Update total when quantity changes
document.querySelectorAll('.cart-quantity').forEach(input => {
    input.addEventListener('change', function() {
        const card = this.closest('.cart-card');
        const productId = card.dataset.id;
        const quantity = this.value;
        
        fetch(`${URLROOT}/homeowner/updateCartQty`, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `product_id=${productId}&quantity=${quantity}`
        });
        
        updateSummary();
    });
});

// Remove item handlers
document.querySelectorAll('.remove-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const card = this.closest('.cart-card');
        const productId = card.dataset.id;
        
        fetch(`${URLROOT}/homeowner/removeFromCart`, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `product_id=${productId}`
        });
        
        card.remove();
        updateSummary();
        
        // Check if cart is empty
        if (document.querySelectorAll('.cart-card').length === 0) {
            location.reload();
        }
    });
});

function updateSummary() {
    const cartCards = document.querySelectorAll('.cart-card');
    let total = 0, count = 0;

    cartCards.forEach(card => {
        const price = parseFloat(card.dataset.price);
        const qty = parseInt(card.querySelector('.cart-quantity').value);
        total += price * qty;
        count += qty;
    });

    document.getElementById('summary-total').textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('summary-count').textContent = count;
}
</script>
