<?php
$items      = $data['items']      ?? [];
$categories = $data['categories'] ?? [];
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/inventory_manager/inventory.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components.css">

<div class="content-area">
    <!-- Page Header -->
    <?php
    $pageHeaderConfig = [
        'title'       => 'Inventory Management',
        'description' => 'Manage your solar equipment inventory',
    ];
    $config = $pageHeaderConfig;
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <!-- Filter Bar -->
    <div class="card shadow-lg rounded-xl mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-4 align-center">
                <input type="text" id="searchInput" placeholder="Search by name or category..." class="form-control" style="max-width: 300px;">
                <select id="nameFilter" class="form-control" style="max-width: 180px;">
                    <option value="all">All Item Names</option>
                </select>
                <select id="categoryFilter" class="form-control" style="max-width: 180px;">
                    <option value="all">All Categories</option>
                </select>
                <select id="stockFilter" class="form-control" style="max-width: 150px;">
                    <option value="all">All Stock</option>
                    <option value="in-stock">In Stock</option>
                    <option value="low-stock">Low Stock</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Inventory Table Header -->
    <div class="d-flex justify-between align-center mb-4">
        <h3 class="text-xl font-semibold">Inventory Items</h3>
        <div class="d-flex gap-2">
            <button class="btn btn-secondary rounded-lg" onclick="showCategoryModal()">
                <i class="fas fa-tags mr-2"></i>Add Category
            </button>
            <button class="btn btn-primary rounded-lg" onclick="showAddModal()">
                <i class="fas fa-plus mr-2"></i>Add New Item
            </button>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="card shadow-lg rounded-xl">
        <div class="card-body" style="overflow-x:auto;">
            <table class="table w-full" id="inventoryTable">
                <thead>
                    <tr>
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
            <p id="emptyMsg" class="text-center text-secondary py-6" style="display:none;">No inventory items found.</p>
        </div>
    </div>
</div>

