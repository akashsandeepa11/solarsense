<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/homeowner/shop.css">

<?php
$config = [
    'title'       => 'Shopping Cart',
    'description' => 'Review your items and proceed to checkout',
    'show_back'   => true,
    'back_url'    => URLROOT . '/homeowner/shop',
    'back_label'  => 'Continue Shopping',
];
include __DIR__ . '/../../inc/components/page_header.php';
?>

<div class="shop-container">
    <div class="cart-grid">

        <!-- Cart Items (populated by JS from localStorage) -->
        <div class="cart-items" id="cartItemsContainer">
            <p id="emptyCartMsg" style="text-align:center;padding:2rem;color:#6b7280;">
                <i class="fas fa-shopping-cart" style="font-size:2rem;display:block;margin-bottom:1rem;"></i>
                Your cart is empty. <a href="<?php echo URLROOT; ?>/homeowner/shop">Browse items</a>
            </p>
        </div>

        <!-- Order Summary -->
        <div class="cart-summary">
            <h3>Order Summary</h3>
            <p>Items: <span id="summary-count">0</span></p>
            <p>Total: <strong>Rs. <span id="summary-total">0.00</span></strong></p>
            <button class="btn btn-primary checkout-btn" id="checkoutBtn" disabled>Proceed to Checkout</button>
        <!-- hidden form posts cart to PHP for hash generation -->
        <form id="checkoutForm" method="POST" action="<?php echo URLROOT; ?>/homeowner/checkout" style="display:none;">
            <input type="hidden" name="cart" id="cartPayload">
        </form>
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
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    background: #fff;
    transition: box-shadow 0.2s;
}
.cart-card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
.cart-product-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    background: #f3f4f6;
}
.cart-product-info h4 { margin: 0 0 4px; }
.cart-product-company { font-size: 13px; color: #9ca3af; margin: 0 0 4px; }
.cart-product-price { font-weight: 700; margin: 4px 0; color: #fe9630; }
.cart-actions { display: flex; gap: 10px; align-items: center; margin-top: 8px; }
.cart-actions input[type="number"] {
    width: 55px; padding: 4px 6px;
    border-radius: 6px; border: 1px solid #d1d5db;
    font-size: 0.875rem;
}
.remove-btn {
    padding: 4px 10px; border: none;
    background: #fee2e2; color: #dc2626;
    border-radius: 6px; cursor: pointer;
    font-size: 0.8rem; transition: background 0.2s;
}
.remove-btn:hover { background: #fca5a5; }
.cart-summary {
    flex: 1; border: 1px solid #e5e7eb;
    border-radius: 10px; padding: 1.5rem;
    background: #fff; height: fit-content;
    position: sticky; top: 20px;
}
.cart-summary h3 { margin-top: 0; }
.cart-summary p { margin: 0.5rem 0; font-size: 0.95rem; }
.checkout-btn { width: 100%; margin-top: 1rem; }
.checkout-btn:disabled { opacity: 0.5; cursor: not-allowed; }
</style>

<script>
const URLROOT = '<?php echo URLROOT; ?>';

function getCart() {
    return JSON.parse(localStorage.getItem('ss_cart') || '[]');
}
function saveCart(cart) {
    localStorage.setItem('ss_cart', JSON.stringify(cart));
}

function renderCart() {
    const cart      = getCart();
    const container = document.getElementById('cartItemsContainer');
    const emptyMsg  = document.getElementById('emptyCartMsg');
    const countEl   = document.getElementById('summary-count');
    const totalEl   = document.getElementById('summary-total');
    const checkoutBtn = document.getElementById('checkoutBtn');

    // Remove existing cards (keep emptyMsg)
    container.querySelectorAll('.cart-card').forEach(c => c.remove());

    if (cart.length === 0) {
        emptyMsg.style.display = 'block';
        countEl.textContent = 0;
        totalEl.textContent = '0.00';
        checkoutBtn.disabled = true;
        return;
    }

    emptyMsg.style.display = 'none';
    checkoutBtn.disabled = false;

    let totalQty = 0, totalAmt = 0;

    cart.forEach(item => {
        totalQty += item.qty;
        totalAmt += item.price * item.qty;

        const imgSrc = item.image
            ? `${URLROOT}/img/inventory/${item.image}`
            : '';

        const card = document.createElement('div');
        card.className = 'cart-card';
        card.dataset.id = item.id;
        card.innerHTML = `
            ${imgSrc
                ? `<img src="${imgSrc}" alt="${item.title}" class="cart-product-image" onerror="this.style.display='none'">`
                : `<div class="cart-product-image" style="display:flex;align-items:center;justify-content:center;"><i class="fas fa-box-open" style="font-size:2rem;color:#d1d5db;"></i></div>`}
            <div class="cart-product-info" style="flex:1;">
                <h4>${item.title}</h4>
                <p class="cart-product-price">Rs. ${(item.price * item.qty).toLocaleString('en-US', {minimumFractionDigits:2})}</p>
                <p style="font-size:0.8rem;color:#9ca3af;">Rs. ${item.price.toLocaleString('en-US', {minimumFractionDigits:2})} each</p>
                <div class="cart-actions">
                    <label>Qty: <input type="number" min="1" value="${item.qty}" class="cart-quantity" data-id="${item.id}"></label>
                    <button class="remove-btn" data-id="${item.id}"><i class="fas fa-trash mr-1"></i>Remove</button>
                </div>
            </div>`;
        container.appendChild(card);
    });

    countEl.textContent = totalQty;
    totalEl.textContent = totalAmt.toLocaleString('en-US', {minimumFractionDigits:2});

    // Qty change
    container.querySelectorAll('.cart-quantity').forEach(input => {
        input.addEventListener('change', function () {
            const id  = parseInt(this.dataset.id);
            const qty = Math.max(1, parseInt(this.value) || 1);
            const cart = getCart();
            const item = cart.find(i => i.id === id);
            if (item) { item.qty = qty; saveCart(cart); }
            renderCart();
        });
    });

    // Remove
    container.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id   = parseInt(this.dataset.id);
            const cart = getCart().filter(i => i.id !== id);
            saveCart(cart);
            renderCart();
        });
    });
}

document.getElementById('checkoutBtn').addEventListener('click', () => {
    const cart = getCart();
    if (cart.length === 0) return;
    document.getElementById('cartPayload').value = JSON.stringify(cart);
    document.getElementById('checkoutForm').submit();
});

renderCart();
</script>
