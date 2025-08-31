<?php
/**
 * Profile Input Field Component
 * 
 * Params:
 * @param array $field - Field configuration array containing:
 *   @param string $id - Field ID
 *   @param string $label - Field label text
 *   @param string $value - Current field value
 *   @param bool $editable - Whether field can be edited (default: true)
 *   @param bool $required - Whether field is required (default: false)
 *   @param string $type - Input type (default: 'text')
 *   @param string $summaryTarget - ID of element in summary to update when input changes (optional)
 */

// Get parameters with defaults
$id = $field['id'] ?? '';
$label = $field['label'] ?? '';
$value = $field['value'] ?? '';
$editable = $field['editable'] ?? true;
$required = $field['required'] ?? false;
$type = $field['type'] ?? 'text';
$summaryTarget = $field['summaryTarget'] ?? '';

// Set properties based on editability
$readonlyAttr = !$editable ? 'readonly' : '';
$btnClass = $editable ? '' : 'd-none';
?>

<div class="form-group">
  <label for="<?php echo htmlspecialchars($id); ?>"><?php echo htmlspecialchars($label); ?><?php echo $required ? ' <span class="text-error">*</span>' : ''; ?></label>
  <div class="d-flex align-center position-relative">
    <input 
      type="<?php echo htmlspecialchars($type); ?>" 
      class="form-control"
      style="background-color: #f5f5f5ff;" 
      id="<?php echo htmlspecialchars($id); ?>" 
      name="<?php echo htmlspecialchars($id); ?>" 
      value="<?php echo htmlspecialchars($value); ?>" 
      <?php echo $readonlyAttr; ?>
      <?php echo $required ? 'required' : ''; ?>
      data-summary-target="<?php echo htmlspecialchars($summaryTarget); ?>"
    >
    <button type="button" class="edit-btn <?php echo $btnClass; ?> position-absolute" style="right:15px; background:none; border:none; cursor:pointer;">
      <i class="fas fa-pen"></i>
    </button>
  </div>
</div>