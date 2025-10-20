<?php
$maintenanceType = $_POST['maintenanceType'];
$allTypes = [
    "Cleaning", "Checking", "Repair", "Breakdown"
];
$description = $_POST['description'] ?? '';

?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/homeowner/service1.css">
<form id="maintenance-form" action="save.php" method="post" novalidate>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="maintenanceType" class="form-label">Request Type <span class="text-error">*</span></label>
                            <select id="maintenanceType" name="maintenanceType" class="form-control" required>
                                <option value="" disabled selected>Select</option>
                                <?php foreach($allTypes as $m): ?>
                                    <option value="<?php echo htmlspecialchars($d); ?>" <?php echo ($maintenanceType == $m) ? 'selected' : ''; ?>><?php echo htmlspecialchars($d); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'email', 'name' => 'email', 'label' => 'Email Address (Username)', 'type' => 'email', 'icon' => 'fas fa-envelope', 'value' => $email, 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'contactNumber', 'name' => 'contactNumber', 'label' => 'Contact Number', 'type' => 'tel', 'icon' => 'fas fa-phone', 'value' => $contactNumber, 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'physicalAddress', 'name' => 'physicalAddress', 'label' => 'Physical Address', 'type' => 'text', 'icon' => 'fas fa-map-marker-alt', 'value' => $physicalAddress, 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="district" class="form-label">District <span class="text-error">*</span></label>
                            <select id="district" name="district" class="form-control" required>
                                <option value="" disabled selected>Select a District</option>
                                <?php foreach($all_districts as $d): ?>
                                    <option value="<?php echo htmlspecialchars($d); ?>" <?php echo ($district == $d) ? 'selected' : ''; ?>><?php echo htmlspecialchars($d); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>