<style>
    body {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        overflow-y: auto;
    }

    .success-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, #fe9630 0%, #d67000 85%, #c26300 100%);
        z-index: -1;
    }

    .success-background::before,
    .success-background::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .success-background::before {
        width: 400px;
        height: 400px;
        bottom: -200px;
        left: -150px;
    }

    .success-background::after {
        width: 400px;
        height: 400px;
        bottom: -150px;
        left: -200px;
    }

    .success-page-container {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
    }

    /* Success header with branding */
    .success-branding {
        text-align: center;
        margin-bottom: 2rem;
        color: white;
    }

    .success-branding h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .success-branding p {
        font-size: 1.2rem;
        opacity: 0.95;
    }

    /* Make logo bigger */
    .success-branding .logo-component {
        transform: scale(1.5);
        margin-bottom: 2rem;
        display: inline-block;
    }

    /* Ensure logo text is white */
    .success-branding .logo-component .logo-text,
    .success-branding .logo-component a,
    .success-branding .logo-component span {
        color: white !important;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Full screen centered layout - Updated for centered on background */
    .registration-success-wrapper {
        width: 100%;
        max-width: 900px;
    }

    .success-template {
        background: white;
        padding: 60px 40px;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        max-width: 900px;
        width: 100%;
    }

    .success-icon {
        font-size: 6rem;
        color: #22c55e;
        animation: scaleIn 0.5s ease-out;
    }

    @keyframes scaleIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .success-title {
        font-size: 2rem;
        color: #1e293b;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .success-subtitle {
        font-size: 1.5rem;
        color: #64748b;
        margin-bottom: 2rem;
        font-weight: 400;
    }

    .success-details {
        font-size: 1.1rem;
        color: #475569;
        line-height: 1.6;
    }

    .alert {
        border-radius: 12px;
        padding: 1.5rem;
        margin: 2rem auto;
        max-width: 600px;
    }

    .alert-info {
        background-color: #e0f2fe;
        border: 1px solid #0ea5e9;
        color: #0c4a6e;
    }

    .alert h5 {
        color: #0c4a6e;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .alert ul {
        list-style-type: none;
        padding-left: 0;
    }

    .alert ul li {
        position: relative;
        padding-left: 1.5rem;
    }

    .alert ul li:before {
        content: "✓";
        position: absolute;
        left: 0;
        color: #0ea5e9;
        font-weight: bold;
    }

    .contact-info {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 12px;
        margin: 2rem auto;
    }

    .contact-info p {
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .success-actions {
        margin-top: 2rem;
    }

    .btn-lg {
        padding: 12px 30px;
        font-size: 1.1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #fe9630;
        border-color: #fe9630;
    }

    .btn-primary:hover {
        background-color: #e8851c;
        border-color: #e8851c;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(254, 150, 48, 0.3);
    }

    .btn-outline-secondary {
        border: 2px solid #64748b;
        color: #64748b;
    }

    .btn-outline-secondary:hover {
        background-color: #64748b;
        color: white;
        transform: translateY(-2px);
    }

    .text-left {
        text-align: left !important;
    }

    .d-inline-block {
        display: inline-block !important;
    }

    .ml-3 {
        margin-left: 1rem;
    }

    .mr-2 {
        margin-right: 0.5rem;
    }

    .mb-0 {
        margin-bottom: 0;
    }

    .mb-2 {
        margin-bottom: 0.5rem;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .mb-5 {
        margin-bottom: 3rem;
    }

    .pl-4 {
        padding-left: 1.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .success-page-container {
            padding: 1rem;
        }

        .success-branding h1 {
            font-size: 2rem;
        }

        .success-branding p {
            font-size: 1rem;
        }

        .success-branding .logo-component {
            transform: scale(1.2);
            margin-bottom: 1.5rem;
        }

        .success-template {
            padding: 40px 20px;
        }

        .success-icon {
            font-size: 4rem;
        }

        .success-title {
            font-size: 1.5rem;
        }

        .success-subtitle {
            font-size: 1.2rem;
        }

        .btn-lg {
            display: block;
            width: 100%;
            margin-bottom: 1rem;
        }

        .ml-3 {
            margin-left: 0;
        }
    }
</style>

<div class="success-background"></div>

<div class="success-page-container">
    <div>
        <!-- Branding Header -->
        <div class="success-branding">
            <?php
            $logoVariant = 'white';
            $logoWrapperClass = 'logo-component logo-component--footer';
            $logoLinkClass = 'd-flex align-center text-decoration-none hover:no-underline';
            require APPROOT . '/views/inc/components/logo.php';
            unset($logoVariant, $logoWrapperClass, $logoLinkClass);
            ?>
            <p>Registration Successful - Welcome Aboard!</p>
        </div>

        <div class="registration-success-wrapper">
        <div class="success-template text-center">
        <div class="success-icon mb-4">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="success-title">Registration Request Submitted Successfully!</h1>
        <h2 class="success-subtitle">Thank you for choosing SolarSense</h2>
        <div class="success-details mb-5">
            <p class="mb-3">Your company registration request has been received and is now under review by our verification team.</p>
            <div class="alert alert-info d-inline-block text-left">
                <h5 class="mb-3"><i class="fas fa-info-circle mr-2"></i>What happens next?</h5>
                <ul class="mb-0 pl-4">
                    <li class="mb-2"><strong>Step 1:</strong> Our team will review your application within 2-3 business days</li>
                    <li class="mb-2"><strong>Step 2:</strong> You may be contacted for additional documents or clarifications</li>
                    <li class="mb-2"><strong>Step 3:</strong> Once approved, you'll receive your login credentials via email</li>
                    <li><strong>Step 4:</strong> Start managing your solar installations on SolarSense!</li>
                </ul>
            </div>
        </div>
        <div class="contact-info mb-4">
            <p class="text-muted">
                <i class="fas fa-envelope mr-2"></i>
                Questions? Contact us at <a href="mailto:support@solarsense.com" class="text-primary">support@solarsense.com</a>
            </p>
            <p class="text-muted">
                <i class="fas fa-phone mr-2"></i>
                Or call us at <a href="tel:+94112345678" class="text-primary">+94 11 234 5678</a>
            </p>
        </div>
        <div class="success-actions">
            <a href="<?php echo URLROOT; ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-home mr-2"></i>Go to Homepage
            </a>
            <a href="<?php echo URLROOT; ?>/pages/about" class="btn btn-outline-secondary btn-lg ml-3">
                <i class="fas fa-info-circle mr-2"></i>Learn More About Us
            </a>
        </div>
        </div>
        </div>
    </div>
</div>
