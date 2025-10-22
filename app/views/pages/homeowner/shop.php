
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/homeowner/shop.css">

<?php
// Page Header Configuration
$config = [
    'title' => 'Accessories Store',
    'description' => 'Browse and purchase solar accessories and equipment'
];
include __DIR__ . '/../../inc/components/page_header.php';

$products = $data['products'] ?? [
    [
        'id' => 1,
        'title' => 'Premium Solar Battery',
        'company' => 'SolarTech Solutions',
        'price' => 899.99,
        'description' => 'High-capacity lithium battery perfect for residential solar systems. 10-year warranty included.',
        'image' => 'solar_battery.png',
        'category' => 'batteries'
    ],
    [
        'id' => 2,
        'title' => 'Solar Panel Kit',
        'company' => 'EcoEnergy Systems',
        'price' => 1299.99,
        'description' => 'Complete solar panel kit with mounting hardware. Perfect for residential installation.',
        'image' => 'solar_panel_kit.png',
        'category' => 'panels'
    ],
    [
        'id' => 3,
        'title' => 'Solar Garden Lamp Set',
        'company' => 'GreenLight Solutions',
        'price' => 129.99,
        'description' => 'Set of 4 solar-powered garden lamps with motion sensors and dusk-to-dawn operation.',
        'image' => 'solar_graden_lamp_set.png',
        'category' => 'lighting'
    ],
    [
        'id' => 4,
        'title' => 'Smart Solar Inverter',
        'company' => 'PowerTech Pro',
        'price' => 799.99,
        'description' => 'Smart inverter with WiFi monitoring capabilities and automatic power management.',
        'image' => 'smart_solar_inverter.png',
        'category' => 'inverters'
    ],
    [
        'id' => 5,
        'title' => 'Portable Solar Power Bank',
        'company' => 'MobilePower Plus',
        'price' => 59.99,
        'description' => '20000mAh solar-powered power bank with dual USB ports and fast charging capability.',
        'image' => 'portable_solar_powerbank.png',
        'category' => 'gadgets'
    ],
    [
        'id' => 6,
        'title' => 'Solar Powered Fan',
        'company' => 'CoolBreeze Solar',
        'price' => 149.99,
        'description' => 'Energy-efficient solar fan with remote control and built-in battery backup.',
        'image' => 'fan.png',
        'category' => 'gadgets'
    ],
    [
        'id' => 7,
        'title' => 'Solar Water Heater',
        'company' => 'HotWater Solutions',
        'price' => 699.99,
        'description' => '200L solar water heater with intelligent temperature control and backup heating.',
        'image' => 'solar_battery.png',
        'category' => 'heaters'
    ],
    [
        'id' => 8,
        'title' => 'Solar Security Camera',
        'company' => 'SecureVision',
        'price' => 199.99,
        'description' => 'Wireless security camera with solar charging, night vision, and mobile app control.',
        'image' => 'solar_battery.png',
        'category' => 'security'
    ],
    [
        'id' => 9,
        'title' => 'Solar System Controller',
        'company' => 'SmartControl Tech',
        'price' => 299.99,
        'description' => 'Smart solar system controller with LCD display and mobile app integration.',
        'image' => 'solar_battery.png',
        'category' => 'inverters'
    ],
    [
        'id' => 10,
        'title' => 'Solar Pool Pump',
        'company' => 'AquaSolar',
        'price' => 449.99,
        'description' => 'Energy-efficient solar pool pump with variable speed control and timer function.',
        'image' => 'solar_battery.png',
        'category' => 'accessories'
    ],
    [
        'id' => 11,
        'title' => 'Solar String Lights',
        'company' => 'FairyGlow',
        'price' => 39.99,
        'description' => '100 LED waterproof string lights with 8 lighting modes and auto on/off.',
        'image' => 'solar_battery.png',
        'category' => 'lighting'
    ],
    [
        'id' => 12,
        'title' => 'MPPT Charge Controller',
        'company' => 'PowerMax Systems',
        'price' => 249.99,
        'description' => '60A MPPT solar charge controller with advanced battery protection and monitoring.',
        'image' => 'solar_battery.png',
        'category' => 'inverters'
    ]
];
?>

