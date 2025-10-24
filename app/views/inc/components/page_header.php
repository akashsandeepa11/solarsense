<?php
/**
 * Page Header Component
 * 
 * A flexible header component for displaying page title, description, and action buttons
 * Supports back button for nested pages and breadcrumb navigation
 * 
 * @param array $config Configuration array:
 *   - title (string): Main heading text
 *   - description (string, optional): Subtitle/description text
 *   - back_url (string, optional): URL for back button (if nested page)
 *   - back_label (string, optional): Custom back button label (default: 'Back')
 *   - show_back (bool, optional): Show back button (default: false)
 *   - buttons (array, optional): Array of button configs:
 *       - label (string): Button text
 *       - url (string): Button link
 *       - icon (string, optional): Font Awesome icon class
 *       - class (string, optional): Additional CSS classes (default: 'btn-primary')
 */

if (!isset($config)) {
    $config = [];
}

$title = $config['title'] ?? 'Page Title';
$description = $config['description'] ?? '';
$buttons = $config['buttons'] ?? [];
$showBack = $config['show_back'] ?? false;
$backUrl = $config['back_url'] ?? '';
$backLabel = $config['back_label'] ?? 'Back';
?>

<style>
    /* Page Header Component */
    .page-header {
        background: #ffffff;
        padding: 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
        width: 100%;
    }

    .page-header-inner {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        width: 100%;
        flex-wrap: nowrap;
    }

    .page-header-content {
        flex: 1 1 auto;
        min-width: 0;
    }

    .page-header h1 {
        margin: 0;
        padding: 0;
        font-size: 1.875rem !important;
        font-weight: 700;
        color: #212121;
        line-height: 1.2;
    }

    .page-header p {
        margin: 0.5rem 0 0 0;
        padding: 0;
        font-size: 0.95rem;
        color: #6b7280;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
        flex-shrink: 0;
        flex-wrap: nowrap;
        align-items: center;
        justify-content: flex-end;
        white-space: nowrap;
    }

    .header-actions .btn {
        white-space: nowrap;
        flex-shrink: 0;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0;
        color: #6b7280;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
        margin-bottom: 0.75rem;
    }

    .back-button:hover {
        color: #212121;
        gap: 0.75rem;
    }

    .back-button i {
        transition: transform 0.2s ease-in-out;
    }

    .back-button:hover i {
        transform: translateX(-2px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .page-header {
            padding: 1rem;
        }

        .page-header-inner {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .header-actions {
            width: 100%;
            flex-direction: column;
            margin-top: 0.5rem;
            align-items: stretch;
        }

        .header-actions a,
        .header-actions button {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .page-header h1 {
            font-size: 1.5rem !important;
        }
    }

    @media (max-width: 576px) {
        .page-header {
            padding: 0.75rem;
        }

        .page-header-inner {
            gap: 0.5rem;
        }

        .page-header h1 {
            font-size: 1.25rem !important;
        }

        .page-header p {
            font-size: 0.85rem;
        }

        .back-button {
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }
    }
</style>

<?php if ($showBack && $backUrl): ?>
    <a href="<?php echo htmlspecialchars($backUrl); ?>" class="back-button">
        <i class="fas fa-arrow-left"></i>
        <span><?php echo htmlspecialchars($backLabel); ?></span>
    </a>
<?php endif; ?>

<div class="page-header mb-6">
    <div class="page-header-inner">
        <div class="page-header-content">
            <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($title); ?></h1>
            <?php if ($description): ?>
                <p class="text-secondary"><?php echo htmlspecialchars($description); ?></p>
            <?php endif; ?>
        </div>
        <?php if (!empty($buttons)): ?>
            <div class="header-actions">
                <?php foreach ($buttons as $button): ?>
                    <a href="<?php echo htmlspecialchars($button['url']); ?>" 
                       class="btn <?php echo htmlspecialchars($button['class'] ?? 'btn-primary'); ?>" 
                       style="text-decoration: none;">
                        <?php if (!empty($button['icon'])): ?>
                            <i class="<?php echo htmlspecialchars($button['icon']); ?> mr-2"></i>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($button['label']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
