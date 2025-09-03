<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 mx-auto text-center">
            <div class="error-template">
                <h1>404</h1>
                <h2>Page Not Found</h2>
                <div class="error-details mb-4">
                    Sorry, the page you requested could not be found.
                </div>
                <div class="error-actions">
                    <a href="<?php echo URLROOT; ?>" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>Go to Homepage
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .error-template {
        padding: 40px 15px;
    }
    
    .error-template h1 {
        font-size: 8rem;
        color: var(--color-primary, #007bff);
    }
    
    .error-template h2 {
        margin-bottom: 2rem;
    }
    
    .error-details {
        font-size: 1.2rem;
        color: #6c757d;
    }
</style>
