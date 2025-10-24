        <?php
$items = $data['items'] ?? [];
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/inventory_manager/inventory.css">

<div class="content-area">
    <!-- Page Header -->
    <?php
    $pageHeaderConfig = [
        'title' => 'Inventory Management',
        'description' => 'Manage your solar equipment inventory',
    ];
    $config = $pageHeaderConfig;
    require APPROOT . '/views/inc/components/page_header.php';
    ?>

    <!-- Filter Bar -->
    <div class="card shadow-lg rounded-xl mb-4">
        <div class="card-body">
            <div class="toolbar">
                <input type="text" id="searchInput" placeholder="Search by name or category..." class="form-control">
                <select id="nameFilter" class="form-control">
                    <option value="all">All Item Names</option>
                </select>
                <select id="categoryFilter" class="form-control">
                    <option value="all">All Categories</option>
                </select>
                <select id="stockFilter" class="form-control">
                    <option value="all">All Stock</option>
                    <option value="in-stock">In Stock</option>
                    <option value="low-stock">Low Stock</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="card shadow-lg rounded-xl">
        <div class="card-body">
            <div class="table-header mb-4">
                <h3 class="text-2xl font-semibold">Inventory Items</h3>
                <button class="btn btn-primary rounded-lg" onclick="showAddModal()">
                    <i class="fas fa-plus mr-2"></i>Add New Item
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-sm font-semibold text-secondary">Item ID</th>
                            <th class="text-sm font-semibold text-secondary">Name</th>
                            <th class="text-sm font-semibold text-secondary">Category</th>
                            <th class="text-sm font-semibold text-secondary">Qty</th>
                            <th class="text-sm font-semibold text-secondary">Unit Price (Rs.)</th>
                            <th class="text-sm font-semibold text-secondary">Total Value (Rs.)</th>
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

