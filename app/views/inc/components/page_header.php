<?php
/**
 * Page Header Component
 * 
 * A flexible header component for displaying page title, description, and action buttons
 * 
 * @param array $config Configuration array:
 *   - title (string): Main heading text
 *   - description (string, optional): Subtitle/description text
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
?>

<div class="page-header mb-6">
    <div class="d-flex align-center justify-between">
        <div class="page-header-content">
            <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($title); ?></h1>
            <?php if ($description): ?>
                <p class="text-secondary"><?php echo htmlspecialchars($description); ?></p>
            <?php endif; ?>
        </div>
        <?php if (!empty($buttons)): ?>
            <div class="header-actions d-flex gap-2">
                <?php foreach ($buttons as $button): ?>
                    <a href="<?php echo $button['url']; ?>" 
                       class="btn <?php echo $button['class'] ?? 'btn-primary'; ?>" 
                       style="text-decoration: none;">
                        <?php if (!empty($button['icon'])): ?>
                            <i class="<?php echo $button['icon']; ?> mr-2"></i>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($button['label']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
