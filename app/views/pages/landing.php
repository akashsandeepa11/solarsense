<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SolarSense - Your Solar Journey, Simplified</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/landing.css">
    <style>
        .form-control {
            height: 40px !important;
            min-height: 40px !important;
            max-height: 40px !important;
            padding: 0.4rem 0.75rem !important;
            line-height: 1.2 !important;
            box-sizing: border-box !important;
        }
    </style>
</head>

<body>
    <!-- Header / Navigation  -->
    <header class="header" id="header">
        <nav class="nav container d-flex align-center justify-between">
            <div class="d-flex align-center gap-2">
                <?php require APPROOT . '/views/inc/components/logo.php'; ?>
            </div>

            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list d-flex">
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

            <div class="nav__buttons d-flex gap-2">
                <a href="<?php echo URLROOT; ?>/auth/installer_registration"
                    class="btn btn-primary-outline btn-sm">Solar Company Registration</a>
                <a href="<?php echo URLROOT; ?>/auth/login" class="btn btn-primary btn-sm">Login</a>
            </div>

            <div class="nav__toggle" id="nav-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </nav>
    </header>

    <!-- Hero Section  -->
    <section class="hero">
        <div class="hero__bg-elements">
            <div class="floating-element floating-element--1"></div>
            <div class="floating-element floating-element--2"></div>
            <div class="floating-element floating-element--3"></div>
            <div class="floating-element floating-element--4"></div>
            <div class="floating-element floating-element--5"></div>
            <div class="floating-element floating-element--9"></div>>
        </div>
        <div class="hero__container container">
            <div class="row align-center">
                <div class="col-lg-6">
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
                                <div class="stat__number"><?php echo number_format($data['homeowner_count']); ?>+</div>
                                <div class="stat__label">Happy Customers</div>
                            </div>
                            <div class="stat">
                                <div class="stat__number"><?php echo number_format($data['installer_count']); ?>+</div>
                                <div class="stat__label">Verified Installers</div>
                            </div>
                        </div>
                        <div class="hero__cta-group">
                            <a href="#quotation-section" class="btn btn-primary btn-lg hero__cta quote-trigger">
                                <i class="fas fa-calculator"></i>
                                Get a Free Quotation Now
                            </a>
                            <a href="#how-it-works" class="btn btn-primary-outline btn-lg">
                                <i class="fas fa-play-circle"></i>
                                Watch How It Works
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero__image">
                        <div class="hero__image-wrapper">
                            <img src="https://images.unsplash.com/photo-1509391366360-2e959784a276?w=800&h=600&fit=crop"
                                alt="Solar powered home">
                            <!-- <div class="energy-indicator">
                                <div class="energy-indicator__icon">
                                    <i class="fas fa-bolt"></i>
                                </div>
                                <div class="energy-indicator__text">
                                    <span class="energy-value">4.2kW</span>
                                    <span class="energy-label">Generated Today</span>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Proof  -->
    <section class="social-proof">
        <div class="container">
            <h3 class="social-proof__title">Trusted by Sri Lanka's Leading Installation Companies</h3>
            <div class="social-proof__logos">
                <img src="https://images.unsplash.com/photo-1599305445671-ac291c95aaa9?w=120&h=60&fit=crop"
                    alt="Partner 1">
                <img src="https://images.unsplash.com/photo-1560179707-f14e90ef3623?w=120&h=60&fit=crop"
                    alt="Partner 2">
                <img src="https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=120&h=60&fit=crop"
                    alt="Partner 3">
                <img src="https://images.unsplash.com/photo-1572883454114-1cf0031ede2a?w=120&h=60&fit=crop"
                    alt="Partner 4">
                <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=120&h=60&fit=crop"
                    alt="Partner 5">
            </div>
        </div>
    </section>

    <!-- Features Section  -->
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
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=500&h=400&fit=crop"
                        alt="Performance Analytics">
                </div>
                <div class="features__content">
                    <div class="feature-badge">
                        <i class="fas fa-magic"></i>
                        <span>Smart Analytics</span>
                    </div>
                    <h3>Never Guess Your Performance Again</h3>
                    <p>Simply upload your monthly CEB SMS and let SolarSense do the work. Our smart analytics compare
                        your actual generation to what it should be based on local weather, instantly flagging any
                        issues.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Solar panel performance monitoring</li>
                        <li><i class="fas fa-check-circle"></i> Weather-based predictions</li>
                        <li><i class="fas fa-check-circle"></i> Instant underperformance detection</li>
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
                    <p>Our system automatically alerts your installer when it detects a fault, enabling them to provide
                        proactive service before you even notice a problem. View service history and provide feedback
                        all in one place.</p>
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
                    <img src="https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=500&h=400&fit=crop"
                        alt="Proactive Maintenance">
                </div>
            </div>

            <div class="features__item features__item--reverse">
                <div class="features__content">
                    <div class="feature-badge">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Marketplace</span>
                    </div>
                    <h3>Upgrade and Expand Your System</h3>
                    <p>Explore our accessories store for the latest in solar batteries, smart meters, and EV chargers.
                        Book installations and manage service subscriptions to maximize your solar investment and energy
                        independence.</p>
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
                    <img src="https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?w=500&h=400&fit=crop"
                        alt="System Upgrades">
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works  -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <h2 class="section__title text-center">How It <span class="text-gradient">Works</span></h2>
            <p class="section__subtitle text-center">Get started with solar in just 3 simple steps</p>

            <div class="steps-container">
                <div class="steps">
                    <div class="step" data-step="1">
                        <div class="step__number">01</div>
                        <div class="step__icon">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <h3 class="step__title">Tell Us Your Needs</h3>
                        <p class="step__description">Use our simple form to outline your requirements. It's fast, free,
                            and gets you started in minutes.</p>
                    </div>

                    <div class="step-connector"></div>

                    <div class="step" data-step="2">
                        <div class="step__number">02</div>
                        <div class="step__icon">
                            <i class="fas fa-users-viewfinder"></i>
                        </div>
                        <h3 class="step__title">Compare Verified Installers</h3>
                        <p class="step__description">We match you with top-rated, Super Admin-verified installation
                            companies in your area.</p>
                    </div>

                    <div class="step-connector"></div>

                    <div class="step" data-step="3">
                        <div class="step__number">03</div>
                        <div class="step__icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3 class="step__title">Receive Transparent Quotes</h3>
                        <p class="step__description">Get detailed, easy-to-understand quotations directly from the
                            installers you choose.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials  -->
    <section class="testimonials">
        <div class="container">
            <h2 class="section__title text-center">What Our <span class="text-gradient">Customers</span> Say</h2>
            <p class="section__subtitle text-center">Real stories from satisfied solar homeowners across Sri Lanka</p>

            <div class="testimonial-grid">
                <div class="testimonial">
                    <div class="testimonial__header">
                        <div class="testimonial__avatar">
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=60&h=60&fit=crop"
                                alt="Nimali P.">
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
                    <p class="testimonial__text">"SolarSense made finding the right installer so easy. The analytics
                        dashboard helps me track my savings every month!"</p>
                    <div class="testimonial__metric">
                        <span class="metric-value">LKR 45,000</span>
                        <span class="metric-label">Saved This Year</span>
                    </div>
                </div>

                <div class="testimonial">
                    <div class="testimonial__header">
                        <div class="testimonial__avatar">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=60&h=60&fit=crop"
                                alt="Rajesh S.">
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
                    <p class="testimonial__text">"The proactive maintenance alerts saved me from a major system failure.
                        Excellent service and platform!"</p>
                    <div class="testimonial__metric">
                        <span class="metric-value">99.2%</span>
                        <span class="metric-label">System Uptime</span>
                    </div>
                </div>

                <div class="testimonial">
                    <div class="testimonial__header">
                        <div class="testimonial__avatar">
                            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=60&h=60&fit=crop"
                                alt="Priya M.">
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
                    <p class="testimonial__text">"Transparent quotes and verified installers gave me confidence in my
                        solar investment. Highly recommended!"</p>
                    <div class="testimonial__metric">
                        <span class="metric-value">3.2kW</span>
                        <span class="metric-label">System Size</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quotation Calculator -->
    <section class="quotation-section" id="quotation-section">
        <div class="container">
            <div class="quotation-container">
                <div class="quotation-header">
                    <span class="quotation-badge"><i class="fas fa-solar-panel"></i> Free Estimate</span>
                    <h2>Book a Site Visit</h2>
                    <p>Answer 3 simple questions. One step closer to installation!</p>
                </div>

                <div class="quotation-progress">
                    <div class="progress-step active" data-step="1">
                        <div class="progress-circle">1</div>
                        <div class="progress-label">Solar Company</div>
                    </div>
                    <div class="progress-step" data-step="2">
                        <div class="progress-circle">2</div>
                        <div class="progress-label">Your Electricity Use</div>
                    </div>
                    <div class="progress-step" data-step="3">
                        <div class="progress-circle">3</div>
                        <div class="progress-label">Site Visit</div>
                    </div>
                </div>

                <div class="quotation-content">
                    <div class="quotation-step active" data-step="1">
                        <h3>Who should we connect you with?</h3>
                        <p class="step-intro">Choose an installer to get your quote. All listed companies are verified
                            and trusted by SolarSense.</p>
                        <p class="form-helper" style="margin-bottom: 1rem;">You can change this later.</p>
                        <div class="installer-options-wrapper">
                            <div class="installer-options" id="installer-options">
                                <?php foreach ($data['installers'] as $installer): ?>
                                    <div class="installer-option"
                                        onclick="selectInstaller(<?php echo htmlspecialchars(json_encode($installer)); ?>, this)"
                                        data-id="<?php echo $installer->company_id; ?>">
                                        <div class="installer-card-head">
                                            <span class="rating"><i class="fas fa-check-circle"></i> Verified</span>
                                            <span class="installer-option__meta"><?php echo $installer->years_experience; ?>
                                                yrs exp.</span>
                                        </div>
                                        <div class="installer-option__name"><?php echo $installer->company_name; ?></div>
                                        <div class="installer-option__meta">
                                            <i class="fas fa-map-marker-alt"></i> <?php echo $installer->district; ?>
                                        </div>
                                        <div class="installer-option__price" style="margin-top: 10px; font-size: 0.85rem;">
                                            <span><?php echo $installer->complete_projects; ?>+ Installs</span>
                                            <span class="text-primary">View Details</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="quotation-step" data-step="2">
                        <h3>Tell us about your electricity usage</h3>
                        <p class="step-intro">We'll use this to recommend the right system size for you.</p>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="bill-amount">How much is your monthly electricity
                                    bill?</label>
                                <select id="bill-amount" class="form-control"
                                    style="height:40px !important; min-height:40px !important; max-height:40px !important; padding:0.4rem 0.75rem !important; box-sizing:border-box;">
                                    <option value="low">Less than Rs. 15,000</option>
                                    <option value="medium" selected>Rs. 15,000 – Rs. 30,000</option>
                                    <option value="high">Rs. 30,000 – Rs. 45,000</option>
                                    <option value="very-high">Over Rs. 45,000</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="roof-type">What type of roof do you have?</label>
                                <select id="roof-type" class="form-control"
                                    style="height:40px !important; min-height:40px !important; max-height:40px !important; padding:0.4rem 0.75rem !important; box-sizing:border-box;">
                                    <option value="tile" selected>Tile Roof</option>
                                    <option value="metal">Metal (Zinc or Calicut)</option>
                                    <option value="flat">Flat Concrete</option>
                                </select>
                                <p class="form-helper">If you're not sure, our installer will confirm this later.</p>
                            </div>
                        </div>

                        <div class="form-row" style="margin-top: 1.25rem;">
                            <div class="form-group">
                                <label class="form-label" for="backup-needs">What is your property type?</label>
                                <select id="backup-needs" class="form-control"
                                    style="height:40px !important; min-height:40px !important; max-height:40px !important; padding:0.4rem 0.75rem !important; box-sizing:border-box;">
                                    <option value="house" selected>House</option>
                                    <option value="apartment">Apartment</option>
                                    <option value="office">Office</option>
                                    <option value="commercial">Commercial Building</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="preference">Do you have an exisitng PV system?</label>
                                <select id="preference" class="form-control"
                                    style="height:40px !important; min-height:40px !important; max-height:40px !important; padding:0.4rem 0.75rem !important; box-sizing:border-box;">
                                    <option value="No" selected>No, I'm looking install.</option>
                                    <option value="Yes-1">Yes, I want to register with SolarSense</option>
                                    <option value="Yes-2">Yes, I'm looking to upsize and register.</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="quotation-step" data-step="3">
                        <h3>Book Your Site Visit Today!</h3>
                        <div class="customer-form">
                            <p class="form-section-title">How can the installer reach you?</p>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="customer-name">Your Name</label>
                                    <input id="customer-name" type="text" class="form-control"
                                        placeholder="e.g. Kamal Perera"
                                        style="height:40px !important; padding:0.4rem 0.75rem !important; box-sizing:border-box;">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="customer-phone">Phone Number</label>
                                    <input id="customer-phone" type="tel" class="form-control"
                                        placeholder="e.g. 077 123 4567"
                                        style="height:40px !important; padding:0.4rem 0.75rem !important; box-sizing:border-box;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="customer-email">Physical Address</label>
                                <input id="customer-address" type="text" class="form-control"
                                    placeholder="e.g. No. 6, Main Street, Colombo"
                                    style="height:40px !important; padding:0.4rem 0.75rem !important; box-sizing:border-box;">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="customer-email">Email Address</label>
                                <input id="customer-email" type="email" class="form-control"
                                    placeholder="e.g. kamal@email.com"
                                    style="height:40px !important; padding:0.4rem 0.75rem !important; box-sizing:border-box;">
                            </div>
                            <p class="form-helper">Only your contact details are shared with your selected installer so
                                they can reach out to you.</p>
                        </div>
                    </div>

                    <div class="quotation-step" data-step="4">
                        <div class="success-message">
                            <div class="success-icon"><i class="fas fa-check-circle"></i></div>
                            <h3>Request Submitted!</h3>
                            <p>We've shared your contact details with <span id="success-installer"></span>. They will
                                reach out to you within 2-3 business days to discuss your solar needs and provide a
                                personalized quote.</p>
                            <button type="button" class="btn btn-primary" id="start-over-btn">Start a New Quote</button>
                        </div>
                    </div>
                </div>

                <div class="quotation-footer" id="quotation-footer">
                    <button type="button" class="btn btn-primary-outline" id="prev-step-btn">&larr; Back</button>
                    <button type="button" class="btn btn-primary" id="next-step-btn">Continue</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Installer CTA  -->
    <section class="installer-cta">
        <div class="container">
            <div class="installer-cta__content">
                <h2>Are You a Solar Installer?</h2>
                <p>Join Sri Lanka's fastest-growing network of solar professionals. Connect with qualified homeowners,
                    manage your fleet, and streamline your operations.</p>
                <a href="installer_registration" class="btn btn-lg">Register Your Company</a>
            </div>
        </div>
    </section>

    <!-- Footer  -->
    <footer class="footer">
        <div class="container">
            <div class="footer__content">
                <div class="footer__column">
                    <div class="footer__logo footer__logo--standalone">
                        <?php
                        $logoVariant = 'white';
                        $logoWrapperClass = 'logo-component logo-component--footer';
                        $logoLinkClass = 'd-flex align-center text-decoration-none hover:no-underline';
                        require APPROOT . '/views/inc/components/logo.php';
                        unset($logoVariant, $logoWrapperClass, $logoLinkClass);
                        ?>
                    </div>
                    <p class="footer__mission">Empowering Sri Lankan homeowners to make informed solar decisions and
                        maximize their renewable energy investments.</p>
                </div>

                <div class="footer__column">
                    <h4>For Homeowners</h4>
                    <ul class="footer__links">
                        <li><a href="#how-it-works">How It Works</a></li>
                        <li><a href="#installers">Find Installers</a></li>
                        <li><a href="<?php echo URLROOT; ?>/users/login">Login</a></li>
                        <li><a href="#quotation-section" class="quote-trigger">Get Quote</a></li>
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
                <p>&copy; 2025 SolarSense. All rights reserved.</p>
                <div class="footer__social">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Expose server-side constants to JavaScript
        const URLROOT = "<?php echo URLROOT; ?>";
    </script>
    <script src="<?php echo URLROOT; ?>/public/js/landing.js"></script>