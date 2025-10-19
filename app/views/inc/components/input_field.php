<?php
// --- Component Configuration ---

// Configuration setup
$cfg = $inputConfig ?? [];
$id = $cfg['id'] ?? ('input_' . uniqid());
$name = $cfg['name'] ?? $id;
$label = $cfg['label'] ?? 'Some Fancy Label';
$type = $cfg['type'] ?? 'text';
$value = $cfg['value'] ?? '';
$icon = $cfg['icon'] ?? ''; // Expects Font Awesome classes e.g., "fas fa-envelope"
$required = !empty($cfg['required']); // This boolean is the key
$wrapperClass = $cfg['wrapperClass'] ?? '';
$inputClass = $cfg['inputClass'] ?? '';

// This block ensures the CSS and JS are only included once on the page.
if (!defined('INPUT_BEM_ASSETS')):
    define('INPUT_BEM_ASSETS', true); ?>
<style>
    /* These styles are specific to the .input component */
    .input {
        position: relative;
    }
    .input__label {
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
    .input__field {
        box-sizing: border-box;
        display: block;
        width: 100%;
        border: 1px solid #ced4da;
        padding: 0.7rem 0.75rem;
        color: var(--color-text, #212121);
        background: transparent;
        border-radius: 0.75rem;
    }
    .input__field:focus {
        outline: none;
        border-color: var(--color-primary, #fe9630);
    }
    .input__field:focus + .input__label,
    .input__field:not(:placeholder-shown) + .input__label {
        transform: translate(0.25rem, -65%) scale(0.8);
        color: var(--color-primary, #fe9630);
    }
    .input__icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #9ca3af;
        z-index: 2;
    }
    .input.input--has-icon .input__field {
        padding-left: 2.5rem;
    }
    .input.input--has-icon .input__label {
        left: 2.25rem;
    }
    .input:focus-within .input__icon {
        color: var(--color-primary, #fe9630);
    }
</style>
<?php endif; ?>

<!-- Component HTML -->
<label class="input <?= htmlspecialchars($wrapperClass) ?><?= $icon ? ' input--has-icon' : '' ?>">
    <?php if (!empty($icon)): ?>
        <i class="input__icon <?= htmlspecialchars($icon) ?>" aria-hidden="true"></i>
    <?php endif; ?>
    <input
        class="input__field <?= htmlspecialchars($inputClass) ?>"
        id="<?= htmlspecialchars($id) ?>"
        name="<?= htmlspecialchars($name) ?>"
        type="<?= htmlspecialchars($type) ?>"
        placeholder=" "
        value="<?= htmlspecialchars($value) ?>"
        <?= $required ? 'required' : '' ?>
    />
    <span class="input__label">
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
