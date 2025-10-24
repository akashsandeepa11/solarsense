<?php
// --- Component Configuration ---

// Configuration setup
$cfg = $selectConfig ?? [];
$id = $cfg['id'] ?? ('select_' . uniqid());
$name = $cfg['name'] ?? $id;
$label = $cfg['label'] ?? 'Some Fancy Label';
$options = $cfg['options'] ?? []; // Array of options
$value = $cfg['value'] ?? ''; // Currently selected value
$icon = $cfg['icon'] ?? ''; // Expects Font Awesome classes e.g., "fas fa-location"
$required = !empty($cfg['required']); // This boolean is the key
$error = $cfg['error'] ?? ''; // Error message
$editable = isset($cfg['editable']) ? !empty($cfg['editable']) : true; // Default to true
$wrapperClass = $cfg['wrapperClass'] ?? '';
$selectClass = $cfg['selectClass'] ?? '';
$placeholder = $cfg['placeholder'] ?? 'Select an option';

// This block ensures the CSS and JS are only included once on the page.
if (!defined('SELECT_BEM_ASSETS')):
    define('SELECT_BEM_ASSETS', true); ?>
<style>
    /* These styles are specific to the .select component */
    .select {
        position: relative;
    }
    .select__label {
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
    .select__field {
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
        cursor: pointer;
    }
    .select__field:focus {
        outline: none;
        border-color: var(--color-primary, #fe9630);
    }
    .select__field:not(:placeholder-shown) + .select__label,
    .select__field:valid:not(:placeholder-shown) + .select__label {
        transform: translate(0.25rem, -65%) scale(0.8);
        color: var(--color-primary, #fe9630);
    }
    .select__field:disabled {
        background-color: #f3f4f6;
        cursor: not-allowed;
    }
    .select__field:disabled:focus {
        border-color: #ced4da;
    }
    .select__icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #9ca3af;
        z-index: 2;
    }
    .select.select--has-icon .select__field {
        padding-left: 2.5rem;
    }
    .select.select--has-icon .select__label {
        left: 2.25rem;
    }
    .select:focus-within .select__icon {
        color: var(--color-primary, #fe9630);
    }
    .select__error {
        display: block;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #dc2626;
    }
    .select.select--error .select__field {
        border-color: #dc2626;
    }
    .select.select--error .select__field:focus {
        border-color: #dc2626;
    }
</style>
<?php endif; ?>

<!-- Component HTML -->
<div>
<label class="select <?= htmlspecialchars($wrapperClass) ?><?= $icon ? ' select--has-icon' : '' ?><?= $error ? ' select--error' : '' ?>">
    <?php if (!empty($icon)): ?>
        <i class="select__icon <?= htmlspecialchars($icon) ?>" aria-hidden="true"></i>
    <?php endif; ?>
    <select
        class="select__field <?= htmlspecialchars($selectClass) ?>"
        id="<?= htmlspecialchars($id) ?>"
        name="<?= htmlspecialchars($name) ?>"
        <?= $required ? 'required' : '' ?>
        <?= !$editable ? 'disabled' : '' ?>
    >
        <option value="" disabled <?= empty($value) ? 'selected' : '' ?>>
            <?= htmlspecialchars($placeholder) ?>
        </option>
        <?php foreach($options as $optionValue => $optionLabel): ?>
            <option value="<?= htmlspecialchars($optionValue) ?>" <?= $value == $optionValue ? 'selected' : '' ?>>
                <?= htmlspecialchars($optionLabel) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <span class="select__label">
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
    <span class="select__error"><?= htmlspecialchars($error) ?></span>
<?php endif; ?>
</div>