<!-- Add Item Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <form id="addForm" method="POST" enctype="multipart/form-data" action="<?php echo URLROOT; ?>/inventorymanager/inventory">
            <h3 class="text-2xl font-semibold mb-4">Add New Item</h3>
            
            <div class="card-body">
                <?php
                $inputConfig = [
                    'id' => 'itemName',
                    'name' => 'itemName',
                    'label' => 'Item Name',
                    'type' => 'text',
                    'icon' => 'fas fa-box',
                    'value' => $data['itemName'] ?? '',
                    'error' => $data['itemName_err'] ?? '',
                    'required' => true,
                    'wrapperClass' => 'mb-4'
                ];
                require APPROOT . '/views/inc/components/input_field.php';
                ?>

                <?php
                $inputConfig = [
                    'id' => 'itemCategory',
                    'name' => 'itemCategory',
                    'label' => 'Category',
                    'type' => 'text',
                    'icon' => 'fas fa-tags',
                    'value' => $data['itemCategory'] ?? '',
                    'error' => $data['itemCategory_err'] ?? '',
                    'required' => true,
                    'wrapperClass' => 'mb-4'
                ];
                require APPROOT . '/views/inc/components/input_field.php';
                ?>

                <?php
                $inputConfig = [
                    'id' => 'itemQty',
                    'name' => 'itemQty',
                    'label' => 'Quantity',
                    'type' => 'number',
                    'icon' => 'fas fa-layer-group',
                    'value' => $data['itemQty'] ?? '',
                    'error' => $data['itemQty_err'] ?? '',
                    'required' => true,
                    'wrapperClass' => 'mb-4'
                ];
                require APPROOT . '/views/inc/components/input_field.php';
                ?>

                <?php
                $inputConfig = [
                    'id' => 'itemPrice',
                    'name' => 'itemPrice',
                    'label' => 'Unit Price (Rs.)',
                    'type' => 'number',
                    'icon' => 'fas fa-money-bill-wave',
                    'step' => '0.01',
                    'value' => $data['itemPrice'] ?? '',
                    'error' => $data['itemPrice_err'] ?? '',
                    'required' => true,
                    'wrapperClass' => 'mb-4'
                ];
                require APPROOT . '/views/inc/components/input_field.php';
                ?>

                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2">
                        <i class="fas fa-image mr-2"></i>Item Photo
                    </label>
                    <input type="file" id="itemPhoto" name="itemPhoto" accept="image/*" class="form-control">
                </div>
            </div>

            <div class="modal-buttons">
                <button type="submit" class="btn btn-primary btn-sm rounded-lg">
                    <i class="fas fa-check mr-2"></i>Add Item
                </button>
                <button type="button" class="btn btn-secondary btn-sm rounded-lg" onclick="closeAddModal()">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Item Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <form id="editForm" method="POST" action="<?php echo URLROOT; ?>/inventorymanager/update_item">
            <h3 class="text-2xl font-semibold mb-4">Edit Item</h3>
            
            <div class="card-body">
                <?php
                $inputConfig = [
                    'id' => 'editName',
                    'name' => 'itemName',
                    'label' => 'Item Name',
                    'type' => 'text',
                    'icon' => 'fas fa-box',
                    'value' => '',
                    'required' => true,
                    'wrapperClass' => 'mb-4'
                ];
                require APPROOT . '/views/inc/components/input_field.php';
                ?>

                <?php
                $inputConfig = [
                    'id' => 'editCategory',
                    'name' => 'itemCategory',
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
                $inputConfig = [
                    'id' => 'editQty',
                    'name' => 'itemQty',
                    'label' => 'Quantity',
                    'type' => 'number',
                    'icon' => 'fas fa-layer-group',
                    'value' => '',
                    'required' => true,
                    'wrapperClass' => 'mb-4'
                ];
                require APPROOT . '/views/inc/components/input_field.php';
                ?>

                <?php
                $inputConfig = [
                    'id' => 'editPrice',
                    'name' => 'itemPrice',
                    'label' => 'Unit Price (Rs.)',
                    'type' => 'number',
                    'icon' => 'fas fa-money-bill-wave',
                    'step' => '0.01',
                    'value' => '',
                    'required' => true,
                    'wrapperClass' => 'mb-4'
                ];
                require APPROOT . '/views/inc/components/input_field.php';
                ?>
            </div>

            <input type="hidden" name="inventory_id" id="editInventoryId">

            <div class="modal-buttons">
                <button type="submit" class="btn btn-primary btn-sm rounded-lg">
                    <i class="fas fa-check mr-2"></i>Save Changes
                </button>
                <button type="button" class="btn btn-secondary btn-sm rounded-lg" onclick="closeEditModal()">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Item Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3 class="text-2xl font-semibold mb-4">Delete Item</h3>
        <p id="deleteMessage" class="text-secondary mb-6">Are you sure you want to delete this item?</p>
        
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
        <td class="text-sm font-semibold">${item.id}</td>
        <td class="text-sm">${item.name}</td>
        <td class="text-sm">${item.category}</td>
        <td class="text-sm">${item.qty}</td>
        <td class="text-sm">${item.price.toLocaleString()}</td>
        <td class="text-sm">${total.toLocaleString()}</td>
        <td class="text-sm">
          <span class="badge ${statusClass === 'in-stock' ? 'badge-success' : 'badge-warning'}">
            <i class="fas ${statusClass === 'in-stock' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-1"></i>${statusText}
          </span>
        </td>
        <td class="text-sm">
          <button class="btn btn-primary btn-sm rounded-lg bg-success" onclick="editItem('${item.id}')">
            <i class="fas fa-edit mr-1"></i>Edit
          </button>
          <button class="btn btn-primary btn-sm rounded-lg bg-error" onclick="deleteItem('${item.id}')">
            <i class="fas fa-trash mr-1"></i>Delete
          </button>
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

function editItem(id){
  const item = items.find(i => i.id == id);
  if (!item) return;
  currentEditItem = item;

  document.getElementById("editInventoryId").value = item.id;
  document.getElementById("editName").value = item.name;
  document.getElementById("editCategory").value = item.category;
  document.getElementById("editQty").value = item.qty;
  document.getElementById("editPrice").value = item.price;

  editModal.classList.add("show");
}

function closeEditModal(){ editModal.classList.remove("show"); currentEditItem=null; }

function deleteItem(id){
  const item=items.find(i=>i.id==id);
  currentDeleteItemId=id;
  document.getElementById("deleteMessage").innerText=`Are you sure you want to delete "${item.name}"?`;
  deleteModal.classList.add("show");
}

document.getElementById("confirmDeleteBtn").addEventListener("click", () => {
    if(currentDeleteItemId){
        document.getElementById("deleteInventoryId").value = currentDeleteItemId;
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
