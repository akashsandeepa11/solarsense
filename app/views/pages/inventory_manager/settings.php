<?php
/**
 * Inventory Manager Settings Page
 * Configure inventory preferences and system settings
 */
?>

<div class="content-area">
    <!-- Page Header -->
    <?php
    $config = [
        'title' => 'Settings',
        'description' => 'Configure your inventory management preferences',
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <div class="row">
        <!-- General Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm rounded-xl">
                <div class="card-header py-4 px-4 border-bottom">
                    <h3 class="text-lg font-semibold">
                        <i class="fas fa-cog mr-2 text-primary"></i>General Settings
                    </h3>
                </div>
                <div class="card-body p-4">
                    <form id="generalSettingsForm" method="POST" action="<?php echo URLROOT; ?>/inventorymanager/update_settings">
                        <div class="form-group mb-4">
                            <label class="form-label">Low Stock Alert Threshold</label>
                            <input type="number" name="low_stock_threshold" class="form-control" value="10" min="1">
                            <small class="text-secondary">Alert when item quantity falls below this number</small>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Default Currency</label>
                            <select name="currency" class="form-control">
                                <option value="LKR" selected>LKR - Sri Lankan Rupee</option>
                                <option value="USD">USD - US Dollar</option>
                                <option value="EUR">EUR - Euro</option>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Date Format</label>
                            <select name="date_format" class="form-control">
                                <option value="Y-m-d" selected>2025-02-26 (YYYY-MM-DD)</option>
                                <option value="d/m/Y">26/02/2025 (DD/MM/YYYY)</option>
                                <option value="m/d/Y">02/26/2025 (MM/DD/YYYY)</option>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="d-flex align-center gap-2">
                                <input type="checkbox" name="auto_reorder" value="1" class="form-checkbox">
                                <span>Enable Auto-Reorder Suggestions</span>
                            </label>
                            <small class="text-secondary d-block mt-1">Get automatic suggestions when stock is low</small>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm rounded-xl">
                <div class="card-header py-4 px-4 border-bottom">
                    <h3 class="text-lg font-semibold">
                        <i class="fas fa-bell mr-2 text-accent"></i>Notification Settings
                    </h3>
                </div>
                <div class="card-body p-4">
                    <form id="notificationSettingsForm" method="POST" action="<?php echo URLROOT; ?>/inventorymanager/update_notifications">
                        <div class="form-group mb-4">
                            <label class="d-flex align-center gap-2">
                                <input type="checkbox" name="email_low_stock" value="1" checked class="form-checkbox">
                                <span>Email alerts for low stock</span>
                            </label>
                        </div>

                        <div class="form-group mb-4">
                            <label class="d-flex align-center gap-2">
                                <input type="checkbox" name="email_new_order" value="1" checked class="form-checkbox">
                                <span>Email alerts for new purchase orders</span>
                            </label>
                        </div>

                        <div class="form-group mb-4">
                            <label class="d-flex align-center gap-2">
                                <input type="checkbox" name="email_delivery" value="1" checked class="form-checkbox">
                                <span>Email alerts for deliveries</span>
                            </label>
                        </div>

                        <div class="form-group mb-4">
                            <label class="d-flex align-center gap-2">
                                <input type="checkbox" name="push_notifications" value="1" class="form-checkbox">
                                <span>Enable push notifications</span>
                            </label>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Notification Email</label>
                            <input type="email" name="notification_email" class="form-control" placeholder="email@example.com">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Save Preferences
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Category Management -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm rounded-xl">
                <div class="card-header py-4 px-4 border-bottom d-flex justify-between align-center">
                    <h3 class="text-lg font-semibold">
                        <i class="fas fa-tags mr-2 text-success"></i>Item Categories
                    </h3>
                    <button class="btn btn-sm btn-primary" onclick="showAddCategoryModal()">
                        <i class="fas fa-plus mr-1"></i>Add
                    </button>
                </div>
                <div class="card-body p-4">
                    <div class="category-list">
                        <div class="d-flex justify-between align-center py-3 border-bottom">
                            <span>Solar Panels</span>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-error"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="d-flex justify-between align-center py-3 border-bottom">
                            <span>Inverters</span>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-error"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="d-flex justify-between align-center py-3 border-bottom">
                            <span>Batteries</span>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-error"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="d-flex justify-between align-center py-3 border-bottom">
                            <span>Mounting Equipment</span>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-error"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="d-flex justify-between align-center py-3">
                            <span>Electrical Accessories</span>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-error"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Management -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm rounded-xl">
                <div class="card-header py-4 px-4 border-bottom">
                    <h3 class="text-lg font-semibold">
                        <i class="fas fa-database mr-2 text-warning"></i>Data Management
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4 pb-4 border-bottom">
                        <h4 class="font-medium mb-2">Export Data</h4>
                        <p class="text-secondary text-sm mb-3">Download your inventory data in various formats</p>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-success" onclick="exportData('csv')">
                                <i class="fas fa-file-csv mr-1"></i>CSV
                            </button>
                            <button class="btn btn-sm btn-success" onclick="exportData('excel')">
                                <i class="fas fa-file-excel mr-1"></i>Excel
                            </button>
                            <button class="btn btn-sm btn-success" onclick="exportData('pdf')">
                                <i class="fas fa-file-pdf mr-1"></i>PDF
                            </button>
                        </div>
                    </div>

                    <div class="mb-4 pb-4 border-bottom">
                        <h4 class="font-medium mb-2">Import Data</h4>
                        <p class="text-secondary text-sm mb-3">Import inventory data from CSV or Excel files</p>
                        <div class="d-flex gap-2 align-center">
                            <input type="file" id="importFile" class="form-control" accept=".csv,.xlsx,.xls" style="max-width: 250px;">
                            <button class="btn btn-sm btn-primary" onclick="importData()">
                                <i class="fas fa-upload mr-1"></i>Import
                            </button>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-medium mb-2 text-error">Danger Zone</h4>
                        <p class="text-secondary text-sm mb-3">Reset all inventory data (cannot be undone)</p>
                        <button class="btn btn-sm btn-error" onclick="confirmReset()">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Reset All Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showAddCategoryModal() {
    const name = prompt('Enter category name:');
    if (name) {
        console.log('Adding category:', name);
        // Submit to server
    }
}

function exportData(format) {
    console.log('Exporting data as:', format);
    alert('Export as ' + format.toUpperCase() + ' - To be implemented');
}

function importData() {
    const fileInput = document.getElementById('importFile');
    if (fileInput.files.length === 0) {
        alert('Please select a file to import');
        return;
    }
    console.log('Importing file:', fileInput.files[0].name);
    alert('Import functionality - To be implemented');
}

function confirmReset() {
    if (confirm('Are you sure you want to reset all inventory data? This action cannot be undone!')) {
        if (confirm('This is your final warning. All data will be permanently deleted. Continue?')) {
            console.log('Resetting all data...');
            alert('Reset functionality - To be implemented');
        }
    }
}
</script>

<style>
.form-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
}
</style>
