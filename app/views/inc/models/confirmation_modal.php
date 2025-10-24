<?php
/**
 * Confirmation Modal Component
 * 
 * A reusable customizable confirmation modal for delete, confirm, or warning actions
 * 
 * @param array $config Configuration array:
 *   - modal_id (string): Unique ID for the modal (required)
 *   - title (string): Modal title text
 *   - icon (string): Font Awesome icon class (default: 'fas fa-exclamation-triangle')
 *   - icon_color (string): Icon color class (default: 'text-warning')
 *   - heading (string): Modal heading/question text
 *   - message (string): Modal message/description text
 *   - subject (string, optional): Subject name (will be bold in message)
 *   - confirm_text (string): Confirm button text (default: 'Confirm')
 *   - cancel_text (string): Cancel button text (default: 'Cancel')
 *   - confirm_action (string): Action URL or onclick handler
 *   - confirm_method (string): Form method - 'GET', 'POST', 'onclick' (default: 'POST')
 *   - confirm_class (string): Button class (default: 'btn-danger')
 *   - confirm_icon (string): Confirm button icon class (optional)
 *   - cancel_icon (string): Cancel button icon class (default: 'fas fa-times')
 */

if (!isset($config)) {
    $config = [];
}

$modalId = $config['modal_id'] ?? 'confirmationModal';
$title = $config['title'] ?? 'Confirm Action';
$icon = $config['icon'] ?? 'fas fa-exclamation-triangle';
$iconColor = $config['icon_color'] ?? 'text-warning';
$heading = $config['heading'] ?? 'Are you sure?';
$message = $config['message'] ?? 'This action cannot be undone.';
$subject = $config['subject'] ?? '';
$confirmText = $config['confirm_text'] ?? 'Confirm';
$cancelText = $config['cancel_text'] ?? 'Cancel';
$confirmAction = $config['confirm_action'] ?? '';
$confirmMethod = $config['confirm_method'] ?? 'POST';
$confirmClass = $config['confirm_class'] ?? 'btn-danger';
$confirmIcon = $config['confirm_icon'] ?? '';
$cancelIcon = $config['cancel_icon'] ?? 'fas fa-times';
?>

<!-- Confirmation Modal -->
<div id="<?php echo htmlspecialchars($modalId); ?>" class="custom-modal" style="display: none;">
    <div class="modal-overlay" onclick="closeConfirmationModal('<?php echo htmlspecialchars($modalId); ?>')"></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="<?php echo htmlspecialchars($icon); ?> <?php echo htmlspecialchars($iconColor); ?> mr-2"></i><?php echo htmlspecialchars($title); ?>
                </h5>
                <button type="button" class="btn-close" onclick="closeConfirmationModal('<?php echo htmlspecialchars($modalId); ?>')" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <p class="text-center mb-4">
                    <i class="<?php echo htmlspecialchars($icon); ?> <?php echo htmlspecialchars($iconColor); ?>" style="font-size: 3rem;"></i>
                </p>
                <h4 class="text-center mb-2"><?php echo htmlspecialchars($heading); ?></h4>
                <p class="text-center text-secondary mb-4">
                    <?php echo htmlspecialchars($message); ?>
                    <?php if (!empty($subject)): ?>
                        <strong><?php echo htmlspecialchars($subject); ?></strong>
                    <?php endif; ?>
                </p>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" onclick="closeConfirmationModal('<?php echo htmlspecialchars($modalId); ?>')">
                    <i class="<?php echo htmlspecialchars($cancelIcon); ?> mr-2"></i><?php echo htmlspecialchars($cancelText); ?>
                </button>
                
                <?php if ($confirmMethod === 'POST'): ?>
                    <form action="<?php echo htmlspecialchars($confirmAction); ?>" method="POST" style="display: inline;">
                        <button type="submit" class="btn btn-sm <?php echo htmlspecialchars($confirmClass); ?>">
                            <?php if (!empty($confirmIcon)): ?>
                                <i class="<?php echo htmlspecialchars($confirmIcon); ?> mr-2"></i>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($confirmText); ?>
                        </button>
                    </form>
                <?php elseif ($confirmMethod === 'GET'): ?>
                    <a href="<?php echo htmlspecialchars($confirmAction); ?>" class="btn btn-sm <?php echo htmlspecialchars($confirmClass); ?>" style="text-decoration: none;">
                        <?php if (!empty($confirmIcon)): ?>
                            <i class="<?php echo htmlspecialchars($confirmIcon); ?> mr-2"></i>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($confirmText); ?>
                    </a>
                <?php else: ?>
                    <button type="button" class="btn btn-sm <?php echo htmlspecialchars($confirmClass); ?>" onclick="<?php echo htmlspecialchars($confirmAction); ?>">
                        <?php if (!empty($confirmIcon)): ?>
                            <i class="<?php echo htmlspecialchars($confirmIcon); ?> mr-2"></i>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($confirmText); ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Styles -->
<style>
.custom-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1050;
    display: flex;
    align-items: center;
    justify-content: center;
}

.custom-modal.show {
    display: flex;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.3s ease-in-out;
}

.modal-dialog {
    position: relative;
    z-index: 1051;
    width: 90%;
    max-width: 500px;
    animation: slideUp 0.3s ease-out;
}

.modal-content {
    background-color: #ffffff;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.modal-header {
    padding: 1.5rem;
    background-color: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #212121;
    margin: 0;
}

.modal-body {
    padding: 2rem 1.5rem;
}

.modal-footer {
    padding: 1.5rem;
    background-color: #f9fafb;
    border-top: 1px solid #e5e7eb;
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
}

.btn-close {
    width: 1.5rem;
    height: 1.5rem;
    background: transparent;
    border: none;
    cursor: pointer;
    opacity: 0.5;
    transition: opacity 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

.btn-close:hover {
    opacity: 1;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

<!-- Modal JavaScript Functions -->
<script>
function showConfirmationModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeConfirmationModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Close modal when clicking overlay or pressing Escape
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.custom-modal').forEach(function(modal) {
        const overlay = modal.querySelector('.modal-overlay');
        if (overlay) {
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    closeConfirmationModal(modal.id);
                }
            });
        }
    });
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.custom-modal.show').forEach(function(modal) {
                closeConfirmationModal(modal.id);
            });
        }
    });
});
</script>
