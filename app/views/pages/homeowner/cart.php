<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/homeowner/shop.css">

<?php
// Dummy cart data (like products added to cart)
$cartItems = [
    [
        'id' => 1,
        'title' => 'Premium Solar Battery',
        'company' => 'SolarTech Solutions',
        'price' => 899.99,
        'quantity' => 1,
        'image' => 'solar_battery.png'
    ],
    [
        'id' => 5,
        'title' => 'Portable Solar Power Bank',
        'company' => 'MobilePower Plus',
        'price' => 59.99,
        'quantity' => 2,
        'image' => 'portable_solar_powerbank.png'
    ],
    [
        'id' => 3,
        'title' => 'Solar Garden Lamp Set',
        'company' => 'GreenLight Solutions',
        'price' => 129.99,
        'quantity' => 1,
        'image' => 'solar_graden_lamp_set.png'
    ]
];
?>

<div class="shop-container">

    <h2 style="margin-bottom: 20px;">Your Shopping Cart</h2>

    <div class="cart-grid">
        <div class="cart-items">
            <?php if(empty($cartItems)): ?>
                <p style="text-align:center; padding:2rem;">Your cart is empty.</p>
            <?php else: ?>
                <?php foreach($cartItems as $item): ?>
                <div class="cart-card" data-id="<?php echo $item['id']; ?>">
                    <img src="<?php echo URLROOT; ?>/img/<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" class="cart-product-image">
                    <div class="cart-product-info">
                        <h4><?php echo $item['title']; ?></h4>
                        <p class="cart-product-company"><?php echo $item['company']; ?></p>
                        <p class="cart-product-price">$<?php echo number_format($item['price'],2); ?></p>

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
            <p>Total: $<span id="summary-total"><?php echo number_format(array_sum(array_map(fn($i)=>$i['price']*$i['quantity'],$cartItems)),2); ?></span></p>
            <button class="btn btn-primary checkout-btn">Proceed to Checkout</button>
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
}
</style>

<script>
// Update total when quantity changes
const quantities = document.querySelectorAll('.cart-quantity');
const summaryTotal = document.getElementById('summary-total');
const summaryCount = document.getElementById('summary-count');

quantities.forEach(input => {
    input.addEventListener('change', updateSummary);
});

document.querySelectorAll('.remove-btn').forEach(btn => {
    btn.addEventListener('click', e => {
        const card = e.target.closest('.cart-card');
        card.remove();
        updateSummary();
    });
});

function updateSummary() {
    const cartCards = document.querySelectorAll('.cart-card');
    let total = 0, count = 0;

    cartCards.forEach(card => {
        const price = parseFloat(card.querySelector('.cart-product-price').textContent.replace('$',''));
        const qty = parseInt(card.querySelector('.cart-quantity').value);
        total += price * qty;
        count += qty;
    });

    summaryTotal.textContent = total.toFixed(2);
    summaryCount.textContent = count;
}
</script>
