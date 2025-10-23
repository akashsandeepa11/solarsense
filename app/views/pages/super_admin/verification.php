<div class="content-area" style="padding: 1.5rem;">
    <!-- Page Header -->
    <?php
    $config = [
        'title' => 'Company Verifications',
        'description' => 'Review and verify installer company registration requests',
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <!-- Filter & Search Section -->
    <?php
    $config = [
        'search' => [
            'id' => 'searchBar',
            'name' => 'search',
            'label' => 'Search Company',
            'placeholder' => 'Search by company name...'
        ],
        'filters' => [
            [
                'id' => 'statusFilter',
                'name' => 'status',
                'label' => 'Status Filter',
                'options' => [
                    ['value' => 'all', 'label' => 'All Status'],
                    ['value' => 'Pending', 'label' => 'Pending'],
                    ['value' => 'Verified', 'label' => 'Verified']
                ]
            ]
        ],
        'buttons' => [],
        'form_action' => '',
        'form_method' => 'GET',
        'auto_submit' => false,
        'reset_on_clear' => false,
        'result_count' => true,
        'result_count_id' => 'resultCount'
    ];
    include __DIR__ . '/../../inc/components/filter_bar.php';
    ?>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-xl">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <div class="text-secondary text-sm">Pending Verification</div>
                            <div class="h3 mb-0 font-bold" id="pendingCount">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-xl">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <div class="text-secondary text-sm">Verified Companies</div>
                            <div class="h3 mb-0 font-bold" id="verifiedCount">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-xl">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <div class="text-secondary text-sm">Total Requests</div>
                            <div class="h3 mb-0 font-bold" id="totalCount">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification List -->
    <div id="registrationList"></div>

    <!-- Empty State -->
    <div id="emptyState" class="card shadow-sm rounded-xl" style="display: none;">
        <div class="card-body text-center" style="padding: 3rem;">
            <i class="fas fa-inbox text-secondary" style="font-size: 4rem; opacity: 0.3;"></i>
            <h4 class="mt-4 mb-2">No Verification Requests Found</h4>
            <p class="text-secondary mb-0">There are no companies matching your search criteria.</p>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div id="viewModal" class="custom-modal" style="display: none;">
    <div class="modal-overlay" onclick="closeViewModal()"></div>
    <div class="modal-dialog" style="max-width: 700px;">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-building text-primary mr-2"></i>Company Details
                </h5>
                <button type="button" class="btn-close" onclick="closeViewModal()" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <h4 class="mb-0 font-bold" id="modalCompany"></h4>
                        <span id="modalStatusBadge" class="badge mt-2"></span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">Email Address</label>
                        <p class="mb-0 font-semibold" id="modalEmail"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">Contact Number</label>
                        <p class="mb-0 font-semibold" id="modalContact"></p>
                    </div>
                    <div class="col-12">
                        <label class="text-secondary text-sm mb-1">Address</label>
                        <p class="mb-0 font-semibold" id="modalAddress"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">Submitted Date</label>
                        <p class="mb-0 font-semibold" id="modalDate"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary text-sm mb-1">District</label>
                        <p class="mb-0 font-semibold" id="modalDistrict"></p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" onclick="closeViewModal()">
                    <i class="fas fa-times mr-2"></i>Close
                </button>
                <button type="button" id="modalVerifyBtn" class="btn btn-sm btn-success" style="display: none;">
                    <i class="fas fa-check-circle mr-2"></i>Verify Company
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Verification Confirmation Modal -->
<?php
$config = [
    'modal_id' => 'verifyModal',
    'title' => 'Verify Company',
    'icon' => 'fas fa-check-circle',
    'icon_color' => 'text-success',
    'heading' => 'Confirm Company Verification',
    'message' => 'Are you sure you want to verify this company? This will grant them access to the platform.',
    'confirm_text' => 'Verify Company',
    'cancel_text' => 'Cancel',
    'confirm_action' => '',
    'confirm_method' => 'GET',
    'confirm_class' => 'btn-success',
    'confirm_icon' => 'fas fa-check-circle'
];
include __DIR__ . '/../../inc/models/confirmation_modal.php';
?>

<style>
.verification-card {
    background: white;
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 1rem;
    border-left: 4px solid #fe9630;
    transition: all 0.3s ease;
}

.verification-card:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.stat-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    color: #ffffff;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.stat-icon.bg-primary {
    background-color: #fe9630 !important;
}

.stat-icon.bg-success {
    background-color: #22c55e !important;
}

.stat-icon.bg-warning {
    background-color: #f59e0b !important;
}
</style>

