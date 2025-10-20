<?php
// --- PHP LOGIC FOR THE FORM ---

// In a real controller, you would likely pass this data to the view.
// For this example, we'll define it here.

// Handle potential POST data to repopulate the form if there's an error
$fullName = $_POST['fullName'] ?? '';
$email = $_POST['email'] ?? '';
$contactNumber = $_POST['contactNumber'] ?? '';
$physicalAddress = $_POST['physicalAddress'] ?? '';
$district = $_POST['district'] ?? '';
$description = $_POST['description'] ?? '';
$requestDate = $_POST['requestDate'] ?? '';
$systemCapacity = $_POST['systemCapacity'] ?? '';
$panelTilt = $_POST['panelTilt'] ?? '';
$panelAzimuth = $_POST['panelAzimuth'] ?? '';
$installationDate = $_POST['installationDate'] ?? '';
$Description = $_POST['panelBrand'] ?? '';
$inverterBrand = $_POST['inverterBrand'] ?? '';
$cebAccount = $_POST['cebAccount'] ?? '';

// List of districts for the dropdown
$all_districts = [
    "Ampara", "Anuradhapura", "Badulla", "Batticaloa", "Colombo", "Galle", "Gampaha",
    "Hambantota", "Jaffna", "Kalutara", "Kandy", "Kegalle", "Kilinochchi", "Kurunegala",
    "Mannar", "Matale", "Matara", "Monaragala", "Mullaitivu", "Nuwara Eliya",
    "Polonnaruwa", "Puttalam", "Ratnapura", "Trincomalee", "Vavuniya"
];
?>

<!-- Link to the custom CSS for this page -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/add_customer_form.css">