<!-- ===== Add Item Modal ===== -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <form id="addForm" method="POST" enctype="multipart/form-data"
              action="<?php echo URLROOT; ?>/inventorymanager/inventory/add_item">
            <h3 class="text-2xl font-semibold mb-4">Add New Item</h3>

            <div class="card-body">
                <!-- Item Name -->
                <?php
                $inputConfig = [
                    'id'           => 'item_name',
                    'name'         => 'item_name',
                    'label'        => 'Item Name',
                    'type'         => 'text',
                    'icon'         => 'fas fa-box',
                    'value'        => '',
                    'required'     => true,
                    'wrapperClass' => 'mb-4'
                ];
                include __DIR__ . '/../../inc/components/input_field.php';
                ?>

                <!-- Category -->
                <div class="form-group mb-4">
                    <label class="block text-sm font-semibold mb-2" for="add_category_id">
                        <i class="fas fa-tags mr-2"></i>Category <span class="text-error">*</span>
                    </label>
                    <div class="d-flex gap-2">
                        <select id="add_category_id" name="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo (int)$cat->id; ?>">
                                    <?php echo htmlspecialchars($cat->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" class="btn btn-secondary btn-sm"
                                onclick="showCategoryModal()" title="Add New Category">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <!-- Description -->
                <?php
                $inputConfig = [
                    'id'           => 'description',
                    'name'         => 'description',
                    'label'        => 'Description',
                    'type'         => 'text',
                    'icon'         => 'fas fa-align-left',
                    'value'        => '',
                    'required'     => false,
                    'wrapperClass' => 'mb-4'
                ];
                include __DIR__ . '/../../inc/components/input_field.php';
                ?>

                <!-- Quantity -->
                <?php
                $inputConfig = [
                    'id'           => 'quantity',
                    'name'         => 'quantity',
                    'label'        => 'Quantity',
                    'type'         => 'number',
                    'icon'         => 'fas fa-layer-group',
                    'value'        => '',
                    'required'     => true,
                    'wrapperClass' => 'mb-4'
                ];
                include __DIR__ . '/../../inc/components/input_field.php';
                ?>

                <!-- Unit Price -->
                <?php
                $inputConfig = [
                    'id'           => 'unit_price',
                    'name'         => 'unit_price',
                    'label'        => 'Unit Price (Rs.)',
                    'type'         => 'number',
                    'icon'         => 'fas fa-money-bill-wave',
                    'step'         => '0.01',
                    'value'        => '',
                    'required'     => true,
                    'wrapperClass' => 'mb-4'
                ];
                include __DIR__ . '/../../inc/components/input_field.php';
                ?>

                <!-- Buying Price -->
                <?php
                $inputConfig = [
                    'id'           => 'buying_price',
                    'name'         => 'buying_price',
                    'label'        => 'Buying Price (Rs.)',
                    'type'         => 'number',
                    'icon'         => 'fas fa-tag',
                    'step'         => '0.01',
                    'value'        => '',
                    'required'     => false,
                    'wrapperClass' => 'mb-4'
                ];
                include __DIR__ . '/../../inc/components/input_field.php';
                ?>

                <!-- Image Upload -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2">
                        <i class="fas fa-image mr-2"></i>Item Photo
                    </label>
                    <div class="image-upload-row" id="addImageUploadRow">
                        <div class="image-upload-box" id="addImageBox"
                             style="width:80px;height:80px;flex-shrink:0;overflow:hidden;position:relative;"
                             onclick="document.getElementById('itemPhoto').click()">
                            <img id="addImagePreview" src="" alt="Preview"
                                 style="display:none;width:100%;height:100%;object-fit:cover;position:absolute;top:0;left:0;">
                            <span class="remove-image-btn" id="addRemoveBtn"
                                  onclick="event.stopPropagation(); removeAddImage();" style="display:none;">
                                <i class="fas fa-times"></i>
                            </span>
                        </div>
                        <div class="image-upload-text">
                            <p>Drag image here</p>
                            <span>or</span>
                            <a href="#" class="browse-link"
                               onclick="event.preventDefault(); document.getElementById('itemPhoto').click();">
                               Browse image
                            </a>
                        </div>
                    </div>
                    <input type="file" id="itemPhoto" name="itemPhoto" accept="image/*"
                           class="hidden-file-input" onchange="previewAddImage(this)">
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

<!-- ===== Edit Item Modal ===== -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <form id="editForm" method="POST" enctype="multipart/form-data"
              action="<?php echo URLROOT; ?>/inventorymanager/update_item">
            <h3 class="text-2xl font-semibold mb-4">Edit Item</h3>

            <div class="card-body">
                <input type="hidden" name="inventory_id" id="editInventoryId">

                <!-- Item Name -->
                <?php
                $inputConfig = [
                    'id'           => 'editName',
                    'name'         => 'item_name',
                    'label'        => 'Item Name',
                    'type'         => 'text',
                    'icon'         => 'fas fa-box',
                    'value'        => '',
                    'required'     => true,
                    'wrapperClass' => 'mb-4'
                ];
                include __DIR__ . '/../../inc/components/input_field.php';
                ?>

                <!-- Category -->
                <div class="form-group mb-4">
                    <label class="block text-sm font-semibold mb-2" for="edit_category_id">
                        <i class="fas fa-tags mr-2"></i>Category <span class="text-error">*</span>
                    </label>
                    <select id="edit_category_id" name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo (int)$cat->id; ?>">
                                <?php echo htmlspecialchars($cat->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Quantity -->
                <?php
                $inputConfig = [
                    'id'           => 'editQty',
                    'name'         => 'quantity',
                    'label'        => 'Quantity',
                    'type'         => 'number',
                    'icon'         => 'fas fa-layer-group',
                    'value'        => '',
                    'required'     => true,
                    'wrapperClass' => 'mb-4'
                ];
                include __DIR__ . '/../../inc/components/input_field.php';
                ?>

                <!-- Unit Price -->
                <?php
                $inputConfig = [
                    'id'           => 'editPrice',
                    'name'         => 'unit_price',
                    'label'        => 'Unit Price (Rs.)',
                    'type'         => 'number',
                    'icon'         => 'fas fa-money-bill-wave',
                    'step'         => '0.01',
                    'value'        => '',
                    'required'     => true,
                    'wrapperClass' => 'mb-4'
                ];
                include __DIR__ . '/../../inc/components/input_field.php';
                ?>

                <!-- Buying Price -->
                <?php
                $inputConfig = [
                    'id'           => 'editBuyingPrice',
                    'name'         => 'buying_price',
                    'label'        => 'Buying Price (Rs.)',
                    'type'         => 'number',
                    'icon'         => 'fas fa-tag',
                    'step'         => '0.01',
                    'value'        => '',
                    'required'     => false,
                    'wrapperClass' => 'mb-4'
                ];
                include __DIR__ . '/../../inc/components/input_field.php';
                ?>
            </div>

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

<!-- Delete item hidden form -->
<form id="deleteForm" method="POST" action="<?php echo URLROOT; ?>/inventorymanager/delete_item">
    <input type="hidden" name="inventory_id" id="deleteInventoryId">
</form>

<!-- ===== Manage Categories Modal ===== -->
<div id="categoryModal" class="modal">
    <div class="modal-content" style="max-width:480px;">
        <h3 class="text-2xl font-semibold mb-4"><i class="fas fa-tags mr-2"></i>Manage Categories</h3>

        <!-- Existing Categories List -->
        <div class="mb-5">
            <h5 class="text-sm font-semibold mb-2" style="color:var(--color-text-secondary);">Existing Categories</h5>
            <?php if (empty($categories)): ?>
                <p class="text-secondary text-sm">No categories yet.</p>
            <?php else: ?>
                <ul style="list-style:none; padding:0; margin:0; max-height:220px; overflow-y:auto;">
                    <?php foreach ($categories as $cat): ?>
                        <li style="display:flex; align-items:center; justify-content:space-between;
                                   padding:0.5rem 0.75rem; border-bottom:1px solid var(--color-border,#e5e7eb);">
                            <span class="text-sm font-semibold">
                                <?php echo htmlspecialchars($cat->name); ?>
                                <small class="text-secondary ml-2">#<?php echo (int)$cat->id; ?></small>
                            </span>
                            <button type="button"
                                    class="btn btn-sm rounded-lg"
                                    style="background:var(--color-error,#dc2626);color:#fff;padding:0.25rem 0.6rem;"
                                    onclick="confirmDeleteCategory(<?php echo (int)$cat->id; ?>, '<?php echo htmlspecialchars(addslashes($cat->name)); ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <!-- Add New Category Form -->
        <h5 class="text-sm font-semibold mb-2" style="color:var(--color-text-secondary);">Add New Category</h5>
        <form id="addCategoryForm" method="POST"
              action="<?php echo URLROOT; ?>/inventorymanager/inventory/add_category">
            <div class="d-flex gap-2 mb-4">
                <input type="text" id="newCategoryName" name="category_name"
                       class="form-control" placeholder="Enter category name" required
                       style="flex:1;">
                <button type="submit" class="btn btn-primary btn-sm rounded-lg" style="white-space:nowrap;">
                    <i class="fas fa-plus mr-1"></i>Add
                </button>
            </div>
        </form>

        <div class="modal-buttons" style="justify-content:flex-end;">
            <button type="button" class="btn btn-secondary btn-sm rounded-lg" onclick="closeCategoryModal()">
                <i class="fas fa-times mr-2"></i>Close
            </button>
        </div>
    </div>
</div>

<!-- Hidden delete-category form -->
<form id="deleteCategoryForm" method="POST"
      action="<?php echo URLROOT; ?>/inventorymanager/delete_category">
    <input type="hidden" name="category_id" id="deleteCategoryId">
</form>

<script>
// ---------- Data from PHP ----------
let items = <?php echo json_encode(array_map(function($i) {
    return [
        'id'            => (string)($i->inventory_id ?? $i['inventory_id'] ?? ''),
        'name'          => $i->item_name        ?? $i['item_name']        ?? '',
        'category_name' => $i->category_name    ?? $i['category_name']    ?? '',
        'category_id'   => (int)($i->category_id ?? $i['category_id']    ?? 0),
        'qty'           => (int)($i->quantity    ?? $i['quantity']        ?? 0),
        'price'         => (float)($i->unit_price ?? $i['unit_price']     ?? 0),
        'buying_price'  => (float)($i->buying_price ?? $i['buying_price'] ?? 0),
    ];
}, $items), JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE); ?>;

// ---------- Render Table ----------
function renderTable(data) {
    const tbody  = document.getElementById('tableBody');
    const empty  = document.getElementById('emptyMsg');
    tbody.innerHTML = '';

    if (!data.length) {
        empty.style.display = 'block';
        return;
    }
    empty.style.display = 'none';

    data.forEach(item => {
        const total       = item.qty * item.price;
        const isLow       = item.qty <= 5;
        const statusBadge = isLow
            ? '<span class="badge bg-warning text-surface px-3 py-1 rounded-full text-xs"><i class="fas fa-exclamation-circle mr-1"></i>Low Stock</span>'
            : '<span class="badge bg-success text-surface px-3 py-1 rounded-full text-xs"><i class="fas fa-check-circle mr-1"></i>In Stock</span>';

        tbody.innerHTML += `
            <tr>
                <td class="text-sm font-semibold">${item.id}</td>
                <td class="text-sm">${item.name}</td>
                <td class="text-sm">${item.category_name || '—'}</td>
                <td class="text-sm">${item.qty}</td>
                <td class="text-sm">${item.price.toLocaleString(undefined,{minimumFractionDigits:2})}</td>
                <td class="text-sm">${total.toLocaleString(undefined,{minimumFractionDigits:2})}</td>
                <td class="text-sm">${statusBadge}</td>
                <td class="text-sm">
                    <a href="<?php echo URLROOT; ?>/inventorymanager/item/${item.id}"
                       class="btn btn-sm btn-info rounded-lg mr-1">
                        <i class="fas fa-eye mr-1"></i>View
                    </a>
                    <button class="btn btn-primary btn-sm rounded-lg mr-1" onclick="editItem('${item.id}')">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                    <button class="btn btn-sm rounded-lg bg-error" onclick="deleteItem('${item.id}')">
                        <i class="fas fa-trash mr-1"></i>Delete
                    </button>
                </td>
            </tr>`;
    });
}

// ---------- Populate Filters ----------
function populateFilters() {
    const nameFilter     = document.getElementById('nameFilter');
    const categoryFilter = document.getElementById('categoryFilter');
    const names      = [...new Set(items.map(i => i.name))];
    const categories = [...new Set(items.map(i => i.category_name).filter(c => c))];

    names.forEach(n => {
        if (!Array.from(nameFilter.options).some(o => o.value === n))
            nameFilter.add(new Option(n, n));
    });
    categories.forEach(c => {
        if (!Array.from(categoryFilter.options).some(o => o.value === c))
            categoryFilter.add(new Option(c, c));
    });
}

// ---------- Apply Filters ----------
function applyFilters() {
    const searchTerm    = document.getElementById('searchInput').value.toLowerCase();
    const nameValue     = document.getElementById('nameFilter').value;
    const categoryValue = document.getElementById('categoryFilter').value;
    const stockValue    = document.getElementById('stockFilter').value;

    const filtered = items.filter(i => {
        let keep = true;
        if (nameValue     !== 'all') keep = keep && i.name === nameValue;
        if (categoryValue !== 'all') keep = keep && i.category_name === categoryValue;
        if (stockValue === 'in-stock')  keep = keep && i.qty > 5;
        if (stockValue === 'low-stock') keep = keep && i.qty <= 5;
        if (searchTerm) keep = keep && (
            i.name.toLowerCase().includes(searchTerm) ||
            i.category_name.toLowerCase().includes(searchTerm)
        );
        return keep;
    });
    renderTable(filtered);
}

['searchInput','nameFilter','categoryFilter','stockFilter'].forEach(id => {
    const el = document.getElementById(id);
    el.addEventListener('input',  applyFilters);
    el.addEventListener('change', applyFilters);
});

// ---------- Modals ----------
const addModal      = document.getElementById('addModal');
const editModal     = document.getElementById('editModal');
const categoryModal = document.getElementById('categoryModal');

function showAddModal()       { addModal.classList.add('show'); }
function closeAddModal()      { addModal.classList.remove('show'); }
function showCategoryModal()  { document.getElementById('newCategoryName').value = ''; categoryModal.classList.add('show'); }
function closeCategoryModal() { categoryModal.classList.remove('show'); }

function confirmDeleteCategory(id, name) {
    if (confirm('Delete category "' + name + '"?\n\nItems using this category will have their category cleared.')) {
        document.getElementById('deleteCategoryId').value = id;
        document.getElementById('deleteCategoryForm').submit();
    }
}

function closeEditModal() { editModal.classList.remove('show'); }

function editItem(id) {
    const item = items.find(i => i.id == id);
    if (!item) return;

    document.getElementById('editInventoryId').value = item.id;
    document.getElementById('editName').value         = item.name;
    document.getElementById('edit_category_id').value = item.category_id;
    document.getElementById('editQty').value          = item.qty;
    document.getElementById('editPrice').value        = item.price;
    document.getElementById('editBuyingPrice').value  = item.buying_price;

    editModal.classList.add('show');
}

function deleteItem(id) {
    const item = items.find(i => i.id == id);
    const name = item ? item.name : 'this item';
    if (confirm('Delete "' + name + '"? This action cannot be undone.')) {
        document.getElementById('deleteInventoryId').value = id;
        document.getElementById('deleteForm').submit();
    }
}

window.addEventListener('click', e => {
    if (e.target === addModal)      closeAddModal();
    if (e.target === editModal)     closeEditModal();
    if (e.target === categoryModal) closeCategoryModal();
});

// ---------- Image Upload ----------
function previewAddImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview   = document.getElementById('addImagePreview');
            const removeBtn = document.getElementById('addRemoveBtn');
            preview.src          = e.target.result;
            preview.style.display = 'block';
            removeBtn.style.display = 'flex';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function removeAddImage() {
    document.getElementById('itemPhoto').value = '';
    document.getElementById('addImagePreview').src = '';
    document.getElementById('addImagePreview').style.display = 'none';
    document.getElementById('addRemoveBtn').style.display = 'none';
}

const addImageBox = document.getElementById('addImageBox');
if (addImageBox) {
    ['dragenter','dragover','dragleave','drop'].forEach(evt =>
        addImageBox.addEventListener(evt, e => { e.preventDefault(); e.stopPropagation(); }));
    ['dragenter','dragover'].forEach(evt =>
        addImageBox.addEventListener(evt, () => addImageBox.classList.add('dragover')));
    ['dragleave','drop'].forEach(evt =>
        addImageBox.addEventListener(evt, () => addImageBox.classList.remove('dragover')));
    addImageBox.addEventListener('drop', e => {
        const files = e.dataTransfer.files;
        if (files.length && files[0].type.startsWith('image/')) {
            document.getElementById('itemPhoto').files = files;
            previewAddImage(document.getElementById('itemPhoto'));
        }
    });
}

// ---------- Init ----------
populateFilters();
renderTable(items);
</script>
