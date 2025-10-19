<?php
// --- Component Configuration ---

// Configuration setup
$cfg = $textareaConfig ?? [];
$id = $cfg['id'] ?? ('textarea_' . uniqid());
$name = $cfg['name'] ?? $id;
$label = $cfg['label'] ?? 'Some Fancy Label';
$value = $cfg['value'] ?? '';
$icon = $cfg['icon'] ?? ''; // Expects Font Awesome classes e.g., "fas fa-comment"
$required = !empty($cfg['required']); // This boolean is the key
$error = $cfg['error'] ?? ''; // Error message
$wrapperClass = $cfg['wrapperClass'] ?? '';
$textareaClass = $cfg['textareaClass'] ?? '';
$rows = $cfg['rows'] ?? 4; // Number of rows for textarea
$placeholder = $cfg['placeholder'] ?? '';

// This block ensures the CSS and JS are only included once on the page.
if (!defined('TEXTAREA_BEM_ASSETS')):
    define('TEXTAREA_BEM_ASSETS', true); ?>
<style>
    /* These styles are specific to the .textarea component */
    .textarea {
        position: relative;
    }
    .textarea__label {
        position: absolute;
        left: 0;
        top: 0;
        padding: 0.25rem;
        margin-top: 0.6rem;
        white-space: nowrap;
        transform: translate(0, 0);
        transform-origin: 0 0;
        background: #fff;
        transition: transform 120ms ease-in, color 120ms ease-in;
        font-weight: 400;
        line-height: 1.2;
        color: #9ca3af;
        pointer-events: none;
    }
    .textarea__field {
        box-sizing: border-box;
        display: block;
        width: 100%;
        border: 1px solid #ced4da;
        padding: 0.7rem 0.75rem;
        color: var(--color-text, #212121);
        background: transparent;
        border-radius: 0.75rem;
        font-family: inherit;
        font-size: 1rem;
        resize: vertical;
    }
    .textarea__field:focus {
        outline: none;
        border-color: var(--color-primary, #fe9630);
    }
    .textarea__field:focus + .textarea__label,
    .textarea__field:not(:placeholder-shown) + .textarea__label {
        transform: translate(0.25rem, -65%) scale(0.8);
        color: var(--color-primary, #fe9630);
    }
    .textarea__icon {
        position: absolute;
        left: 12px;
        top: 12px;
        pointer-events: none;
        color: #9ca3af;
        z-index: 2;
    }
    .textarea.textarea--has-icon .textarea__field {
        padding-left: 2.5rem;
    }
    .textarea.textarea--has-icon .textarea__label {
        left: 2.25rem;
    }
    .textarea:focus-within .textarea__icon {
        color: var(--color-primary, #fe9630);
    }
    .textarea__error {
        display: block;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #dc2626;
    }
    .textarea.textarea--error .textarea__field {
        border-color: #dc2626;
    }
    .textarea.textarea--error .textarea__field:focus {
        border-color: #dc2626;
    }
</style>
<?php endif; ?>

<div>
<!-- Component HTML -->
<label class="textarea <?= htmlspecialchars($wrapperClass) ?><?= $icon ? ' textarea--has-icon' : '' ?><?= $error ? ' textarea--error' : '' ?>">

        <?php if (!empty($icon)): ?>
                <i class="textarea__icon <?= htmlspecialchars($icon) ?>" aria-hidden="true"></i>
        <?php endif; ?>
        <textarea
            class="textarea__field <?= htmlspecialchars($textareaClass) ?>"
            id="<?= htmlspecialchars($id) ?>"
            name="<?= htmlspecialchars($name) ?>"
            placeholder=" "
            rows="<?= htmlspecialchars($rows) ?>"
            <?= $required ? 'required' : '' ?>
            ><?= htmlspecialchars($value) ?></textarea>
            <span class="textarea__label">
            <?php 
                // Output the label text safely
                echo htmlspecialchars($label);
                // If the field is required, add the asterisk
                if ($required) {
                    echo ' <span class="text-error">*</span>';
                }
                ?>
        </span>
    </label>
    <?php if (!empty($error)): ?>
        <span class="textarea__error"><?= htmlspecialchars($error) ?></span>
    <?php endif; ?>
</div>
