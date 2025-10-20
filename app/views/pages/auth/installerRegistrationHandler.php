<div class="error-page-wrapper">
    <div class="error-template text-center">
        <h2>Thank you for your response!</h2>
        <div class="error-details mb-4">
            You will hear from us soon!
        </div>
        <div class="error-actions">
            <a href="<?php echo URLROOT; ?>" class="btn btn-primary">
                <i class="fas fa-home me-2"></i>Go to Homepage
            </a>
        </div>
    </div>
</div>

<style>
    /* Make full screen flex center */
    .error-page-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;          /* full viewport height */
        width: 100vw;           /* full viewport width */
        background-color: #fff; /* optional */
    }

    .error-template {
        padding: 40px 15px;
    }

    .error-template h1 {
        font-size: 8rem;
        color: var(--color-primary, #007bff);
        margin-bottom: 0.5rem;
    }

    .error-template h2 {
        margin-bottom: 2rem;
    }

    .error-details {
        font-size: 1.2rem;
        color: #6c757d;
    }
</style>
