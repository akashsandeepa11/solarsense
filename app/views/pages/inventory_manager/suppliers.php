<?php
$suppliers = [
    ["id"=>"S-1001","name"=>"ABC Electricals","contact"=>"0771234567","category"=>"Electrical","status"=>"Active"],
    ["id"=>"S-1002","name"=>"Battery World","contact"=>"0719876543","category"=>"Storage","status"=>"Active"],
    ["id"=>"S-1003","name"=>"Wiring Co.","contact"=>"0724567890","category"=>"Accessories","status"=>"Inactive"],
    ["id"=>"S-1004","name"=>"Solar Tech Ltd","contact"=>"0754321098","category"=>"Equipment","status"=>"Active"],
    ["id"=>"S-1005","name"=>"Power Solutions","contact"=>"0798765432","category"=>"Electrical","status"=>"Inactive"]
];
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/inventory_manager/suppliers.css">

<div class="content-area">
    <!-- Page Header -->
    <?php
    $pageHeaderConfig = [
        'title' => 'Suppliers Management',
        'description' => 'Manage your solar equipment suppliers and vendors'
    ];
    $config = $pageHeaderConfig;
    require APPROOT . '/views/inc/components/page_header.php';
    ?>

    <!-- Stats Cards -->
    <div class="stats-grid mb-6">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-store"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Total Suppliers</p>
                <p class="stat-value" id="totalSuppliers">0</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Active Suppliers</p>
                <p class="stat-value" id="activeSuppliers">0</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-pause-circle"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Inactive Suppliers</p>
                <p class="stat-value" id="inactiveSuppliers">0</p>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card shadow-lg rounded-xl mb-4">
        <div class="card-body">
            <div class="toolbar">
                <input type="text" id="searchInput" placeholder="Search by name or category..." class="form-control">
                <select id="categoryFilter" class="form-control">
                    <option value="all">All Categories</option>
                </select>
                <select id="statusFilter" class="form-control">
                    <option value="all">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="card shadow-lg rounded-xl">
        <div class="card-body">
            <div class="table-header mb-4">
                <h3 class="text-2xl font-semibold">Suppliers List</h3>
                <button class="btn btn-primary rounded-lg" onclick="showAddModal()">
                    <i class="fas fa-plus mr-2"></i>Add New Supplier
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-sm font-semibold text-secondary">Supplier ID</th>
                            <th class="text-sm font-semibold text-secondary">Name</th>
                            <th class="text-sm font-semibold text-secondary">Contact</th>
                            <th class="text-sm font-semibold text-secondary">Category</th>
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

