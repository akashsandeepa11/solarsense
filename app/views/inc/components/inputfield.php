<?php
// =============================================================================
// INPUT COMPONENT WITH FLOATING LABEL (BEM STYLE)
// =============================================================================

// Configuration setup
$cfg = $inputConfig ?? [];
$id = $cfg['id'] ?? ('input_' . uniqid());
$name = $cfg['name'] ?? $id;
$label = $cfg['label'] ?? 'Some Fancy Label';
$type = $cfg['type'] ?? 'text';
$value = $cfg['value'] ?? '';
$icon = $cfg['icon'] ?? ''; // Expects Font Awesome classes e.g., "fas fa-envelope"
$required = !empty($cfg['required']);
$wrapperClass = $cfg['wrapperClass'] ?? '';
$inputClass = $cfg['inputClass'] ?? ''; // e.g., your library's .form-control
?>

<?php
// This block ensures the CSS and JS are only included once on the page.
if (!defined(constant_name: 'INPUT_BEM_ASSETS')):
    define('INPUT_BEM_ASSETS', true); ?>
<style>
    /* These styles are specific to the .input component.
      They are designed to work with the variables from your SCSS library.
      For example, you might define these CSS variables in your main stylesheet:
      :root {
        --size-bezel: 0.5rem; 
        --color-background: #f5f5f5;
        --color-accent: #03a9f4;
        --size-radius: 0.25rem;
      }
    */
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
        background: var(--color-background, #fff); /* Fallback color */
        transition: transform 120ms ease-in, color 120ms ease-in;
        font-weight: 400; /* Corresponds to semibold */
        line-height: 1.2;
        color: #9ca3af; /* A neutral placeholder color */
        pointer-events: none;
    }
    .input__field {
        box-sizing: border-box;
        display: block;
        width: 100%;
        border: 1px solid #ced4da; /* Standard border */
        padding: 0.7rem 0.75rem; /* Based on your .form-control */
        color: var(--color-text, #212121);
        background: transparent;
        border-radius: 0.25rem; /* base radius */
    }

    /* Focus state: change border to primary color */
    .input__field:focus {
        outline: none;
        border-color: var(--color-primary, #fe9630);
    }

    /* The magic for the floating label effect */
    .input__field:focus + .input__label,
    .input__field:not(:placeholder-shown) + .input__label {
        transform: translate(0.25rem, -65%) scale(0.8);
        color: var(--color-primary, #fe9630); /* Use primary color on focus */
    }

    /* Icon Support */
    .input__icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    color: #9ca3af; /* A neutral icon color */
    z-index: 2; /* Ensure icon sits above label/background */
    }
    .input.input--has-icon .input__field {
        padding-left: 2.5rem; /* 40px, same as your .form-field--has-icon */
    }
    .input.input--has-icon .input__label {
        left: 2.25rem; /* Move label past the icon */
    }

    /* Focus state: icon adopts primary color when the field is focused */
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
    <span class="input__label"><?= htmlspecialchars($label) ?></span>
</label> 