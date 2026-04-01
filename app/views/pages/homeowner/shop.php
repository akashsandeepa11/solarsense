
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/homeowner/shop.css">

<?php
$products   = $data['products']   ?? [];
$categories = $data['categories'] ?? [];

$config = [
    'title'       => 'Accessories Store',
    'description' => 'Browse and purchase solar accessories and equipment',
];
include __DIR__ . '/../../inc/components/page_header.php';
?>

<div class="shop-container">

    <div class="filters">
        <div class="filter-group">
            <label for="categoryFilter">Product Category</label>
            <select id="categoryFilter">
                <option value="">All Categories</option>
                <?php foreach ((array)$categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat->name ?? $cat['name']); ?>">
                        <?php echo htmlspecialchars($cat->name ?? $cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-group">
            <label for="priceFilter">Price Range</label>
            <select id="priceFilter">
                <option value="">Any Price</option>
                <option value="0-30000">Under Rs. 30,000</option>
                <option value="30000-150000">Rs. 30,000 - Rs. 150,000</option>
                <option value="150000-300000">Rs. 150,000 - Rs. 300,000</option>
                <option value="300000-600000">Rs. 300,000 - Rs. 600,000</option>
                <option value="600000+">Above Rs. 600,000</option>
            </select>
        </div>

        <button class="filter-button" onclick="applyFilters()">Apply Filters</button>

        <a href="<?php echo URLROOT; ?>/homeowner/shop/cart" class="cart-wrapper">
            <i class="fa-solid fa-cart-shopping cart-icon"></i>
            <span id="cartCount" class="cart-count">0</span>
        </a>
    </div>

    <div class="products-grid">
        <?php if (empty($products)): ?>
            <p style="grid-column:1/-1; text-align:center; padding:3rem; color:#6b7280;">No items in inventory yet.</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
            <div class="product-card"
                 data-id="<?php echo (int)$product['id']; ?>"
                 data-title="<?php echo htmlspecialchars($product['title'], ENT_QUOTES); ?>"
                 data-price="<?php echo (float)$product['price']; ?>"
                 data-image="<?php echo htmlspecialchars($product['image'] ?? '', ENT_QUOTES); ?>"
                 data-category="<?php echo htmlspecialchars($product['category'], ENT_QUOTES); ?>">

                <?php if (!empty($product['image'])): ?>
                    <img src="<?php echo URLROOT; ?>/img/inventory/<?php echo htmlspecialchars($product['image']); ?>"
                         alt="<?php echo htmlspecialchars($product['title']); ?>"
                         class="product-image"
                         onerror="this.style.display='none'">
                <?php else: ?>
                    <div class="product-image" style="display:flex;align-items:center;justify-content:center;background:#f3f4f6;">
                        <i class="fas fa-box-open" style="font-size:3rem;color:#d1d5db;"></i>
                    </div>
                <?php endif; ?>

                <div class="product-info">
                    <h3 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h3>
                    <?php if (!empty($product['category'])): ?>
                        <p class="product-company"><?php echo htmlspecialchars($product['category']); ?></p>
                    <?php endif; ?>
                    <p class="product-price">Rs. <?php echo number_format($product['price'], 2); ?></p>
                    <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                    <div class="product-actions">
                        <button class="btn btn-primary add-to-cart-btn">Add to Cart</button>
                        <a href="<?php echo URLROOT; ?>/homeowner/productDetails/<?php echo $product['id']; ?>" class="btn btn-secondary">Details</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
.cart-wrapper {
    position: relative;
    margin-left: 15px;
    cursor: pointer;
}
.cart-icon {
    font-size: 28px;
    color: gray;
}
.cart-icon:hover { color: orange; }
.cart-count {
    position: absolute;
    top: -6px;
    right: -8px;
    background: orange;
    color: white;
    font-size: 12px;
    padding: 2px 6px;
    border-radius: 50%;
    font-weight: bold;
    display: none;
}
</style>

<script>
// ── Filter ──────────────────────────────────────────────
function applyFilters() {
    const category   = document.getElementById('categoryFilter').value.toLowerCase();
    const priceRange = document.getElementById('priceFilter').value;
    const cards      = document.querySelectorAll('.product-card');

    cards.forEach(card => {
        let show = true;
        if (category && card.dataset.category.toLowerCase() !== category) show = false;
        if (show && priceRange) {
            const price = parseFloat(card.dataset.price);
            switch (priceRange) {
                case '0-30000':       show = price <= 30000; break;
                case '30000-150000':  show = price > 30000  && price <= 150000; break;
                case '150000-300000': show = price > 150000 && price <= 300000; break;
                case '300000-600000': show = price > 300000 && price <= 600000; break;
                case '600000+':       show = price > 600000; break;
            }
        }
        card.style.display = show ? '' : 'none';
    });

    const visible = [...cards].filter(c => c.style.display !== 'none').length;
    let msg = document.getElementById('noResultsMessage');
    if (visible === 0) {
        if (!msg) {
            msg = document.createElement('p');
            msg.id = 'noResultsMessage';
            msg.style.cssText = 'grid-column:1/-1;text-align:center;padding:2rem;color:#6b7280;';
            msg.textContent = 'No products found matching your filters.';
            document.querySelector('.products-grid').appendChild(msg);
        }
    } else if (msg) {
        msg.remove();
    }
}

document.getElementById('categoryFilter').addEventListener('change', applyFilters);
document.getElementById('priceFilter').addEventListener('change', applyFilters);

// ── Cart (localStorage) ──────────────────────────────────
function getCart() {
    return JSON.parse(localStorage.getItem('ss_cart') || '[]');
}
function saveCart(cart) {
    localStorage.setItem('ss_cart', JSON.stringify(cart));
}
function updateCartBubble() {
    const total = getCart().reduce((s, i) => s + i.qty, 0);
    const bubble = document.getElementById('cartCount');
    bubble.textContent = total;
    bubble.style.display = total > 0 ? 'inline-block' : 'none';
}

document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const card  = this.closest('.product-card');
        const id    = parseInt(card.dataset.id);
        const title = card.dataset.title;
        const price = parseFloat(card.dataset.price);
        const image = card.dataset.image;

        const cart = getCart();
        const existing = cart.find(i => i.id === id);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({ id, title, price, image, qty: 1 });
        }
        saveCart(cart);
        updateCartBubble();

        // Quick feedback
        this.textContent = 'Added ✓';
        this.disabled = true;
        setTimeout(() => { this.textContent = 'Add to Cart'; this.disabled = false; }, 1200);
    });
});

// Initialise bubble on load
updateCartBubble();
</script>