<div class="shop-container">


    <div class="filters">
        <div class="filter-group">
            <label for="categoryFilter">Product Category</label>
            <select id="categoryFilter">
                <option value="">All Categories</option>
                <option value="batteries">Solar Batteries</option>
                <option value="panels">Solar Panels</option>
                <option value="inverters">Inverters & Controllers</option>
                <option value="lighting">Solar Lighting</option>
                <option value="gadgets">Solar Gadgets</option>
                <option value="heaters">Water Heaters</option>
                <option value="security">Security Devices</option>
                <option value="accessories">Accessories</option>
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

        <button class="filter-button" onclick="applyFilters()">
            Apply Filters
        </button>

        <a href="<?php echo URLROOT; ?>/homeowner/shop/cart" class="cart-wrapper">
    <i class="fa-solid fa-cart-shopping cart-icon"></i>
    <span id="cartCount" class="cart-count">0</span>
</a>


     
    </div>

    <div class="products-grid">
        <?php foreach ($products as $product): ?>
        <div class="product-card" data-category="<?php echo htmlspecialchars($product['category']); ?>" data-price="<?php echo $product['price']; ?>">
            <img src="<?php echo URLROOT; ?>/img/<?php echo htmlspecialchars($product['image']); ?>" 
                 alt="<?php echo htmlspecialchars($product['title']); ?>" 
                 class="product-image">
            <div class="product-info">
                <h3 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h3>
                <p class="product-company"><?php echo htmlspecialchars($product['company']); ?></p>
                <p class="product-price">Rs. <?php echo number_format($product['price'] * 325, 2); ?></p>
                <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                <div class="product-actions">
                    <button class="btn btn-primary">Add to Cart</button>
                    <a href="<?php echo URLROOT; ?>/homeowner/productDetails/<?php echo $product['id']; ?>" class="btn btn-secondary">Details</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

    <script>
        function applyFilters() {
            const category = document.getElementById('categoryFilter').value.toLowerCase();
            const priceRange = document.getElementById('priceFilter').value;
            const products = document.querySelectorAll('.product-card');
            
            products.forEach(product => {
                let showProduct = true;
                
                // Filter by category
                if (category) {
                    const productCategory = product.dataset.category;
                    showProduct = (category === productCategory);
                }
                
                // Filter by price
                if (showProduct && priceRange) {
                    const price = parseFloat(product.dataset.price);
                    
                    // Convert price to Sri Lankan Rupees for comparison
                    const priceInLKR = price * 325;
                    switch(priceRange) {
                        case '0-30000':
                            showProduct = priceInLKR <= 30000;
                            break;
                        case '30000-150000':
                            showProduct = priceInLKR > 30000 && priceInLKR <= 150000;
                            break;
                        case '150000-300000':
                            showProduct = priceInLKR > 150000 && priceInLKR <= 300000;
                            break;
                        case '300000-600000':
                            showProduct = priceInLKR > 300000 && priceInLKR <= 600000;
                            break;
                        case '600000+':
                            showProduct = priceInLKR > 600000;
                            break;
                    }
                }
                
                // Show or hide the product based on filters
                product.style.display = showProduct ? '' : 'none';
                product.style.opacity = showProduct ? '1' : '0';
            });

            // Check if no products are visible
            const visibleProducts = Array.from(products).filter(p => p.style.display === '').length;
            const noResultsMsg = document.getElementById('noResultsMessage');
            
            if (visibleProducts === 0) {
                if (!noResultsMsg) {
                    const msg = document.createElement('div');
                    msg.id = 'noResultsMessage';
                    msg.style.textAlign = 'center';
                    msg.style.padding = '2rem';
                    msg.style.gridColumn = '1 / -1';
                    msg.innerHTML = '<h4>No products found matching your filters</h4>';
                    document.querySelector('.products-grid').appendChild(msg);
                }
            } else if (noResultsMsg) {
                noResultsMsg.remove();
            }
        }

        // Add event listeners for filters
        document.getElementById('categoryFilter').addEventListener('change', applyFilters);
        document.getElementById('priceFilter').addEventListener('change', applyFilters);  


        let cartCount = 0;

//number of add to cart items  update in cart icon
document.querySelectorAll('.btn.btn-primary').forEach(btn => {
    btn.addEventListener('click', function() {
        cartCount++;
        const countBubble = document.getElementById('cartCount');
        countBubble.textContent = cartCount;
        if (cartCount > 0) {
    countBubble.style.display = 'inline-block';
    countBubble.textContent = cartCount;
}

    });
});
    
</script>  

<style>
.cart-wrapper {
  position: relative;
  margin-left: 15px;
  cursor: pointer;
}

.cart-icon {
  font-size: 28px; 
  color: gray;/* bigger modern icon */
  
}

.cart-icon:hover {
  color: orange;
}

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
  display: none; /* hidden initially */
}
</style>
