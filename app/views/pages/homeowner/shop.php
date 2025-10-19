
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/homeowner/shop.css">

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
                <option value="0-100">Under $100</option>
                <option value="100-500">$100 - $500</option>
                <option value="500-1000">$500 - $1,000</option>
                <option value="1000-2000">$1,000 - $2,000</option>
                <option value="2000+">Above $2,000</option>
            </select>
        </div>

        <button class="filter-button" onclick="applyFilters()">
            Apply Filters
        </button>
    </div>

    <div class="products-grid">
        <!-- Product Card 1 -->
        <div class="product-card">
            <img src="<?php echo URLROOT; ?>/img/solar_battery.png" alt="Solar Battery" class="product-image">
            <div class="product-info">
                <h3 class="product-title">Premium Solar Battery</h3>
                <p class="product-company">SolarTech Solutions</p>
                <p class="product-price">$899.99</p>
                <p class="product-description">High-capacity lithium battery perfect for residential solar systems. 10-year warranty included.</p>
                <div class="product-actions">
                    <button class="btn btn-primary">Add to Cart</button>
                    <button class="btn btn-secondary">Details</button>
                </div>
            </div>
        </div>

        <!-- Product Card 2 -->
        <div class="product-card">
            <img src="<?php echo URLROOT; ?>/img/solar_panel_kit.png" alt="Solar Panel Kit" class="product-image">
            <div class="product-info">
                <h3 class="product-title">Solar Panel Kit</h3>
                <p class="product-company">EcoEnergy Systems</p>
                <p class="product-price">$1,299.99</p>
                <p class="product-description">Complete solar panel kit with mounting hardware. Perfect for residential installation.</p>
                <div class="product-actions">
                    <button class="btn btn-primary">Add to Cart</button>
                    <button class="btn btn-secondary">Details</button>
                </div>
            </div>
        </div>

        <!-- Product Card 3 -->
        <div class="product-card">
            <img src="<?php echo URLROOT; ?>/img/solar_graden_lamp_set.png" alt="Solar Garden Lights" class="product-image">
            <div class="product-info">
                <h3 class="product-title">Solar Garden Lamp Set</h3>
                <p class="product-company">GreenLight Solutions</p>
                <p class="product-price">$129.99</p>
                <p class="product-description">Set of 4 solar-powered garden lamps with motion sensors and dusk-to-dawn operation.</p>
                <div class="product-actions">
                    <button class="btn btn-primary">Add to Cart</button>
                    <button class="btn btn-secondary">Details</button>
                </div>
            </div>
        </div>

        <!-- Product Card 4 -->
        <div class="product-card">
            <img src="<?php echo URLROOT; ?>/img/smart_solar_inverter.png" alt="Solar Inverter" class="product-image">
            <div class="product-info">
                <h3 class="product-title">Smart Solar Inverter</h3>
                <p class="product-company">PowerTech Pro</p>
                <p class="product-price">$799.99</p>
                <p class="product-description">Smart inverter with WiFi monitoring capabilities and automatic power management.</p>
                <div class="product-actions">
                    <button class="btn btn-primary">Add to Cart</button>
                    <button class="btn btn-secondary">Details</button>
                </div>
            </div>
        </div>

        <!-- Product Card 5 -->
        <div class="product-card">
            <img src="<?php echo URLROOT; ?>/img/portable_solar_powerbank.png" alt="Solar Power Bank" class="product-image">
            <div class="product-info">
                <h3 class="product-title">Portable Solar Power Bank</h3>
                <p class="product-company">MobilePower Plus</p>
                <p class="product-price">$59.99</p>
                <p class="product-description">20000mAh solar-powered power bank with dual USB ports and fast charging capability.</p>
                <div class="product-actions">
                    <button class="btn btn-primary">Add to Cart</button>
                    <button class="btn btn-secondary">Details</button>
                </div>
            </div>
        </div>

        <!-- Product Card 6 -->
        <div class="product-card">
            <img src="<?php echo URLROOT; ?>/img/fan.png" alt="Solar Fan" class="product-image">
            <div class="product-info">
                <h3 class="product-title">Solar Powered Fan</h3>
                <p class="product-company">CoolBreeze Solar</p>
                <p class="product-price">$149.99</p>
                <p class="product-description">Energy-efficient solar fan with remote control and built-in battery backup.</p>
                <div class="product-actions">
                    <button class="btn btn-primary">Add to Cart</button>
                    <button class="btn btn-secondary">Details</button>
                </div>
            </div>
        </div>

        <!-- Product Card 7 -->
        <div class="product-card">
            <img src="<?php echo URLROOT; ?>/img/solar_battery.png" alt="Solar Water Heater" class="product-image">
            <div class="product-info">
                <h3 class="product-title">Solar Water Heater</h3>
                <p class="product-company">HotWater Solutions</p>
                <p class="product-price">$699.99</p>
                <p class="product-description">200L solar water heater with intelligent temperature control and backup heating.</p>
                <div class="product-actions">
                    <button class="btn btn-primary">Add to Cart</button>
                    <button class="btn btn-secondary">Details</button>
                </div>
            </div>
        </div>

        <!-- Product Card 8 -->
        <div class="product-card">
            <img src="<?php echo URLROOT; ?>/img/solar_battery.png" alt="Solar Security Camera" class="product-image">
            <div class="product-info">
                <h3 class="product-title">Solar Security Camera</h3>
                <p class="product-company">SecureVision</p>
                <p class="product-price">$199.99</p>
                <p class="product-description">Wireless security camera with solar charging, night vision, and mobile app control.</p>
                <div class="product-actions">
                    <button class="btn btn-primary">Add to Cart</button>
                    <button class="btn btn-secondary">Details</button>
                </div>
            </div>
        </div>

        <!-- Product Card 9 -->
        <div class="product-card">
            <img src="<?php echo URLROOT; ?>/img/solar_battery.png" alt="Smart Controller" class="product-image">
            <div class="product-info">
                <h3 class="product-title">Solar System Controller</h3>
                <p class="product-company">SmartControl Tech</p>
                <p class="product-price">$299.99</p>
                <p class="product-description">Smart solar system controller with LCD display and mobile app integration.</p>
                <div class="product-actions">
                    <button class="btn btn-primary">Add to Cart</button>
                    <button class="btn btn-secondary">Details</button>
                </div>
            </div>
        </div>

        <!-- Product Card 10 -->
        <div class="product-card">
            <img src="<?php echo URLROOT; ?>/img/solar_battery.png" alt="Solar Pool Pump" class="product-image">
            <div class="product-info">
                <h3 class="product-title">Solar Pool Pump</h3>
                <p class="product-company">AquaSolar</p>
                <p class="product-price">$449.99</p>
                <p class="product-description">Energy-efficient solar pool pump with variable speed control and timer function.</p>
                <div class="product-actions">
                    <button class="btn btn-primary">Add to Cart</button>
                    <button class="btn btn-secondary">Details</button>
                </div>
            </div>
        </div>

        <!-- Product Card 11 -->
        <div class="product-card">
            <img src="<?php echo URLROOT; ?>/img/solar_battery.png" alt="Solar String Lights" class="product-image">
            <div class="product-info">
                <h3 class="product-title">Solar String Lights</h3>
                <p class="product-company">FairyGlow</p>
                <p class="product-price">$39.99</p>
                <p class="product-description">100 LED waterproof string lights with 8 lighting modes and auto on/off.</p>
                <div class="product-actions">
                    <button class="btn btn-primary">Add to Cart</button>
                    <button class="btn btn-secondary">Details</button>
                </div>
            </div>
        </div>

        <!-- Product Card 12 -->
        <div class="product-card">
            <img src="<?php echo URLROOT; ?>/img/solar_battery.png" alt="Charge Controller" class="product-image">
            <div class="product-info">
                <h3 class="product-title">MPPT Charge Controller</h3>
                <p class="product-company">PowerMax Systems</p>
                <p class="product-price">$249.99</p>
                <p class="product-description">60A MPPT solar charge controller with advanced battery protection and monitoring.</p>
                <div class="product-actions">
                    <button class="btn btn-primary">Add to Cart</button>
                    <button class="btn btn-secondary" onclick="showProductDetails(this)">Details</button>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>

        function applyFilters() {
            const category = document.getElementById('categoryFilter').value.toLowerCase();
            const priceRange = document.getElementById('priceFilter').value;            // Get all product cards
            const products = document.querySelectorAll('.product-card');
            
            products.forEach(product => {
                let showProduct = true;
                
                // Filter by category
                if (category) {
                    const productTitle = product.querySelector('.product-title').textContent.toLowerCase();
                    const productDesc = product.querySelector('.product-description').textContent.toLowerCase();
                    
                    // Check if product matches selected category
                    switch(category) {
                        case 'batteries':
                            showProduct = productTitle.includes('battery') || productDesc.includes('battery');
                            break;
                        case 'panels':
                            showProduct = productTitle.includes('panel') || productDesc.includes('panel');
                            break;
                        case 'inverters':
                            showProduct = productTitle.includes('inverter') || productTitle.includes('controller') || 
                                        productDesc.includes('inverter') || productDesc.includes('controller');
                            break;
                        case 'lighting':
                            showProduct = productTitle.includes('lamp') || productTitle.includes('light') || 
                                        productDesc.includes('lamp') || productDesc.includes('light');
                            break;
                        case 'gadgets':
                            showProduct = productTitle.includes('power bank') || productTitle.includes('fan') || 
                                        productDesc.includes('portable') || productDesc.includes('gadget');
                            break;
                        case 'heaters':
                            showProduct = productTitle.includes('heater') || productDesc.includes('heater');
                            break;
                        case 'security':
                            showProduct = productTitle.includes('security') || productTitle.includes('camera') || 
                                        productDesc.includes('security') || productDesc.includes('camera');
                            break;
                        case 'accessories':
                            showProduct = productTitle.includes('controller') || productTitle.includes('pump') || 
                                        productDesc.includes('accessory') || productDesc.includes('accessories');
                            break;
                    }
                }
                
                // Filter by price
                if (showProduct && priceRange) {
                    const price = parseFloat(product.querySelector('.product-price').textContent.replace('$', '').replace(',', ''));
                    
                    switch(priceRange) {
                        case '0-100':
                            showProduct = price <= 100;
                            break;
                        case '100-500':
                            showProduct = price > 100 && price <= 500;
                            break;
                        case '500-1000':
                            showProduct = price > 500 && price <= 1000;
                            break;
                        case '1000-2000':
                            showProduct = price > 1000 && price <= 2000;
                            break;
                        case '2000+':
                            showProduct = price > 2000;
                            break;
                    }
                }
                
                // Show or hide the product based on filters
                if (showProduct) {
                    product.style.display = '';
                    product.style.opacity = '1';
                } else {
                    product.style.display = 'none';
                    product.style.opacity = '0';
                }
            });

            // Check if no products are visible
            const visibleProducts = document.querySelectorAll('.product-card[style="display: "]').length;
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



    // Simple filter functionality
    document.getElementById('categoryFilter').addEventListener('change', function() {
        // Add filtering logic here
        console.log('Category filter changed:', this.value);
    });

    document.getElementById('priceFilter').addEventListener('change', function() {
        // Add filtering logic here
        console.log('Price filter changed:', this.value);
    });

    
</script>

