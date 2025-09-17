<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/operation_manager/quotation.css">

<div class="quotation-container">
    <!-- Header Section -->
    <div class="page-header">
        <h1>Quotation Management</h1>
        <button class="btn btn-primary" onclick="openNewQuotationForm()">
            <i class="fas fa-plus"></i> Create New Quotation
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon pending">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3>Pending Quotations</h3>
                <p class="stat-number">12</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon approved">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3>Approved Quotations</h3>
                <p class="stat-number">45</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon rejected">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-info">
                <h3>Rejected Quotations</h3>
                <p class="stat-number">8</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div class="stat-info">
                <h3>Total Value</h3>
                <p class="stat-number">$125,450</p>
            </div>
        </div>
    </div>

    <!-- Quotation List Section -->
    <div class="quotation-list-section">
        <div class="section-header">
            <h2>Recent Quotations</h2>
            <div class="filter-controls">
                <select id="statusFilter" onchange="filterQuotations()">
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
                <div class="search-box">
                    <input type="text" id="searchQuotation" placeholder="Search quotations...">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>

        <div class="quotation-table-container">
            <table class="quotation-table">
                <thead>
                    <tr>
                        <th>Quotation ID</th>
                        <th>Customer Name</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>QT2025091701</td>
                        <td>John Smith</td>
                        <td>2025-09-17</td>
                        <td>$5,450.00</td>
                        <td><span class="status-badge pending">Pending</span></td>
                        <td>
                            <button class="action-btn view" onclick="viewQuotation('QT2025091701')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn edit" onclick="editQuotation('QT2025091701')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn delete" onclick="deleteQuotation('QT2025091701')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <button class="pagination-btn" disabled><i class="fas fa-chevron-left"></i></button>
            <button class="pagination-btn active">1</button>
            <button class="pagination-btn">2</button>
            <button class="pagination-btn">3</button>
            <button class="pagination-btn"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</div>

<!-- New Quotation Modal -->
<div id="quotationModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Create New Quotation</h2>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="quotationForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="customerName">Customer Name</label>
                        <input type="text" id="customerName" name="customerName" required>
                    </div>
                    <div class="form-group">
                        <label for="customerEmail">Customer Email</label>
                        <input type="email" id="customerEmail" name="customerEmail" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="quotationDate">Quotation Date</label>
                        <input type="date" id="quotationDate" name="quotationDate" required>
                    </div>
                    <div class="form-group">
                        <label for="validUntil">Valid Until</label>
                        <input type="date" id="validUntil" name="validUntil" required>
                    </div>
                </div>

                <div class="items-section">
                    <h3>Items</h3>
                    <div id="itemsList">
                        <div class="item-row">
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" name="items[0][description]" required>
                            </div>
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" name="items[0][quantity]" required>
                            </div>
                            <div class="form-group">
                                <label>Unit Price</label>
                                <input type="number" name="items[0][price]" step="0.01" required>
                            </div>
                            <button type="button" class="remove-item" onclick="removeItem(this)">×</button>
                        </div>
                    </div>
                    <button type="button" class="add-item-btn" onclick="addItem()">
                        <i class="fas fa-plus"></i> Add Item
                    </button>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Quotation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Modal functions
    function openNewQuotationForm() {
        document.getElementById('quotationModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('quotationModal').style.display = 'none';
    }

    // Items management
    function addItem() {
        const itemsList = document.getElementById('itemsList');
        const itemCount = itemsList.children.length;
        
        const newItem = document.createElement('div');
        newItem.className = 'item-row';
        newItem.innerHTML = `
            <div class="form-group">
                <label>Description</label>
                <input type="text" name="items[${itemCount}][description]" required>
            </div>
            <div class="form-group">
                <label>Quantity</label>
                <input type="number" name="items[${itemCount}][quantity]" required>
            </div>
            <div class="form-group">
                <label>Unit Price</label>
                <input type="number" name="items[${itemCount}][price]" step="0.01" required>
            </div>
            <button type="button" class="remove-item" onclick="removeItem(this)">×</button>
        `;
        
        itemsList.appendChild(newItem);
    }

    function removeItem(button) {
        button.parentElement.remove();
    }

    // Form submission
    document.getElementById('quotationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // Add your form submission logic here
        console.log('Form submitted');
        closeModal();
    });

    // Quotation management functions
    function viewQuotation(id) {
        console.log('Viewing quotation:', id);
    }

    function editQuotation(id) {
        console.log('Editing quotation:', id);
    }

    function deleteQuotation(id) {
        if (confirm('Are you sure you want to delete this quotation?')) {
            console.log('Deleting quotation:', id);
        }
    }

    function filterQuotations() {
        const status = document.getElementById('statusFilter').value;
        console.log('Filtering by status:', status);
        // Add your filtering logic here
    }
</script>