<script>
    const registrations = <?php echo json_encode($data['verifications']); ?>;
    const registrationList = document.getElementById("registrationList");
    const statusFilter = document.getElementById("statusFilter");
    const searchBar = document.getElementById("searchBar");
    const emptyState = document.getElementById("emptyState");
    const viewModal = document.getElementById("viewModal");
    
    let currentVerificationId = null;

    function updateCounts(filtered) {
        const pending = filtered.filter(r => r.status === 'Pending').length;
        const verified = filtered.filter(r => r.status === 'Verified' || r.status === 'verified').length;
        const total = registrations.length;

        document.getElementById('pendingCount').textContent = pending;
        document.getElementById('verifiedCount').textContent = verified;
        document.getElementById('totalCount').textContent = total;
        document.getElementById('resultCount').textContent = filtered.length;
    }

    function renderRegistrations() {
        registrationList.innerHTML = "";
        
        let filtered = registrations.filter(reg => {
            const matchesSearch = (reg.company_name ?? '').toLowerCase().includes(searchBar.value.toLowerCase());
            const matchesStatus = statusFilter.value === "all" || reg.status === statusFilter.value;
            return matchesSearch && matchesStatus;
        });

        updateCounts(filtered);

        if (filtered.length === 0) {
            emptyState.style.display = 'block';
            return;
        } else {
            emptyState.style.display = 'none';
        }

        filtered.forEach(reg => {
            const card = document.createElement("div");
            card.className = "verification-card";

            const statusClass = reg.status === 'Pending' ? 'bg-warning text-dark' : 'bg-success';
            const statusIcon = reg.status === 'Pending' ? 'fa-clock' : 'fa-check-circle';

            card.innerHTML = `
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-start gap-3">
                            <div class="stat-icon ${reg.status === 'Pending' ? 'bg-warning' : 'bg-success'}" style="width: 50px; height: 50px; font-size: 1.25rem;">
                                <i class="fas ${statusIcon}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1 font-bold">${reg.company_name}</h5>
                                <div class="text-secondary text-sm mb-2">
                                    <i class="fas fa-envelope mr-1"></i>${reg.email}
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-phone mr-1"></i>${reg.contact}
                                </div>
                                <div class="text-secondary text-sm">
                                    <i class="fas fa-map-marker-alt mr-1"></i>${reg.address}
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-calendar mr-1"></i>${reg.request_date}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex gap-2 justify-content-end flex-wrap">
                            <button class="btn btn-sm ${statusClass}" style="cursor: default; pointer-events: none;">
                                <i class="fas ${statusIcon} mr-1"></i>${reg.status}
                            </button>
                            <button class="btn btn-sm btn-primary view-details-btn" data-id="${reg.companyId}">
                                <i class="fas fa-eye mr-1"></i>View Details
                            </button>
                            ${reg.status === 'Pending' ? `
                                <button class="btn btn-sm btn-success verify-company-btn" data-id="${reg.companyId}" data-name="${reg.company_name}">
                                    <i class="fas fa-check-circle mr-1"></i>Verify
                                </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;

            registrationList.appendChild(card);
        });

        // Add event listeners for view buttons
        document.querySelectorAll('.view-details-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const reg = registrations.find(r => r.companyId == id);
                if (reg) {
                    showViewModal(reg);
                }
            });
        });

        // Add event listeners for verify buttons
        document.querySelectorAll('.verify-company-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                currentVerificationId = id;
                
                // Update confirmation modal
                document.querySelector('#verifyModal .modal-body p').innerHTML = 
                    'Are you sure you want to verify this company? This will grant them access to the platform. <strong>' + name + '</strong>';
                
                // Update confirmation action
                const confirmBtn = document.querySelector('#verifyModal .btn-success');
                if (confirmBtn) {
                    confirmBtn.onclick = function() {
                        window.location.href = "<?php echo URLROOT?>/superadmin/verify_company/" + currentVerificationId;
                    };
                }
                
                showConfirmationModal('verifyModal');
            });
        });
    }

    function showViewModal(reg) {
        document.getElementById('modalCompany').textContent = reg.company_name;
        document.getElementById('modalEmail').textContent = reg.email;
        document.getElementById('modalContact').textContent = reg.contact;
        document.getElementById('modalAddress').textContent = reg.address;
        document.getElementById('modalDate').textContent = reg.request_date;
        document.getElementById('modalDistrict').textContent = reg.district || 'N/A';
        
        const statusBadge = document.getElementById('modalStatusBadge');
        if (reg.status === 'Pending') {
            statusBadge.className = 'badge bg-warning text-dark mt-2';
            statusBadge.innerHTML = '<i class="fas fa-clock mr-1"></i>Pending Verification';
        } else {
            statusBadge.className = 'badge bg-success mt-2';
            statusBadge.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Verified';
        }

        const verifyBtn = document.getElementById('modalVerifyBtn');
        if (reg.status === 'Pending') {
            verifyBtn.style.display = 'inline-block';
            verifyBtn.onclick = function() {
                closeViewModal();
                currentVerificationId = reg.companyId;
                
                // Update confirmation modal
                document.querySelector('#verifyModal .modal-body p').innerHTML = 
                    'Are you sure you want to verify this company? This will grant them access to the platform. <strong>' + reg.company_name + '</strong>';
                
                // Update confirmation action
                const confirmBtn = document.querySelector('#verifyModal .btn-success');
                if (confirmBtn) {
                    confirmBtn.onclick = function() {
                        window.location.href = "<?php echo URLROOT?>/superadmin/verify_company/" + currentVerificationId;
                    };
                }
                
                showConfirmationModal('verifyModal');
            };
        } else {
            verifyBtn.style.display = 'none';
        }

        viewModal.classList.add('show');
        viewModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeViewModal() {
        viewModal.classList.remove('show');
        viewModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Event listeners
    searchBar.addEventListener("input", renderRegistrations);
    statusFilter.addEventListener("change", renderRegistrations);

    // Close modal when clicking overlay
    document.querySelector('#viewModal .modal-overlay')?.addEventListener('click', closeViewModal);

    // Initial render
    renderRegistrations();
</script>