<div class="content-area">

    <!-- Main Form Card -->
    <div class="card shadow-lg rounded-xl">
        <div class="card-body p-10">
            
            <!-- Step Indicator -->
            <div class="form-header text-center mb-10">
                <h2 class="text-2xl font-bold">Maintenance Request</h2>
                <p class="text-secondary mt-1">Please use this form to report any issues you are experiencing</p>
            </div>
            <div class="step-indicator-container mb-10">
                <div class="step-indicator-line">
                    <div id="progress-line"></div>
                </div>
                <div class="step-indicator active" data-step="1">
                    <div class="step-indicator-icon"><i class="fas fa-user"></i></div>
                    <div class="step-indicator-label">Personal</div>
                </div>
                <div class="step-indicator" data-step="2">
                    <div class="step-indicator-icon"><i class="fas fa-solar-panel"></i></div>
                    <div class="step-indicator-label">System</div>
                </div>
                <!-- <div class="step-indicator" data-step="3">
                    <div class="step-indicator-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <div class="step-indicator-label">Utility</div>
                </div> -->
                <div class="step-indicator" data-step="3">
                    <div class="step-indicator-icon"><i class="fas fa-check"></i></div>
                    <div class="step-indicator-label">Confirm</div>
                </div>
            </div>

            <form id="registration-form" action="save.php" method="post" novalidate>
                <!-- Step 1: Personal & Contact Details -->
                <div id="step-1" class="form-step active">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'fullName', 'name' => 'fullName', 'label' => 'Full Name', 'type' => 'text', 'icon' => 'fas fa-user', 'value' => $fullName, 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
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

                <!-- Step 2: Solar System Specifications -->
                <div id="step-2" class="form-step">
                    <div class="row">
                        <div class="col-md-9 form-group">
                            Please describe the issue you are experiencing:
                            <?php $inputConfig = ['id' => 'description', 'name' => 'description', 'label' => 'description', 'type' => 'text', 'icon' => 'fas fa-tag', 'value' => $description, 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>                   
                        <div class="col-md-2 form-group">
                            Date:
                            <?php $inputConfig = ['id' => 'requestDate', 'name' => 'requestDate',  'type' => 'date', 'icon' => 'fas fa-calendar-alt', 'value' => $requestDate, 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                    </div>
                </div>                

                <!-- Step 4: Review and Confirm -->
                <div id="step-3" class="form-step">
                    <div class="review-section">
                        <div class="d-flex justify-between align-center mb-2">
                            <h3 class="text-xl font-semibold">Reported Issue</h3>
                            <button type="button" class="btn btn-secondary btn-sm edit-btn" data-step="1">Edit</button>
                        </div>
                        <div class="review-item"><span class="label">Description</span><span class="value" data-review="description"></span></div>
                        <div class="review-item"><span class="label">Requested Date</span><span class="value" data-review="currentDate"></span></div>
                    </div>
                    <div class="review-section">
                        <div class="d-flex justify-between align-center mb-2">
                            <h3 class="text-xl font-semibold">Requested By</h3>
                            <button type="button" class="btn btn-secondary btn-sm edit-btn" data-step="1">Edit</button>
                        </div>
                        <div class="review-item"><span class="label">Full Name</span><span class="value" data-review="fullName"></span></div>
                        <div class="review-item"><span class="label">Email Address</span><span class="value" data-review="email"></span></div>
                        <div class="review-item"><span class="label">Contact Number</span><span class="value" data-review="contactNumber"></span></div>
                        <div class="review-item"><span class="label">Physical Address</span><span class="value" data-review="physicalAddress"></span></div>
                        <div class="review-item"><span class="label">District</span><span class="value" data-review="district"></span></div>
                    </div>
                    <div class="review-section">
                        <div class="d-flex justify-between align-center mb-2">
                            <h3 class="text-xl font-semibold">Solar System Specifications</h3>
                            <button type="button" class="btn btn-secondary btn-sm edit-btn" data-step="2">Edit</button>
                        </div>
                        <div class="review-item"><span class="label">System Capacity</span><span class="value" data-review="systemCapacity"></span></div>
                        <div class="review-item"><span class="label">Panel Tilt</span><span class="value" data-review="panelTilt"></span></div>
                        <div class="review-item"><span class="label">Panel Azimuth</span><span class="value" data-review="panelAzimuth"></span></div>
                        <div class="review-item"><span class="label">Installation Date</span><span class="value" data-review="installationDate"></span></div>
                        <div class="review-item"><span class="label">Panel Brand</span><span class="value" data-review="Description"></span></div>
                        <div class="review-item"><span class="label">Inverter Brand</span><span class="value" data-review="inverterBrand"></span></div>
                    </div>
                    <div class="review-section">
                        <div class="d-flex justify-between align-center mb-2">
                            <h3 class="text-xl font-semibold">Utility Account</h3>
                            <button type="button" class="btn btn-secondary btn-sm edit-btn" data-step="3">Edit</button>
                        </div>
                        <div class="review-item"><span class="label">CEB Account Number</span><span class="value" data-review="cebAccount"></span></div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="mt-8 pt-5 border-t d-flex justify-between align-center">
                    <button type="button" id="prev-btn" class="btn btn-secondary" style="display: none;">Previous</button>
                    <button type="button" id="next-btn" class="btn btn-primary ml-auto">Next</button>
                    <button type="submit" id="submit-btn" class="btn btn-success ml-auto" style="display: none;">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        const formContainer = document.querySelector('.content-area');
        if (!formContainer) return;

        const form = formContainer.querySelector('#registration-form');
        if(!form) return;

        const steps = formContainer.querySelectorAll('.form-step');
        const nextBtn = formContainer.querySelector('#next-btn');
        const prevBtn = formContainer.querySelector('#prev-btn');
        const submitBtn = formContainer.querySelector('#submit-btn');
        const indicators = formContainer.querySelectorAll('.step-indicator');
        const progressLine = formContainer.querySelector('#progress-line');

        let currentStep = 1;
        const totalSteps = steps.length;

        function goToStep(stepNumber) {
            currentStep = stepNumber;
            steps.forEach(step => {
                step.style.display = 'none';
                step.classList.remove('active');
            });

            const activeStepEl = formContainer.querySelector(`#step-${currentStep}`);
            if(activeStepEl) {
                activeStepEl.style.display = 'block';
                setTimeout(() => activeStepEl.classList.add('active'), 10);
            }

            if(prevBtn) prevBtn.style.display = currentStep > 1 ? 'inline-flex' : 'none';
            if(nextBtn) nextBtn.style.display = currentStep < totalSteps ? 'inline-flex' : 'none';
            if(submitBtn) submitBtn.style.display = currentStep === totalSteps ? 'inline-flex' : 'none';

            updateProgress();

            if (currentStep === totalSteps) {
                updateReviewData();
            }
        }

        function updateProgress() {
            indicators.forEach((indicator, index) => {
                const step = index + 1;
                indicator.classList.remove('active', 'completed');
                if (step < currentStep) {
                    indicator.classList.add('completed');
                } else if (step === currentStep) {
                    indicator.classList.add('active');
                }
            });
            if(progressLine) {
                const progressPercentage = totalSteps > 1 ? ((currentStep - 1) / (totalSteps - 1)) * 100 : 0;
                progressLine.style.width = `${progressPercentage}%`;
            }
        }

        function validateStep(stepNumber) {
            const currentStepEl = formContainer.querySelector(`#step-${stepNumber}`);
            if(!currentStepEl) return false;

            const currentStepFields = currentStepEl.querySelectorAll('[required]');
            let isValid = true;
            currentStepFields.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = 'var(--color-error, #ef4444)';
                    isValid = false;
                } else {
                    field.style.borderColor = '#ced4da';
                }
            });
            return isValid;
        }

        function updateReviewData() {
            const formData = new FormData(form);
            for (let [key, value] of formData.entries()) {
                const reviewElement = formContainer.querySelector(`[data-review="${key}"]`);
                if (reviewElement) {
                    reviewElement.textContent = value || 'N/A';
                }
            }

            const capacityEl = formContainer.querySelector('[data-review="systemCapacity"]');
            if(capacityEl) capacityEl.textContent = formData.get('systemCapacity') ? `${formData.get('systemCapacity')} kWp` : 'N/A';

            const tiltEl = formContainer.querySelector('[data-review="panelTilt"]');
            if(tiltEl) tiltEl.textContent = formData.get('panelTilt') ? `${formData.get('panelTilt')}°` : 'N/A';
        }

        if(nextBtn) {
            nextBtn.addEventListener('click', () => {
                if (validateStep(currentStep) && currentStep < totalSteps) {
                    goToStep(currentStep + 1);
                }
            });
        }

        if(prevBtn) {
            prevBtn.addEventListener('click', () => {
                if (currentStep > 1) {
                    goToStep(currentStep - 1);
                }
            });
        }

        formContainer.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const stepToEdit = parseInt(this.getAttribute('data-step'));
                goToStep(stepToEdit);
            });
        });

        form.addEventListener('submit', function(e) {
            if (currentStep !== totalSteps) {
                 e.preventDefault();
                 return;
            }
            let isFormValid = true;
            for(let i = 1; i < totalSteps; i++) {
                if (!validateStep(i)) {
                    isFormValid = false;
                    goToStep(i);
                    break;
                }
            }
            if(!isFormValid) {
                e.preventDefault();
                alert('Please fill out all required fields before submitting.');
            }
        });

        // Initial setup
        goToStep(1);

    })();
</script>
