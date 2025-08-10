<?php
// BEM-style floating input component (no changes to existing input_field.php)
// Usage: $inputConfig = [
//  'id' => 'field_id', 'name' => 'field_name', 'label' => 'Some Fancy Label',
//  'type' => 'text', 'value' => '', 'icon' => 'fa-regular fa-envelope',
//  'wrapperClass' => '', 'inputClass' => '', 'required' => true, 'helper' => 'Help text']
// include APPROOT . '/views/inc/components/input_bem.php';

$config = isset($inputConfig) && is_array($inputConfig) ? $inputConfig : [];
$id = $config['id'] ?? ('field_' . bin2hex(random_bytes(3)));
$name = $config['name'] ?? $id;
$label = $config['label'] ?? 'Label';
$type = $config['type'] ?? 'text';
$value = $config['value'] ?? '';
$icon = $config['icon'] ?? null; // Font Awesome class string or null
$inputClass = $config['inputClass'] ?? '';
$wrapperClass = $config['wrapperClass'] ?? '';
$required = !empty($config['required']);
$helper = $config['helper'] ?? null;
?>
<label class="input <?= $icon ? 'input--has-icon' : '' ?> <?= htmlspecialchars($wrapperClass) ?>" for="<?= htmlspecialchars($id) ?>">
  <?php if ($icon): ?>
    <i class="input__icon fa-fw <?= htmlspecialchars($icon) ?>" aria-hidden="true"></i>
  <?php endif; ?>
  <input
    id="<?= htmlspecialchars($id) ?>"
    name="<?= htmlspecialchars($name) ?>"
    type="<?= htmlspecialchars($type) ?>"
    class="input__field <?= htmlspecialchars($inputClass) ?>"
    placeholder=" "
    value="<?= htmlspecialchars((string)$value) ?>"
    <?= $required ? 'required' : '' ?>
    aria-label="<?= htmlspecialchars($label) ?>"
  />
  <span class="input__label"><?= htmlspecialchars($label) ?></span>
  <?php if ($helper): ?>
    <small class="text-sm text-muted" style="display:block;margin-top:4px;">&nbsp;<?= htmlspecialchars($helper) ?></small>
  <?php endif; ?>
</label>