<!-- Add Supplier Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <h3 class="text-2xl font-semibold mb-4">Add New Supplier</h3>
        
        <div class="card-body">
            <?php
            $inputConfig = [
                'id' => 'supplierName',
                'name' => 'name',
                'label' => 'Supplier Name',
                'type' => 'text',
                'icon' => 'fas fa-building',
                'value' => '',
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/input_field.php';
            ?>

            <?php
            $inputConfig = [
                'id' => 'supplierContact',
                'name' => 'contact',
                'label' => 'Contact Number',
                'type' => 'tel',
                'icon' => 'fas fa-phone',
                'value' => '',
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/input_field.php';
            ?>

            <?php
            $inputConfig = [
                'id' => 'supplierCategory',
                'name' => 'category',
                'label' => 'Category',
                'type' => 'text',
                'icon' => 'fas fa-tags',
                'value' => '',
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/input_field.php';
            ?>

            <?php
            $selectConfig = [
                'id' => 'supplierStatus',
                'name' => 'status',
                'label' => 'Status',
                'value' => 'Active',
                'options' => [
                    'Active' => 'Active',
                    'Inactive' => 'Inactive'
                ],
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/select_field.php';
            ?>
        </div>

        <div class="modal-buttons">
            <button class="btn btn-primary btn-sm rounded-lg" onclick="addSupplier()">
                <i class="fas fa-check mr-2"></i>Add Supplier
            </button>
            <button class="btn btn-secondary btn-sm rounded-lg" onclick="closeAddModal()">
                <i class="fas fa-times mr-2"></i>Cancel
            </button>
        </div>
    </div>
</div>

<!-- Edit Supplier Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <h3 class="text-2xl font-semibold mb-4">Edit Supplier</h3>
        
        <div class="card-body">
            <?php
            $inputConfig = [
                'id' => 'editName',
                'name' => 'name',
                'label' => 'Supplier Name',
                'type' => 'text',
                'icon' => 'fas fa-building',
                'value' => '',
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/input_field.php';
            ?>

            <?php
            $inputConfig = [
                'id' => 'editContact',
                'name' => 'contact',
                'label' => 'Contact Number',
                'type' => 'tel',
                'icon' => 'fas fa-phone',
                'value' => '',
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/input_field.php';
            ?>

            <?php
            $inputConfig = [
                'id' => 'editCategory',
                'name' => 'category',
                'label' => 'Category',
                'type' => 'text',
                'icon' => 'fas fa-tags',
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
                'value' => 'Active',
                'options' => [
                    'Active' => 'Active',
                    'Inactive' => 'Inactive'
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

<!-- Delete Supplier Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3 class="text-2xl font-semibold mb-4">Delete Supplier</h3>
        <p id="deleteMessage" class="text-secondary mb-6">Are you sure you want to delete this supplier?</p>
        
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
let suppliers = <?php echo json_encode($suppliers); ?>;
let currentEditSupplier = null;
let currentDeleteSupplierId = null;

// ---------- Cards Update ----------
function updateCards(){
  const total = suppliers.length;
  const active = suppliers.filter(s=>s.status==="Active").length;
  const inactive = suppliers.filter(s=>s.status==="Inactive").length;

  document.getElementById("totalSuppliers").innerText = total;
  document.getElementById("activeSuppliers").innerText = active;
  document.getElementById("inactiveSuppliers").innerText = inactive;
}

// Helper to update table and cards together
function refreshTableAndCards(){
  filterSuppliers();
  updateCards();
}

// ---------- Render Table ----------
function renderTable(data){
  const tbody=document.getElementById("tableBody");
  tbody.innerHTML="";
  data.forEach(s=>{
    const statusClass = s.status==="Active"?"badge-success":"badge-warning";
    const statusIcon = s.status==="Active"?"fa-check-circle":"fa-pause-circle";
    tbody.innerHTML+=`
      <tr>
        <td class="text-sm font-semibold">${s.id}</td>
        <td class="text-sm">${s.name}</td>
        <td class="text-sm">${s.contact}</td>
        <td class="text-sm">${s.category}</td>
        <td class="text-sm">
          <span class="badge ${statusClass}">
            <i class="fas ${statusIcon} mr-1"></i>${s.status}
          </span>
        </td>
        <td class="text-sm">
          <button class="btn btn-primary btn-sm rounded-lg bg-success" onclick="editSupplier('${s.id}')">
            <i class="fas fa-edit mr-1"></i>Edit
          </button>
          <button class="btn btn-primary btn-sm rounded-lg bg-error" onclick="deleteSupplier('${s.id}')">
            <i class="fas fa-trash mr-1"></i>Delete
          </button>
        </td>
      </tr>`;
  });
}

// ---------- Populate Category Filter ----------
function populateCategoryFilter(){
  const categoryFilter=document.getElementById("categoryFilter");
  const categories=[...new Set(suppliers.map(s=>s.category))];
  categories.forEach(c=>{
    const option=document.createElement("option");
    option.value=c;
    option.text=c;
    categoryFilter.add(option);
  });
}
populateCategoryFilter();
refreshTableAndCards();

// ---------- Filtering ----------
function filterSuppliers(){
  const searchTerm=document.getElementById("searchInput").value.toLowerCase();
  const category=document.getElementById("categoryFilter").value;
  const status=document.getElementById("statusFilter").value;

  let filtered = suppliers.filter(s=>{
    return (s.name.toLowerCase().includes(searchTerm) || s.category.toLowerCase().includes(searchTerm)) &&
           (category==="all" || s.category===category) &&
           (status==="all" || s.status===status);
  });
  renderTable(filtered);
}

document.getElementById("searchInput").addEventListener("input",refreshTableAndCards);
document.getElementById("categoryFilter").addEventListener("change",refreshTableAndCards);
document.getElementById("statusFilter").addEventListener("change",refreshTableAndCards);

// ---------- Modal Functions ----------
function showAddModal(){ document.getElementById("addModal").classList.add("show"); }
function closeAddModal(){ document.getElementById("addModal").classList.remove("show"); }
function showEditModal(){ document.getElementById("editModal").classList.add("show"); }
function closeEditModal(){ document.getElementById("editModal").classList.remove("show"); currentEditSupplier=null; }

function addSupplier(){
  const name=document.getElementById("supplierName").value.trim();
  const contact=document.getElementById("supplierContact").value.trim();
  const category=document.getElementById("supplierCategory").value.trim();
  const status=document.getElementById("supplierStatus").value;
  if(!name||!contact||!category){ alert("Fill all fields"); return; }
  const id="S-"+(suppliers.length+1001);
  suppliers.push({id,name,contact,category,status});
  if(![...document.getElementById("categoryFilter").options].some(o=>o.value===category)){
    const option=document.createElement("option"); option.value=category; option.text=category;
    document.getElementById("categoryFilter").add(option);
  }
  refreshTableAndCards();
  closeAddModal();
  ["supplierName","supplierContact","supplierCategory"].forEach(id=>document.getElementById(id).value="");
  document.getElementById("supplierStatus").value="Active";
}

function editSupplier(id){
  const s=suppliers.find(x=>x.id===id);
  if(!s) return;
  currentEditSupplier=s;
  document.getElementById("editName").value=s.name;
  document.getElementById("editContact").value=s.contact;
  document.getElementById("editCategory").value=s.category;
  document.getElementById("editStatus").value=s.status;
  showEditModal();
}

document.getElementById("saveEditBtn").addEventListener("click",()=>{
  if(!currentEditSupplier) return;
  const name=document.getElementById("editName").value.trim();
  const contact=document.getElementById("editContact").value.trim();
  const category=document.getElementById("editCategory").value.trim();
  const status=document.getElementById("editStatus").value;
  if(!name||!contact||!category){ alert("Fill all fields"); return; }
  currentEditSupplier.name=name;
  currentEditSupplier.contact=contact;
  currentEditSupplier.category=category;
  currentEditSupplier.status=status;

  if(![...document.getElementById("categoryFilter").options].some(o=>o.value===category)){
    const option=document.createElement("option"); option.value=category; option.text=category;
    document.getElementById("categoryFilter").add(option);
  }

  refreshTableAndCards();
  closeEditModal();
});

function deleteSupplier(id){
  const s=suppliers.find(x=>x.id===id);
  if(!s) return;
  currentDeleteSupplierId=id;
  document.getElementById("deleteMessage").innerText=`Are you sure you want to delete "${s.name}"?`;
  document.getElementById("deleteModal").classList.add("show");
}

document.getElementById("confirmDeleteBtn").addEventListener("click",()=>{
  if(currentDeleteSupplierId){
    suppliers=suppliers.filter(x=>x.id!==currentDeleteSupplierId);
    refreshTableAndCards();
    currentDeleteSupplierId=null;
    document.getElementById("deleteModal").classList.remove("show");
  }
});
document.getElementById("cancelDeleteBtn").addEventListener("click",()=>{
  currentDeleteSupplierId=null;
  document.getElementById("deleteModal").classList.remove("show");
});

// Close modals on click outside
window.addEventListener("click",e=>{
  if(e.target===document.getElementById("addModal")) closeAddModal();
  if(e.target===document.getElementById("editModal")) closeEditModal();
  if(e.target===document.getElementById("deleteModal")){
    currentDeleteSupplierId=null;
    document.getElementById("deleteModal").classList.remove("show");
  }
});
</script>