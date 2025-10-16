<?php
$suppliers = [
    ["id"=>"S-1001","name"=>"ABC Electricals","contact"=>"0771234567","category"=>"Electrical","status"=>"Active"],
    ["id"=>"S-1002","name"=>"Battery World","contact"=>"0719876543","category"=>"Storage","status"=>"Active"],
    ["id"=>"S-1003","name"=>"Wiring Co.","contact"=>"0724567890","category"=>"Accessories","status"=>"Inactive"]
];
?>

<style>
.main { display:flex; flex-direction:column; align-items:center; }


.table-container { width:900px; max-width:100%; background:whitesmoke; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
.table-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; }
.table-header h2 { font-size:20px; }
table { width:100%; border-collapse:collapse; }
th, td { text-align:left; padding:10px 12px; border-bottom:1px solid #eee; font-size:14px; }
th { color:white; font-weight:600; }
tr:hover { background:#f5f5f5; }
.status { font-weight:600; font-size:13px; padding:4px 8px; border-radius:10px; }
.active { color:#166534; background:#dcfce7; }
.inactive { color:#92400e; background:#fef3c7; }

.modal { display:none; position:fixed; z-index:5000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.5); align-items:center; }
.modal.show { display:flex; }
.modal-content { background:#fff; width:400px; max-width:90%; margin:auto; padding:20px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.2); }
.modal-content h3 { margin-top:0; }
.modal-buttons{ display:flex; justify-content:space-between; align-items:center; width:100%; margin-top:20px;}
button:hover{ opacity:0.9; }
.toolbar { display:flex; flex-wrap:wrap; width:950px; max-width:100%; justify-content:space-between; align-items:center; margin-bottom:20px; gap:10px; }
.toolbar input, .toolbar select { padding:8px; border:1px solid #ccc; border-radius:5px; flex:1; min-width:150px; }

.cards-container {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
  flex-wrap: wrap;
  justify-content: center;
  width: 900px;
  max-width: 100%;
}
.card {
  background: white;
  flex: 1;
  min-width: 150px;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  text-align: center;
}
.card h3 { margin: 0 0 10px 0; font-size: 16px; color: #555; }
.card p { margin: 0; font-size: 20px; font-weight: 600; }
</style>

<div class="main">

  <!-- Cards -->
  <div class="cards-container">
    <div class="card">
      <h3>Total Suppliers</h3>
      <p id="totalSuppliers">0</p>
    </div>
    <div class="card">
      <h3>Active Suppliers</h3>
      <p id="activeSuppliers">0</p>
    </div>
    <div class="card">
      <h3>Inactive Suppliers</h3>
      <p id="inactiveSuppliers">0</p>
    </div>
  </div>

  <!-- Table -->
  <div class="table-container">
    <div class="table-header">
      <h2>Suppliers</h2>
      <button class="add-btn btn btn-primary btn-lg" onclick="showAddModal()">+ Add New Supplier</button>
    </div>

    <div class="toolbar">
      <input type="text" id="searchInput" placeholder="Search by name or category...">

      <select id="categoryFilter">
        <option value="all">All Categories</option>
      </select>

      <select id="statusFilter">
        <option value="all">All Status</option>
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
      </select>
    </div>

    <table id="suppliersTable">
      <thead>
        <tr class="bg-primary">
          <th>Supplier ID</th>
          <th>Name</th>
          <th>Contact</th>
          <th>Category</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="tableBody"></tbody>
    </table>
  </div>
</div>

<!-- Add/Edit/Delete Modals -->
<div id="addModal" class="modal">
  <div class="modal-content">
    <h3>Add New Supplier</h3>
    <label>Name:</label><br>
    <input type="text" id="supplierName" style="width:100%; padding:6px;"><br><br>
    <label>Contact:</label><br>
    <input type="text" id="supplierContact" style="width:100%; padding:6px;"><br><br>
    <label>Category:</label><br>
    <input type="text" id="supplierCategory" style="width:100%; padding:6px;"><br><br>
    <label>Status:</label><br>
    <select id="supplierStatus" style="width:100%; padding:6px;">
      <option value="Active">Active</option>
      <option value="Inactive">Inactive</option>
    </select>
    <div class="modal-buttons">
      <button class="add-btn btn btn-primary btn-sm" onclick="addSupplier()">Add</button>
      <button class="delete-btn btn btn-primary btn-sm bg-secondary" onclick="closeAddModal()">Cancel</button>
    </div>
  </div>
</div>

<div id="editModal" class="modal">
  <div class="modal-content">
    <h3>Edit Supplier</h3>
    <label>Name:</label><br>
    <input type="text" id="editName" style="width:100%; padding:6px;"><br><br>
    <label>Contact:</label><br>
    <input type="text" id="editContact" style="width:100%; padding:6px;"><br><br>
    <label>Category:</label><br>
    <input type="text" id="editCategory" style="width:100%; padding:6px;"><br><br>
    <label>Status:</label><br>
    <select id="editStatus" style="width:100%; padding:6px;">
      <option value="Active">Active</option>
      <option value="Inactive">Inactive</option>
    </select>
    <div class="modal-buttons">
      <button class="add-btn btn btn-primary btn-sm" id="saveEditBtn">Save</button>
      <button class="delete-btn btn btn-primary btn-sm bg-secondary" onclick="closeEditModal()">Cancel</button>
    </div>
  </div>
</div>

<div id="deleteModal" class="modal">
  <div class="modal-content">
    <h3>Delete Supplier</h3>
    <p id="deleteMessage">Are you sure you want to delete this supplier?</p>
    <div class="modal-buttons">
      <button class="delete-btn btn btn-primary btn-sm bg-error" id="confirmDeleteBtn">Delete</button>
      <button class="add-btn btn btn-primary btn-sm bg-secondary" id="cancelDeleteBtn">Cancel</button>
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

// Call initially
updateCards();

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
    const statusClass = s.status==="Active"?"active":"inactive";
    tbody.innerHTML+=`
      <tr>
        <td>${s.id}</td>
        <td>${s.name}</td>
        <td>${s.contact}</td>
        <td>${s.category}</td>
        <td><span class="status ${statusClass}">${s.status}</span></td>
        <td>
          <button class="edit-btn btn btn-primary btn-sm bg-success" onclick="editSupplier('${s.id}')">Edit</button>
          <button class="delete-btn btn btn-primary btn-sm bg-error" onclick="deleteSupplier('${s.id}')">Delete</button>
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

// ---------- Add/Edit/Delete Modal Functions ----------
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
