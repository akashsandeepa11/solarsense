        <title>SolarSense - Your Solar Journey, Simplified</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/landing.css">

    <!-- Header / Navigation -->
    <header class="header" id="header">
        <nav class="nav container">
            <?php
                require APPROOT . '/views/inc/components/logo.php';
            ?>

            
            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item">
                        <a href="#features" class="nav__link">Features</a>
                    </li>
                    <li class="nav__item">
                        <a href="#how-it-works" class="nav__link">How It Works</a>
                    </li>
                    <li class="nav__item">
                        <a href="#installers" class="nav__link">Find Installers</a>
                    </li>
                    <li class="nav__item">
                        <a href="#about" class="nav__link">About Us</a>
                    </li>
                </ul>
            </div>

            <div class="nav__buttons">
                <a href="installer-registration.php" class="btn btn--secondary">Installer Registration</a>
                <a href="<?php echo URLROOT; ?>/users/login" class="btn btn--primary">Login</a>
            </div>

            <div class="nav__toggle" id="nav-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero__bg-elements">
            <div class="floating-element floating-element--1"></div>
            <div class="floating-element floating-element--2"></div>
            <div class="floating-element floating-element--3"></div>
        </div>
        <div class="hero__container container">
            <div class="hero__content">
                <div class="hero__badge">
                    <i class="fas fa-leaf"></i>
                    <span>100% Clean Energy Solutions</span>
                </div>
                <h1 class="hero__title">Your Solar Journey, <span class="text-gradient">Simplified</span></h1>
                <p class="hero__description">
                    Compare Sri Lanka's top-rated solar installers, get transparent quotes, 
                    and manage your investment with our powerful analytics tools.
                </p>
                <div class="hero__stats">
                    <div class="stat">
                        <div class="stat__number">500+</div>
                        <div class="stat__label">Happy Customers</div>
                    </div>
                    <div class="stat">
                        <div class="stat__number">50+</div>
                        <div class="stat__label">Verified Installers</div>
                    </div>
                    <div class="stat">
                        <div class="stat__number">Rs2M+</div>
                        <div class="stat__label">Savings Generated</div>
                    </div>
                </div>
                <div class="hero__cta-group">
                    <a href="quotation.php" class="btn btn--primary btn--large hero__cta">
                        <i class="fas fa-calculator"></i>
                        Get a Free Quotation Now
                    </a>
                    <a href="#how-it-works" class="btn btn--outline btn--large">
                        <i class="fas fa-play-circle"></i>
                        Watch How It Works
                    </a>
                </div>
            </div>
            <div class="hero__image">
                <div class="hero__image-wrapper">
                    <img src="<?php echo URLROOT; ?>public/img/solar-home.png" alt="Solar powered home">
                    <div class="hero__image-overlay">
                        <div class="energy-indicator">
                            <div class="energy-indicator__icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div class="energy-indicator__text">
                                <span class="energy-value">4.2kW</span>
                                <span class="energy-label">Generated Today</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Proof -->
    <section class="social-proof">
        <div class="container">
            <h3 class="social-proof__title">Trusted by Sri Lanka's Leading Installation Companies</h3>
            <div class="social-proof__logos">
                <img src="/placeholder.svg?height=60&width=120" alt="Partner 1">
                <img src="/placeholder.svg?height=60&width=120" alt="Partner 2">
                <img src="/placeholder.svg?height=60&width=120" alt="Partner 3">
                <img src="/placeholder.svg?height=60&width=120" alt="Partner 4">
                <img src="/placeholder.svg?height=60&width=120" alt="Partner 5">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="features__header">
                <h2 class="section__title">Why Choose <span class="text-gradient">SolarSense</span>?</h2>
                <p class="section__subtitle">Powerful tools and insights to maximize your solar investment</p>
            </div>

            <div class="features__item">
                <div class="features__image">
                    <div class="feature-icon-large">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <img src="/placeholder.svg?height=400&width=500" alt="Performance Analytics">
                </div>
                <div class="features__content">
                    <div class="feature-badge">
                        <i class="fas fa-magic"></i>
                        <span>Smart Analytics</span>
                    </div>
                    <h3>Never Guess Your Performance Again</h3>
                    <p>Simply upload your monthly CEB SMS and let SolarSense do the work. Our smart analytics compare your actual generation to what it should be based on local weather, instantly flagging any issues.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Real-time performance monitoring</li>
                        <li><i class="fas fa-check-circle"></i> Weather-based predictions</li>
                        <li><i class="fas fa-check-circle"></i> Instant issue detection</li>
                    </ul>
                </div>
            </div>

            <div class="features__item features__item--reverse">
                <div class="features__content">
                    <div class="feature-badge">
                        <i class="fas fa-tools"></i>
                        <span>Proactive Care</span>
                    </div>
                    <h3>Proactive Maintenance, Zero Hassle</h3>
                    <p>Our system automatically alerts your installer when it detects a fault, enabling them to provide proactive service before you even notice a problem. View service history and provide feedback all in one place.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Automatic fault detection</li>
                        <li><i class="fas fa-check-circle"></i> Installer notifications</li>
                        <li><i class="fas fa-check-circle"></i> Service history tracking</li>
                    </ul>
                </div>
                <div class="features__image">
                    <div class="feature-icon-large">
                        <i class="fas fa-wrench"></i>
                    </div>
                    <img src="/placeholder.svg?height=400&width=500" alt="Proactive Maintenance">
                </div>
            </div>

            <div class="features__item">
                <div class="features__image">
                    <div class="feature-icon-large">
                        <i class="fas fa-piggy-bank"></i>
                    </div>
                    <img src="/placeholder.svg?height=400&width=500" alt="Profit Tracking">
                </div>
                <div class="features__content">
                    <div class="feature-badge">
                        <i class="fas fa-chart-pie"></i>
                        <span>Financial Insights</span>
                    </div>
                    <h3>Track Your Lifetime Profit</h3>
                    <p>Watch your investment grow with our Lifetime Profit Tracker. See your total accumulated savings and monitor your progress towards your yearly financial goals with a clear, visual progress bar.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Lifetime savings tracking</li>
                        <li><i class="fas fa-check-circle"></i> ROI calculations</li>
                        <li><i class="fas fa-check-circle"></i> Goal progress monitoring</li>
                    </ul>
                </div>
            </div>

            <div class="features__item features__item--reverse">
                <div class="features__content">
                    <div class="feature-badge">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Marketplace</span>
                    </div>
                    <h3>Upgrade and Expand Your System</h3>
                    <p>Explore our accessories store for the latest in solar batteries, smart meters, and EV chargers. Book installations and manage service subscriptions to maximize your solar investment and energy independence.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Premium accessories store</li>
                        <li><i class="fas fa-check-circle"></i> Professional installation</li>
                        <li><i class="fas fa-check-circle"></i> Service subscriptions</li>
                    </ul>
                </div>
                <div class="features__image">
                    <div class="feature-icon-large">
                        <i class="fas fa-battery-full"></i>
                    </div>
                    <img src="/placeholder.svg?height=400&width=500" alt="System Upgrades">
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <h2 class="section__title">How It <span class="text-gradient">Works</span></h2>
            <p class="section__subtitle">Get started with solar in just 3 simple steps</p>
            
            <div class="steps-container">
                <div class="steps">
                    <div class="step" data-step="1">
                        <div class="step__number">01</div>
                        <div class="step__icon">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <h3 class="step__title">Tell Us Your Needs</h3>
                        <p class="step__description">Use our simple form to outline your requirements. It's fast, free, and gets you started in minutes.</p>
                    </div>

                    <div class="step-connector"></div>

                    <div class="step" data-step="2">
                        <div class="step__number">02</div>
                        <div class="step__icon">
                            <i class="fas fa-users-viewfinder"></i>
                        </div>
                        <h3 class="step__title">Compare Verified Installers</h3>
                        <p class="step__description">We match you with top-rated, Super Admin-verified installation companies in your area.</p>
                    </div>

                    <div class="step-connector"></div>

                    <div class="step" data-step="3">
                        <div class="step__number">03</div>
                        <div class="step__icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3 class="step__title">Receive Transparent Quotes</h3>
                        <p class="step__description">Get detailed, easy-to-understand quotations directly from the installers you choose.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Installer Directory -->
    <section class="installers" id="installers">
        <div class="container">
            <h2 class="section__title">Find Verified Installers</h2>
            
            <div class="installer-search">
                <div class="search-bar">
                    <input type="text" placeholder="Search installers by name..." id="installer-search">
                    <button class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="filters">
                    <select id="district-filter">
                        <option value="">All Districts</option>
                        <option value="colombo">Colombo</option>
                        <option value="kandy">Kandy</option>
                        <option value="galle">Galle</option>
                        <option value="jaffna">Jaffna</option>
                    </select>
                    <select id="service-filter">
                        <option value="">All Services</option>
                        <option value="residential">Residential</option>
                        <option value="commercial">Commercial</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
            </div>

            <div class="installer-grid" id="installer-grid">
                <!-- Installers will be loaded via JavaScript -->
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials">
        <div class="container">
            <h2 class="section__title">What Our <span class="text-gradient">Customers</span> Say</h2>
            <p class="section__subtitle">Real stories from satisfied solar homeowners across Sri Lanka</p>
            
            <div class="testimonial-grid">
                <div class="testimonial">
                    <div class="testimonial__header">
                        <div class="testimonial__avatar">
                            <img src="/placeholder.svg?height=60&width=60" alt="Nimali P.">
                        </div>
                        <div class="testimonial__info">
                            <strong>Nimali P.</strong>
                            <div class="location-badge">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Kandy</span>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial__stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial__text">"SolarSense made finding the right installer so easy. The analytics dashboard helps me track my savings every month!"</p>
                    <div class="testimonial__metric">
                        <span class="metric-value">₹45,000</span>
                        <span class="metric-label">Saved This Year</span>
                    </div>
                </div>

                <div class="testimonial">
                    <div class="testimonial__header">
                        <div class="testimonial__avatar">
                            <img src="/placeholder.svg?height=60&width=60" alt="Rajesh S.">
                        </div>
                        <div class="testimonial__info">
                            <strong>Rajesh S.</strong>
                            <div class="location-badge">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Colombo</span>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial__stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial__text">"The proactive maintenance alerts saved me from a major system failure. Excellent service and platform!"</p>
                    <div class="testimonial__metric">
                        <span class="metric-value">99.2%</span>
                        <span class="metric-label">System Uptime</span>
                    </div>
                </div>

                <div class="testimonial">
                    <div class="testimonial__header">
                        <div class="testimonial__avatar">
                            <img src="/placeholder.svg?height=60&width=60" alt="Priya M.">
                        </div>
                        <div class="testimonial__info">
                            <strong>Priya M.</strong>
                            <div class="location-badge">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Galle</span>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial__stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial__text">"Transparent quotes and verified installers gave me confidence in my solar investment. Highly recommended!"</p>
                    <div class="testimonial__metric">
                        <span class="metric-value">3.2kW</span>
                        <span class="metric-label">System Size</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Installer CTA -->
    <section class="installer-cta">
        <div class="container">
            <div class="installer-cta__content">
                <h2>Are You a Solar Installer?</h2>
                <p>Join Sri Lanka's fastest-growing network of solar professionals. Connect with qualified homeowners, manage your fleet, and streamline your operations.</p>
                <a href="installer-registration.php" class="btn btn--primary btn--large">Register Your Company</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer__content">
                <div class="footer__column">
                    <div class="footer__logo">
                        <i class="fas fa-solar-panel"></i>
                        <span>SolarSense</span>
                    </div>
                    <p class="footer__mission">Empowering Sri Lankan homeowners to make informed solar decisions and maximize their renewable energy investments.</p>
                </div>

                <div class="footer__column">
                    <h4>For Homeowners</h4>
                    <ul class="footer__links">
                        <li><a href="#how-it-works">How It Works</a></li>
                        <li><a href="#installers">Find Installers</a></li>
                        <li><a href="<?php echo URLROOT; ?>/users/login">Login</a></li>
                        <li><a href="quotation.php">Get Quote</a></li>
                    </ul>
                </div>

                <div class="footer__column">
                    <h4>For Installers</h4>
                    <ul class="footer__links">
                        <li><a href="installer-registration.php">Registration</a></li>
                        <li><a href="installer-benefits.php">Benefits</a></li>
                        <li><a href="installer-login.php">Installer Login</a></li>
                        <li><a href="support.php">Support</a></li>
                    </ul>
                </div>

                <div class="footer__column">
                    <h4>Company</h4>
                    <ul class="footer__links">
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="privacy.php">Privacy Policy</a></li>
                        <li><a href="terms.php">Terms of Service</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer__bottom">
                <p>&copy; 2024 SolarSense. All rights reserved.</p>
                <div class="footer__social">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="<?php echo URLROOT; ?>/js/landing.js"></script>
