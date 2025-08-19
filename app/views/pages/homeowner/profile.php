<?php
// Example PHP variables
$fullName = "John Doe";
$email = "john@example.com";
$contactNumber = "0771234567";
$physicalAddress = "123 Main Street";
$district = "Colombo";
$systemCapacity = 5;
$panelTilt = 30;
$panelAzimuth = "North";
$installationDate = "2024-01-15";
$panelBrand = "SunPower";
$inverterBrand = "SMA";
$cebAccount = "1234567890";
?>

<style>
.customer-profile-container { max-width: 800px; margin: auto; }
.card { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
.profile-header { display: flex; align-items: center; justify-content: space-between; }
.profile-avatar i { font-size: 60px; color: #555; }
.profile-info h2 { margin: 0; }
.profile-info p { margin: 5px 0; }

.btn-primary { background: #007bff; color: #fff; }
.btn-secondary { background: #6c757d; color: #fff; }
.profile-section h3 { margin-top: 0; margin-bottom: 15px; color: #333; }
.profile-item { position: relative; margin-bottom: 15px; }
.edit-icon { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: gray; }
.edit-icon:hover { color: #007bff; }
</style>


<div class="customer-profile-container">



    <!-- Profile Header -->
    <div class="card profile-header">
        <div class="profile-avatar">
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="profile-info">
            <h2><?php echo htmlspecialchars($fullName); ?></h2>
            <p class="text-secondary"><?php echo htmlspecialchars($email); ?></p>
            <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($contactNumber); ?></p>
            <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($district); ?></p>
        </div>
       
    </div>

    <form method="post" action="save.php">
    <!-- Personal & Contact Details -->
    <div class="card profile-section">
        <h3>Personal & Contact Details</h3>

        <div class="profile-item">
            <?php 
                $inputConfig = [
                    'id' => 'fullName', 
                    'name' => 'fullName', 
                    'label' => 'Full Name', 
                    'type' => 'text', 
                    'icon' => 'fas fa-user', 
                    'value' => $fullName, 
                    'readonly' => true
                ]; 
                require APPROOT . '/views/inc/components/input_field.php'; 
            ?>
            <span class="edit-icon" onclick="enableEdit('fullName')">&#9998;</span>
        </div>

        <div class="profile-item">
            <?php 
                $inputConfig = [
                    'id' => 'email', 
                    'name' => 'email', 
                    'label' => 'Email', 
                    'type' => 'email', 
                    'icon' => 'fas fa-envelope', 
                    'value' => $email, 
                    'readonly' => true
                ]; 
                require APPROOT . '/views/inc/components/input_field.php'; 
            ?>
            <span class="edit-icon" onclick="enableEdit('email')">&#9998;</span>
        </div>

        <div class="profile-item">
            <?php 
                $inputConfig = [
                    'id' => 'contactNumber', 
                    'name' => 'contactNumber', 
                    'label' => 'Contact Number', 
                    'type' => 'tel', 
                    'icon' => 'fas fa-phone', 
                    'value' => $contactNumber, 
                    'readonly' => true
                ]; 
                require APPROOT . '/views/inc/components/input_field.php'; 
            ?>
            <span class="edit-icon" onclick="enableEdit('contactNumber')">&#9998;</span>
        </div>

        <div class="profile-item">
            <?php 
                $inputConfig = [
                    'id' => 'physicalAddress', 
                    'name' => 'physicalAddress', 
                    'label' => 'Address', 
                    'type' => 'text', 
                    'icon' => 'fas fa-map-marker-alt', 
                    'value' => $physicalAddress, 
                    'readonly' => true
                ]; 
                require APPROOT . '/views/inc/components/input_field.php'; 
            ?>
            <span class="edit-icon" onclick="enableEdit('physicalAddress')">&#9998;</span>
        </div>

        <div class="profile-item">
            <?php 
                $inputConfig = [
                    'id' => 'district', 
                    'name' => 'district', 
                    'label' => 'District', 
                    'type' => 'text', 
                    'icon' => 'fas fa-city', 
                    'value' => $district, 
                    'readonly' => true
                ]; 
                require APPROOT . '/views/inc/components/input_field.php'; 
            ?>
            <span class="edit-icon" onclick="enableEdit('district')">&#9998;</span>
        </div>

         <button type="submit" id="saveButtonContainer" class="btn btn-primary"  style="display: none;"><i class="fas fa-save"></i> Save Changes </button>

    </div>



    </form>

    <!-- Solar System Details -->
    <div class="card profile-section">
        <h3>Solar System Specifications</h3>

        <div class="profile-item">
            <?php 
                $inputConfig = [
                    'id' => 'systemCapacity', 
                    'name' => 'systemCapacity', 
                    'label' => 'System Capacity', 
                    'type' => 'text', 
                    'icon' => 'fas fa-bolt', 
                    'value' => $systemCapacity.' kWp', 
                    'readonly' => true
                ]; 
                require APPROOT . '/views/inc/components/input_field.php'; 
            ?>
            <span class="edit-icon" onclick="enableEdit('systemCapacity')">&#9998;</span>
        </div>

        <div class="profile-item">
            <?php 
                $inputConfig = [
                    'id' => 'panelTilt', 
                    'name' => 'panelTilt', 
                    'label' => 'Panel Tilt', 
                    'type' => 'text', 
                    'icon' => 'fas fa-angle-up', 
                    'value' => $panelTilt.'°', 
                    'readonly' => true
                ]; 
                require APPROOT . '/views/inc/components/input_field.php'; 
            ?>
            <span class="edit-icon" onclick="enableEdit('panelTilt')">&#9998;</span>
        </div>

        <div class="profile-item">
            <?php 
                $inputConfig = [
                    'id' => 'panelAzimuth', 
                    'name' => 'panelAzimuth', 
                    'label' => 'Panel Azimuth', 
                    'type' => 'text', 
                    'icon' => 'fas fa-compass', 
                    'value' => $panelAzimuth, 
                    'readonly' => true
                ]; 
                require APPROOT . '/views/inc/components/input_field.php'; 
            ?>
            <span class="edit-icon" onclick="enableEdit('panelAzimuth')">&#9998;</span>
        </div>

        <div class="profile-item">
            <?php 
                $inputConfig = [
                    'id' => 'installationDate', 
                    'name' => 'installationDate', 
                    'label' => 'Installation Date', 
                    'type' => 'date', 
                    'icon' => 'fas fa-calendar-alt', 
                    'value' => $installationDate, 
                    'readonly' => true
                ]; 
                require APPROOT . '/views/inc/components/input_field.php'; 
            ?>
            <span class="edit-icon" onclick="enableEdit('installationDate')">&#9998;</span>
        </div>

        <div class="profile-item">
            <?php 
                $inputConfig = [
                    'id' => 'panelBrand', 
                    'name' => 'panelBrand', 
                    'label' => 'Panel Brand', 
                    'type' => 'text', 
                    'icon' => 'fas fa-solar-panel', 
                    'value' => $panelBrand ?: "N/A", 
                    'readonly' => true
                ]; 
                require APPROOT . '/views/inc/components/input_field.php'; 
            ?>
            <span class="edit-icon" onclick="enableEdit('panelBrand')">&#9998;</span>
        </div>

        <div class="profile-item">
            <?php 
                $inputConfig = [
                    'id' => 'inverterBrand', 
                    'name' => 'inverterBrand', 
                    'label' => 'Inverter Brand', 
                    'type' => 'text', 
                    'icon' => 'fas fa-microchip', 
                    'value' => $inverterBrand ?: "N/A", 
                    'readonly' => true
                ]; 
                require APPROOT . '/views/inc/components/input_field.php'; 
            ?>
            <span class="edit-icon" onclick="enableEdit('inverterBrand')">&#9998;</span>
        </div>

    </div>

    <!-- Utility Account -->
    <div class="card profile-section">
        <h3>Utility Account</h3>

        <div class="profile-item">
            <?php 
                $inputConfig = [
                    'id' => 'cebAccount', 
                    'name' => 'cebAccount', 
                    'label' => 'CEB Account', 
                    'type' => 'text', 
                    'icon' => 'fas fa-id-card', 
                    'value' => $cebAccount, 
                    'readonly' => true
                ]; 
                require APPROOT . '/views/inc/components/input_field.php'; 
            ?>
            <span class="edit-icon" onclick="enableEdit('cebAccount')">&#9998;</span>
        </div>

        <div class="profile-item">
            <?php 
                $inputConfig = [
                    'id' => 'provider', 
                    'name' => 'provider', 
                    'label' => 'Provider', 
                    'type' => 'text', 
                    'icon' => 'fas fa-building', 
                    'value' => 'Ceylon Electricity Board (CEB)', 
                    'readonly' => true 
                ]; 
                require APPROOT . '/views/inc/components/input_field.php'; 
            ?>

        </div>

         

    </div>

    

</div>



<script>

// Ensure all inputs are read-only initially
window.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.setAttribute('readonly', true);
        input.style.border = 'none';
        input.dataset.originalValue = input.value; // store original value
    });
});

function enableEdit(id) {
    // First, make all other inputs read-only
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        if (input.id !== id) {
            input.setAttribute('readonly', true);
            input.style.border = 'none';
        }
    });

    // Then toggle the selected input
    const input = document.getElementById(id);
    input.removeAttribute('readonly'); // Make editable
    input.focus();
    input.style.border = "1px solid #007bff";


        // Show Save button only if input value changes
    input.addEventListener('input', function checkChange() {
        const saveBtn = document.getElementById('saveButtonContainer');
        if (input.value !== input.dataset.originalValue) {
            saveBtn.style.display = 'block';
        } else {
            saveBtn.style.display = 'none';
        }
    });


}
</script>

