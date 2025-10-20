<?php
$items = $data['items'] ?? []; // ensure $items exists
?>

<style>
.main {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.table-container {
    width: 900px;
    max-width: 100%;
    background: whitesmoke;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.table-header h2 {
    font-size: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    text-align: left;
    padding: 10px 12px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
}

th {
    color: white;
    font-weight: 600;
}

tr:hover {
    background: #f5f5f5;
}

.status {
    font-weight: 600;
    font-size: 13px;
    padding: 4px 8px;
    border-radius: 10px;
}

.in-stock {
    color: #166534;
    background: #dcfce7;
}

.low-stock {
    color: #92400e;
    background: #fef3c7;
}

/* Modals */
.modal {
    display: none;
    position: fixed;
    z-index: 5000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    align-items: center;
}

.modal.show {
    display: flex;
}

.modal-content {
    background: #fff;
    width: 400px;
    max-width: 90%;
    margin: auto;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.modal-content h3 {
    margin-top: 0;
    margin-bottom: 15px;
}

.modal-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    margin-top: 20px;
}

/* Toolbar Inputs */
.toolbar {
    display: flex;
    flex-wrap: wrap;
    width: 950px;
    max-width: 100%;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    gap: 10px;
}

.toolbar input,
.toolbar select {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    flex: 1;
    min-width: 150px;
}
.card-body {
    display: flex;
    flex-direction: column;
    gap: 20px; /* space between input fields */
}



</style>


<div class="main">
  <div class="table-container">
    <div class="table-header">
      <h2>Inventory</h2>
      <button class="add-btn btn btn-primary btn-lg" onclick="showAddModal()">+ Add New Item</button>
    </div>

    <div class="toolbar">
        <input type="text" id="searchInput" placeholder="Search by name or category...">
        <select id="nameFilter"><option value="all">All Item Names</option></select>
        <select id="categoryFilter"><option value="all">All Categories</option></select>
        <select id="stockFilter">
            <option value="all">All Stock</option>
            <option value="in-stock">In Stock</option>
            <option value="low-stock">Low Stock</option>
        </select>
    </div>

    <table id="inventoryTable">
      <thead>
        <tr class="bg-primary">
          <th>Item ID</th>
          <th>Name</th>
          <th>Category</th>
          <th>Qty</th>
          <th>Unit Price (Rs.)</th>
          <th>Total Value (Rs.)</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="tableBody"></tbody>
    </table>
  </div>
</div>
<!-- ✅ Add Modal (componentized) -->
<?php
$addModalSections = [
    [
        'title' => 'Add New Item',
        'fields' => [
            [
                'id' => 'itemName',
                'name' => 'itemName',
                'label' => 'Item Name',
                'type' => 'text',
                'icon' => 'fas fa-box',
                'value' => $data['itemName'] ?? '',
                'error' => $data['itemName_err'] ?? '',
                'required' => true
            ],
            [
                'id' => 'itemCategory',
                'name' => 'itemCategory',
                'label' => 'Category',
                'type' => 'text',
                'icon' => 'fas fa-tags',
                'value' => $data['itemCategory'] ?? '',
                'error' => $data['itemCategory_err'] ?? '',
                'required' => true
            ],
            [
                'id' => 'itemQty',
                'name' => 'itemQty',
                'label' => 'Quantity',
                'type' => 'number',
                'icon' => 'fas fa-layer-group',
                'value' => $data['itemQty'] ?? '',
                'error' => $data['itemQty_err'] ?? '',
                'required' => true
            ],
            [
                'id' => 'itemPrice',
                'name' => 'itemPrice',
                'label' => 'Unit Price (Rs.)',
                'type' => 'number',
                'icon' => 'fas fa-money-bill-wave',
                'step' => '0.01',
                'value' => $data['itemPrice'] ?? '',
                'error' => $data['itemPrice_err'] ?? '',
                'required' => true
            ],
        ]
    ]
];
?>

<div id="addModal" class="modal">
  <div class="modal-content add">
    <!-- Begin Form -->
    <form id="addForm" method="POST" action="<?php echo URLROOT; ?>/inventorymanager/inventory">
      <?php foreach ($addModalSections as $section): ?>
        <h3><?php echo htmlspecialchars($section['title']); ?></h3>
        <div class="card-body">
          <?php foreach ($section['fields'] as $field): ?>
            <?php 
            $inputConfig = $field; // pass the field directly to the input component
            require APPROOT . '/views/inc/components/input_field.php'; 
            ?>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>

      <div class="modal-buttons mt-3">
        <button type="submit" class="add-btn btn btn-primary btn-sm">Add</button>
        <button type="button" class="delete-btn btn btn-secondary btn-sm" onclick="closeAddModal()">Cancel</button>
      </div>
    </form>
    <!-- End Form -->
  </div>
</div>



<!-- ✅ Edit Modal (using your component loop structure) -->
<!-- Edit Modal -->
<div id="editModal" class="modal">
  <div class="modal-content edit">
    <form id="editForm" method="POST" action="<?php echo URLROOT; ?>/inventorymanager/update_item">
      <h3>Edit Item</h3>
      
      <div class="card-body">
            <?php 
        $inputConfig = [
            'id' => 'editName',
            'name' => 'itemName',
            'label' => 'Item Name',
            'type' => 'text',
            'icon' => 'fas fa-box', // optional, you can pick any relevant icon
            'value' => $data['itemName'] ?? '',
            'error' => $data['itemName_err'] ?? '',
            'required' => true
        ]; 
        require APPROOT . '/views/inc/components/input_field.php'; 
        ?>
      
        <?php 
        $inputConfig = [
            'id' => 'editCategory',
            'name' => 'itemCategory',
            'label' => 'Category',
            'type' => 'text',
            'icon' => 'fas fa-tags', // optional icon
            'value' => $data['itemCategory'] ?? '',
            'error' => $data['itemCategory_err'] ?? '',
            'required' => true
        ]; 
        require APPROOT . '/views/inc/components/input_field.php'; 
        ?>
        


     <?php 
            $inputConfig = [
                'id' => 'editQty',
                'name' => 'itemQty',
                'label' => 'Quantity',
                'type' => 'number',
                'icon' => 'fas fa-layer-group', // optional, pick any relevant icon
                'value' => $data['itemQty'] ?? '',
                'error' => $data['itemQty_err'] ?? '',
                'required' => true
            ]; 
            require APPROOT . '/views/inc/components/input_field.php'; 
      ?>


      <?php 
            $inputConfig = [
                'id' => 'editPrice',
                'name' => 'itemPrice',
                'label' => 'Unit Price (Rs.)',
                'type' => 'number',
                'icon' => 'fas fa-money-bill-wave', // optional, pick any icon you like
                'value' => $data['itemPrice'] ?? '',
                'error' => $data['itemPrice_err'] ?? '',
                'required' => true,
                'step' => '0.01' // preserves your decimal step
            ]; 
            require APPROOT . '/views/inc/components/input_field.php'; 
       ?>

      </div>
                            

      <!-- Hidden input for inventory ID -->
      <input type="hidden" name="inventory_id" id="editInventoryId">

      <div class="modal-buttons" style="margin-top:15px;">
        <button type="submit" class="btn btn-primary btn-sm">Save</button>
        <button type="button" class="btn btn-secondary btn-sm" onclick="closeEditModal()">Cancel</button>
      </div>
    </form>
  </div>
</div>


<!-- Delete Modal (unchanged) -->
<div id="deleteModal" class="modal">
  <div class="modal-content delete">
    <h3>Delete Item</h3>
    <p id="deleteMessage">Are you sure you want to delete this item?</p>
    <div class="modal-buttons">
      <button class="delete-btn btn btn-primary btn-sm bg-error" id="confirmDeleteBtn">Delete</button>
      <button class="add-btn btn btn-primary btn-sm bg-secondary" id="cancelDeleteBtn">Cancel</button>
    </div>
  </div>
</div>

<form id="deleteForm" method="POST" action="<?php echo URLROOT; ?>/inventorymanager/delete_item">
    <input type="hidden" name="inventory_id" id="deleteInventoryId">
</form>







<script>

let items = <?php echo json_encode(array_map(function($i){
  return [
    'id'       => isset($i->inventory_id) ? (string)$i->inventory_id : (string)($i['inventory_id'] ?? ''),
    'name'     => $i->item_name ?? $i['item_name'] ?? '',
    'category' => $i->category ?? $i['category'] ?? '',
    'qty'      => (int)($i->quantity ?? $i['quantity'] ?? 0),
    'price'    => (float)($i->unit_price ?? $i['unit_price'] ?? 0),
  ];
}, $items), JSON_NUMERIC_CHECK); ?>;









// ---------- Render Table ----------
function renderTable(data) {
  const tbody = document.getElementById("tableBody");
  tbody.innerHTML = "";
  data.forEach(item => {
    const total = item.qty * item.price;
    const statusClass = item.qty <= 5 ? "low-stock" : "in-stock";
    const statusText = item.qty <= 5 ? "Low Stock" : "In Stock";
    const row = `
      <tr>
        <td>${item.id}</td>
        <td>${item.name}</td>
        <td>${item.category}</td>
        <td>${item.qty}</td>
        <td>${item.price.toLocaleString()}</td>
        <td>${total.toLocaleString()}</td>
        <td><span class="status ${statusClass}">${statusText}</span></td>
        <td>
          <button class="edit-btn btn btn-primary btn-sm bg-success" onclick="editItem('${item.id}')">Edit</button>
          <button class="delete-btn btn btn-primary btn-sm bg-error" onclick="deleteItem('${item.id}')">Delete</button>
        </td>
      </tr>
    `;
    tbody.innerHTML += row;
  });
}

// ---------- Populate Filters ----------
function populateFilters() {
  const nameFilter = document.getElementById("nameFilter");
  const categoryFilter = document.getElementById("categoryFilter");
  const names = [...new Set(items.map(i => i.name))];
  const categories = [...new Set(items.map(i => i.category))];

  names.forEach(n => { if (!Array.from(nameFilter.options).some(o => o.value===n)) nameFilter.add(new Option(n,n)); });
  categories.forEach(c => { if (!Array.from(categoryFilter.options).some(o => o.value===c)) categoryFilter.add(new Option(c,c)); });
}

// ---------- Apply Filters ----------
function applyFilters() {
  const searchTerm = document.getElementById("searchInput").value.toLowerCase();
  const nameValue = document.getElementById("nameFilter").value;
  const categoryValue = document.getElementById("categoryFilter").value;
  const stockValue = document.getElementById("stockFilter").value;

  const filtered = items.filter(i => {
    let keep = true;
    if(nameValue!=="all") keep = keep && i.name===nameValue;
    if(categoryValue!=="all") keep = keep && i.category===categoryValue;
    if(stockValue!=="all") keep = keep && ((stockValue==="in-stock" && i.qty>5) || (stockValue==="low-stock" && i.qty<=5));
    if(searchTerm) keep = keep && (i.name.toLowerCase().includes(searchTerm) || i.category.toLowerCase().includes(searchTerm));
    return keep;
  });

  renderTable(filtered);
}

// ---------- Filter Listeners ----------
["searchInput","nameFilter","categoryFilter","stockFilter"].forEach(id => {
  document.getElementById(id).addEventListener("input", applyFilters);
  document.getElementById(id).addEventListener("change", applyFilters);
});

// ---------- Modals & CRUD ----------
const addModal = document.getElementById("addModal");
const editModal = document.getElementById("editModal");
const deleteModal = document.getElementById("deleteModal");
let currentEditItem=null, currentDeleteItemId=null;

function showAddModal() { addModal.classList.add("show"); }
function closeAddModal() { addModal.classList.remove("show"); }
// function addItem() {
//   const name=document.getElementById("itemName").value.trim();
//   const category=document.getElementById("itemCategory").value.trim();
//   const qty=parseInt(document.getElementById("itemQty").value);
//   const price=parseFloat(document.getElementById("itemPrice").value);
//   if(!name||!category||isNaN(qty)||isNaN(price)){ alert("Fill all fields correctly"); return; }
//   items.push({id:"I-"+(items.length+1001), name, category, qty, price});
//   populateFilters(); applyFilters();
//   ["itemName","itemCategory","itemQty","itemPrice"].forEach(id=>document.getElementById(id).value="");
//   closeAddModal();
// }

function editItem(id){
  const item = items.find(i => i.id == id);
  if (!item) return;
  currentEditItem = item;

  document.getElementById("editInventoryId").value = item.id;  // <-- set ID
  document.getElementById("editName").value = item.name;
  document.getElementById("editCategory").value = item.category;
  document.getElementById("editQty").value = item.qty;
  document.getElementById("editPrice").value = item.price;

  editModal.classList.add("show");
}

function closeEditModal(){ editModal.classList.remove("show"); currentEditItem=null; }

// document.getElementById("saveEditBtn").addEventListener("click", ()=>{
//   if(!currentEditItem)return;
//   const name=document.getElementById("editName").value.trim();
//   const category=document.getElementById("editCategory").value.trim();
//   const qty=parseInt(document.getElementById("editQty").value);
//   const price=parseFloat(document.getElementById("editPrice").value);
//   if(!name||!category||isNaN(qty)||isNaN(price)){ alert("Fill all fields correctly"); return; }
//   Object.assign(currentEditItem,{name,category,qty,price});
//   populateFilters(); applyFilters();
//   closeEditModal();
// });

function deleteItem(id){
  //not 3 === works as intended
  const item=items.find(i=>i.id==id);
  currentDeleteItemId=id;
  document.getElementById("deleteMessage").innerText=`Are you sure you want to delete "${item.name}"?`;
  deleteModal.classList.add("show");



}

// document.getElementById("confirmDeleteBtn").addEventListener("click", ()=>{
//   if(currentDeleteItemId){
//     const idx=items.findIndex(i=>i.id===currentDeleteItemId);
//     if(idx!==-1) items.splice(idx,1);
//     populateFilters(); applyFilters();
//     currentDeleteItemId=null;
//     deleteModal.classList.remove("show");
//   }
// });

document.getElementById("confirmDeleteBtn").addEventListener("click", () => {
    if(currentDeleteItemId){
        // Set the hidden input value
        document.getElementById("deleteInventoryId").value = currentDeleteItemId;
        // Submit the form (page will reload after deletion)
        document.getElementById("deleteForm").submit();
    }
});

document.getElementById("cancelDeleteBtn").addEventListener("click", ()=>{
  currentDeleteItemId=null;
  deleteModal.classList.remove("show");
});

window.addEventListener("click", e=>{
  if(e.target===addModal) closeAddModal();
  if(e.target===editModal) closeEditModal();
  if(e.target===deleteModal){ currentDeleteItemId=null; deleteModal.classList.remove("show"); }
});

populateFilters();
renderTable(items);
</script>     

