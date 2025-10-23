<?php
$quotations = [
    ["id"=>"QT-1001","customer"=>"John Smith","email"=>"john@example.com","date"=>"2025-09-17","amount"=>5450.00,"status"=>"Pending"],
    ["id"=>"QT-1002","customer"=>"Sarah Johnson","email"=>"sarah@example.com","date"=>"2025-09-15","amount"=>8750.00,"status"=>"Approved"],
    ["id"=>"QT-1003","customer"=>"Michael Brown","email"=>"michael@example.com","date"=>"2025-09-10","amount"=>3200.00,"status"=>"Rejected"],
    ["id"=>"QT-1004","customer"=>"Emily Davis","email"=>"emily@example.com","date"=>"2025-09-12","amount"=>12450.00,"status"=>"Approved"],
    ["id"=>"QT-1005","customer"=>"Robert Wilson","email"=>"robert@example.com","date"=>"2025-09-18","amount"=>6800.00,"status"=>"Pending"]
];
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/operation_manager/quotation.css">

<div class="content-area">
    <!-- Page Header -->
    <?php
    $pageHeaderConfig = [
        'title' => 'Quotation Management',
        'description' => 'Create and manage customer quotations',
        'show_back' => true,
        'back_url' => URLROOT . '/operationmanager/dashboard',
        'back_label' => 'Back to Dashboard'
    ];
    $config = $pageHeaderConfig;
    require APPROOT . '/views/inc/components/page_header.php';
    ?>

    <!-- Stats Grid -->
    <div class="stats-grid mb-6">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Pending Quotations</p>
                <p class="stat-value" id="pendingCount">0</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Approved Quotations</p>
                <p class="stat-value" id="approvedCount">0</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon error">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Rejected Quotations</p>
                <p class="stat-value" id="rejectedCount">0</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Total Value</p>
                <p class="stat-value" id="totalValue">Rs. 0</p>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card shadow-lg rounded-xl mb-4">
        <div class="card-body">
            <div class="toolbar">
                <input type="text" id="searchInput" placeholder="Search by customer or ID..." class="form-control">
                <select id="statusFilter" class="form-control">
                    <option value="all">All Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="card shadow-lg rounded-xl">
        <div class="card-body">
            <div class="table-header mb-4">
                <h3 class="text-2xl font-semibold">Quotations List</h3>
                <button class="btn btn-primary rounded-lg" onclick="showAddModal()">
                    <i class="fas fa-plus mr-2"></i>Create New Quotation
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-sm font-semibold text-secondary">Quote ID</th>
                            <th class="text-sm font-semibold text-secondary">Customer</th>
                            <th class="text-sm font-semibold text-secondary">Email</th>
                            <th class="text-sm font-semibold text-secondary">Date</th>
                            <th class="text-sm font-semibold text-secondary">Amount (Rs.)</th>
                            <th class="text-sm font-semibold text-secondary">Status</th>
                            <th class="text-sm font-semibold text-secondary">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Quotation Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <h3 class="text-2xl font-semibold mb-4">Create New Quotation</h3>
        
        <div class="card-body">
            <?php
            $inputConfig = [
                'id' => 'customerName',
                'name' => 'customer',
                'label' => 'Customer Name',
                'type' => 'text',
                'icon' => 'fas fa-user',
                'value' => '',
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/input_field.php';
            ?>

            <?php
            $inputConfig = [
                'id' => 'customerEmail',
                'name' => 'email',
                'label' => 'Customer Email',
                'type' => 'email',
                'icon' => 'fas fa-envelope',
                'value' => '',
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/input_field.php';
            ?>

            <?php
            $inputConfig = [
                'id' => 'quotationDate',
                'name' => 'date',
                'label' => 'Quotation Date',
                'type' => 'date',
                'icon' => 'fas fa-calendar',
                'value' => date('Y-m-d'),
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/input_field.php';
            ?>

            <?php
            $inputConfig = [
                'id' => 'quotationAmount',
                'name' => 'amount',
                'label' => 'Amount (Rs.)',
                'type' => 'number',
                'icon' => 'fas fa-money-bill-wave',
                'step' => '0.01',
                'value' => '',
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/input_field.php';
            ?>

            <?php
            $selectConfig = [
                'id' => 'quotationStatus',
                'name' => 'status',
                'label' => 'Status',
                'value' => 'Pending',
                'options' => [
                    'Pending' => 'Pending',
                    'Approved' => 'Approved',
                    'Rejected' => 'Rejected'
                ],
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/select_field.php';
            ?>
        </div>

        <div class="modal-buttons">
            <button class="btn btn-primary btn-sm rounded-lg" onclick="addQuotation()">
                <i class="fas fa-check mr-2"></i>Create Quotation
            </button>
            <button class="btn btn-secondary btn-sm rounded-lg" onclick="closeAddModal()">
                <i class="fas fa-times mr-2"></i>Cancel
            </button>
        </div>
    </div>
</div>

<!-- Edit Quotation Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <h3 class="text-2xl font-semibold mb-4">Edit Quotation</h3>
        
        <div class="card-body">
            <?php
            $inputConfig = [
                'id' => 'editCustomer',
                'name' => 'customer',
                'label' => 'Customer Name',
                'type' => 'text',
                'icon' => 'fas fa-user',
                'value' => '',
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/input_field.php';
            ?>

            <?php
            $inputConfig = [
                'id' => 'editEmail',
                'name' => 'email',
                'label' => 'Customer Email',
                'type' => 'email',
                'icon' => 'fas fa-envelope',
                'value' => '',
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/input_field.php';
            ?>

            <?php
            $inputConfig = [
                'id' => 'editDate',
                'name' => 'date',
                'label' => 'Quotation Date',
                'type' => 'date',
                'icon' => 'fas fa-calendar',
                'value' => '',
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/input_field.php';
            ?>

            <?php
            $inputConfig = [
                'id' => 'editAmount',
                'name' => 'amount',
                'label' => 'Amount (Rs.)',
                'type' => 'number',
                'icon' => 'fas fa-money-bill-wave',
                'step' => '0.01',
                'value' => '',
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/input_field.php';
            ?>

            <?php
            $selectConfig = [
                'id' => 'editStatus',
                'name' => 'status',
                'label' => 'Status',
                'value' => 'Pending',
                'options' => [
                    'Pending' => 'Pending',
                    'Approved' => 'Approved',
                    'Rejected' => 'Rejected'
                ],
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/select_field.php';
            ?>
        </div>

        <div class="modal-buttons">
            <button class="btn btn-primary btn-sm rounded-lg" id="saveEditBtn">
                <i class="fas fa-check mr-2"></i>Save Changes
            </button>
            <button class="btn btn-secondary btn-sm rounded-lg" onclick="closeEditModal()">
                <i class="fas fa-times mr-2"></i>Cancel
            </button>
        </div>
    </div>
</div>

<!-- Delete Quotation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3 class="text-2xl font-semibold mb-4">Delete Quotation</h3>
        <p id="deleteMessage" class="text-secondary mb-6">Are you sure you want to delete this quotation?</p>
        
        <div class="modal-buttons">
            <button class="btn btn-danger btn-sm rounded-lg" id="confirmDeleteBtn">
                <i class="fas fa-trash mr-2"></i>Delete
            </button>
            <button class="btn btn-secondary btn-sm rounded-lg" id="cancelDeleteBtn">
                <i class="fas fa-times mr-2"></i>Cancel
            </button>
        </div>
    </div>
</div>

<script>
let quotations = <?php echo json_encode($quotations); ?>;
let currentEditQuotation = null;
let currentDeleteQuotationId = null;

// ---------- Cards Update ----------
function updateCards(){
  const total = quotations.length;
  const pending = quotations.filter(q=>q.status==="Pending").length;
  const approved = quotations.filter(q=>q.status==="Approved").length;
  const rejected = quotations.filter(q=>q.status==="Rejected").length;
  const totalAmount = quotations.reduce((sum,q)=>sum+q.amount,0);

  document.getElementById("pendingCount").innerText = pending;
  document.getElementById("approvedCount").innerText = approved;
  document.getElementById("rejectedCount").innerText = rejected;
  document.getElementById("totalValue").innerText = "Rs. " + totalAmount.toLocaleString();
}

// Helper to update table and cards together
function refreshTableAndCards(){
  filterQuotations();
  updateCards();
}

// ---------- Render Table ----------
function renderTable(data){
  const tbody=document.getElementById("tableBody");
  tbody.innerHTML="";
  data.forEach(q=>{
    const statusClass = q.status==="Pending"?"badge-warning":q.status==="Approved"?"badge-success":"badge-error";
    const statusIcon = q.status==="Pending"?"fa-clock":q.status==="Approved"?"fa-check-circle":"fa-times-circle";
    tbody.innerHTML+=`
      <tr>
        <td class="text-sm font-semibold">${q.id}</td>
        <td class="text-sm">${q.customer}</td>
        <td class="text-sm">${q.email}</td>
        <td class="text-sm">${q.date}</td>
        <td class="text-sm">Rs. ${q.amount.toLocaleString()}</td>
        <td class="text-sm">
          <span class="badge ${statusClass}">
            <i class="fas ${statusIcon} mr-1"></i>${q.status}
          </span>
        </td>
        <td class="text-sm">
          <button class="btn btn-primary btn-sm rounded-lg bg-success" onclick="editQuotation('${q.id}')">
            <i class="fas fa-edit mr-1"></i>Edit
          </button>
          <button class="btn btn-primary btn-sm rounded-lg bg-error" onclick="deleteQuotation('${q.id}')">
            <i class="fas fa-trash mr-1"></i>Delete
          </button>
        </td>
      </tr>`;
  });
}

refreshTableAndCards();

// ---------- Filtering ----------
function filterQuotations(){
  const searchTerm=document.getElementById("searchInput").value.toLowerCase();
  const status=document.getElementById("statusFilter").value;

  let filtered = quotations.filter(q=>{
    return (q.customer.toLowerCase().includes(searchTerm) || q.id.toLowerCase().includes(searchTerm) || q.email.toLowerCase().includes(searchTerm)) &&
           (status==="all" || q.status===status);
  });
  renderTable(filtered);
}

document.getElementById("searchInput").addEventListener("input",refreshTableAndCards);
document.getElementById("statusFilter").addEventListener("change",refreshTableAndCards);

// ---------- Modal Functions ----------
function showAddModal(){ document.getElementById("addModal").classList.add("show"); }
function closeAddModal(){ document.getElementById("addModal").classList.remove("show"); }
function showEditModal(){ document.getElementById("editModal").classList.add("show"); }
function closeEditModal(){ document.getElementById("editModal").classList.remove("show"); currentEditQuotation=null; }

function addQuotation(){
  const customer=document.getElementById("customerName").value.trim();
  const email=document.getElementById("customerEmail").value.trim();
  const date=document.getElementById("quotationDate").value;
  const amount=parseFloat(document.getElementById("quotationAmount").value);
  const status=document.getElementById("quotationStatus").value;
  if(!customer||!email||!date||!amount){ alert("Fill all fields"); return; }
  const id="QT-"+(quotations.length+1001);
  quotations.push({id,customer,email,date,amount,status});
  refreshTableAndCards();
  closeAddModal();
  ["customerName","customerEmail","quotationAmount"].forEach(id=>document.getElementById(id).value="");
  document.getElementById("quotationDate").value=new Date().toISOString().split('T')[0];
  document.getElementById("quotationStatus").value="Pending";
}

function editQuotation(id){
  const q=quotations.find(x=>x.id===id);
  if(!q) return;
  currentEditQuotation=q;
  document.getElementById("editCustomer").value=q.customer;
  document.getElementById("editEmail").value=q.email;
  document.getElementById("editDate").value=q.date;
  document.getElementById("editAmount").value=q.amount;
  document.getElementById("editStatus").value=q.status;
  showEditModal();
}

document.getElementById("saveEditBtn").addEventListener("click",()=>{
  if(!currentEditQuotation) return;
  const customer=document.getElementById("editCustomer").value.trim();
  const email=document.getElementById("editEmail").value.trim();
  const date=document.getElementById("editDate").value;
  const amount=parseFloat(document.getElementById("editAmount").value);
  const status=document.getElementById("editStatus").value;
  if(!customer||!email||!date||!amount){ alert("Fill all fields"); return; }
  currentEditQuotation.customer=customer;
  currentEditQuotation.email=email;
  currentEditQuotation.date=date;
  currentEditQuotation.amount=amount;
  currentEditQuotation.status=status;
  refreshTableAndCards();
  closeEditModal();
});

function deleteQuotation(id){
  const q=quotations.find(x=>x.id===id);
  if(!q) return;
  currentDeleteQuotationId=id;
  document.getElementById("deleteMessage").innerText=`Are you sure you want to delete quotation "${q.id}" for ${q.customer}?`;
  document.getElementById("deleteModal").classList.add("show");
}

document.getElementById("confirmDeleteBtn").addEventListener("click",()=>{
  if(currentDeleteQuotationId){
    quotations=quotations.filter(x=>x.id!==currentDeleteQuotationId);
    refreshTableAndCards();
    currentDeleteQuotationId=null;
    document.getElementById("deleteModal").classList.remove("show");
  }
});
document.getElementById("cancelDeleteBtn").addEventListener("click",()=>{
  currentDeleteQuotationId=null;
  document.getElementById("deleteModal").classList.remove("show");
});

// Close modals on click outside
window.addEventListener("click",e=>{
  if(e.target===document.getElementById("addModal")) closeAddModal();
  if(e.target===document.getElementById("editModal")) closeEditModal();
  if(e.target===document.getElementById("deleteModal")){
    currentDeleteQuotationId=null;
    document.getElementById("deleteModal").classList.remove("show");
  }
});
</script>