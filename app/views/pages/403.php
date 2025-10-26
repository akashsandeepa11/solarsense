<div class="error-page-wrapper">
    <div class="error-template text-center">
        <h1>403</h1>
        <h2>Access Forbidden</h2>
        <div class="error-details mb-4">
            You do not have permission to access this page.
        </div>
        <div class="error-actions">
            <a href="<?php echo URLROOT; ?>/<?php echo strtolower($_SESSION['user_type'] ?? 'pages'); ?>" class="btn btn-primary">
                <i class="fas fa-home me-2"></i>Go to My Dashboard
            </a>
            <a href="<?php echo URLROOT; ?>/auth/logout" class="btn btn-secondary ms-2">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
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
        height: 100vh;
        width: 100vw;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: fixed;
        top: 0;
        left: 0;
        z-index: 10000;
    }

    .error-template {
        padding: 40px 15px;
        background: white;
        border-radius: 1rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        max-width: 600px;
        margin: 0 1rem;
    }

    .error-template h1 {
        font-size: 8rem;
        color: #ef4444;
        margin-bottom: 0.5rem;
        font-weight: 700;
        text-shadow: 0 4px 10px rgba(239, 68, 68, 0.2);
    }

    .error-template h2 {
        margin-bottom: 2rem;
        color: #1f2937;
        font-weight: 600;
    }

    .error-details {
        font-size: 1.2rem;
        color: #6b7280;
        margin-bottom: 2rem;
    }

    .error-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #fe9630;
        color: white;
    }

    .btn-primary:hover {
        background-color: #e67e1a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(254, 150, 48, 0.4);
    }

    .btn-secondary {
        background-color: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
    }

    .me-2 {
        margin-right: 0.5rem;
    }

    .ms-2 {
        margin-left: 0.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .error-template h1 {
            font-size: 5rem;
        }

        .error-template h2 {
            font-size: 1.5rem;
        }

        .error-details {
            font-size: 1rem;
        }

        .error-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .ms-2 {
            margin-left: 0;
        }
    }
</style>
