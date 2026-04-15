<?php

class InventoryManager extends Controller
{

    private $user = [
        'role' => ROLE_INVENTORY_MANAGER,
    ];

    private $inventoryModel;
    private $company_id;

    public function __construct()
    {
        // Load the model
        $this->inventoryModel = $this->model('M_inventory');

        // Resolve company_id from the logged-in inventory manager's record
        $userId = $_SESSION['user_id'] ?? null;
        $this->company_id = $userId
            ? (int) $this->inventoryModel->get_company_id($userId)
            : null;
    }

    // --- Dashboard Page ---
    public function dashboard()
    {
        $totalItems = $this->inventoryModel->get_total_items_count();
        $lowStockItems = $this->inventoryModel->get_low_stock_items(5);
        $totalStockValue = $this->inventoryModel->get_total_stock_value();
        $categoriesCount = $this->inventoryModel->get_categories_count();
        $stockByCategory = $this->inventoryModel->get_stock_by_category();
        $recentOrders = $this->inventoryModel->get_recent_orders(5);
        $totalOrdersCount = $this->inventoryModel->get_total_orders_count();
        $totalOrdersAmount = $this->inventoryModel->get_total_orders_amount();

        $data = [
            'user' => $this->user,
            'total_items' => $totalItems,
            'low_stock_items' => $lowStockItems,
            'low_stock_count' => count((array) $lowStockItems),
            'total_stock_value' => $totalStockValue,
            'categories_count' => $categoriesCount,
            'stock_by_category' => $stockByCategory,
            'recent_orders' => $recentOrders,
            'total_orders_count' => $totalOrdersCount,
            'total_orders_amount' => $totalOrdersAmount,
        ];

        $this->view('pages/inventory_manager/dashboard', $data, layout: 'dashboard');
    }

    // --- Admin-Specific Pages ---

    #  // --- Inventory Page ---
    public function inventory($page = "index")
    {

        if ($page == "add_category") {
            $this->add_category();
        }

        if ($page == "add_item") {
            $this->add_item();
        }

        // if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        //     if (isset($_FILES['itemPhoto']) && $_FILES['itemPhoto']['error'] == UPLOAD_ERR_OK) {
        //         $fileTmpPath = $_FILES['itemPhoto']['tmp_name'];
        //         $fileName = time() . '_' . $_FILES['itemPhoto']['name'];
        //         $uploadDir = APPROOT . '/../public/img/';

        //         $destPath = $uploadDir . $fileName;

        //         if (!is_dir($uploadDir)) {
        //             mkdir($uploadDir, 0755, true);
        //         }

        //         move_uploaded_file($fileTmpPath, $destPath);
        //         $photoName = $fileName;
        //     } else {
        //         $photoName = null;
        //     }


        //     $data = [
        //         'company_id' => , // or $_SESSION['company_id'] if dynamic
        //         'item_name'  => trim($_POST['itemName']),
        //         'category'   => trim($_POST['itemCategory']),
        //         'quantity'   => (int) $_POST['itemQty'],
        //         'unit_price' => (float) $_POST['itemPrice'],
        //         'itemPhoto' => $photoName
        //     ];

        //     // Save to DB
        //     if ($this->inventoryModel->add_item($data)) {
        //         setToast('Item Added Successfully', 'success');
        //         redirect('inventorymanager/inventory');
        //         exit;


        //     } else {

        //            setToast( 'Failed to add item. Please try again.', 'failure');

        //     }
        // }

        // --- Fetch items from DB ---

        $items = $this->inventoryModel->get_all_items($this->company_id);
        $categories = $this->inventoryModel->get_categories();

        $data = [
            'user' => $this->user,
            'items' => $items ?? [],
            'categories' => $categories ?? []
        ];

        $this->view('pages/inventory_manager/inventory', $data, layout: 'dashboard');

    }

    public function add_category()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = trim($_POST['category_name']);

            if (empty($name)) {
                setToast('Category name cannot be empty', 'failure');
                redirect('inventorymanager/inventory');
                return;
            }

            $success = $this->inventoryModel->add_category($name);

            if ($success) {
                setToast('Category Added Successfully', 'success');
            } else {
                setToast('Failed to add category. Please try again.', 'failure');
            }

            redirect('inventorymanager/inventory');
            exit;
        }
    }

    public function delete_category()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = (int) ($_POST['category_id'] ?? 0);

            if ($id <= 0) {
                setToast('Invalid category.', 'failure');
                redirect('inventorymanager/inventory');
                return;
            }

            $success = $this->inventoryModel->delete_category($id);

            if ($success) {
                setToast('Category Deleted Successfully', 'success');
            } else {
                setToast('Failed to delete category.', 'failure');
            }

            redirect('inventorymanager/inventory');
            exit;
        }
    }

    public function add_item()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $catId = (int) ($_POST['category_id'] ?? 0);
            $data = [
                'company_id' => $this->company_id,
                'item_name' => trim($_POST['item_name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'category_id' => $catId > 0 ? $catId : null,
                'quantity' => (int) ($_POST['quantity'] ?? 0),
                'unit_price' => (float) ($_POST['unit_price'] ?? 0),
                'buying_price' => (float) ($_POST['buying_price'] ?? 0),
                'item_image' => null
            ];

            // Handle image upload
            if (isset($_FILES['itemPhoto']) && $_FILES['itemPhoto']['error'] == 0) {
                $uploadDir = APPROOT . '/../public/img/inventory/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = time() . '_' . basename($_FILES['itemPhoto']['name']);
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['itemPhoto']['tmp_name'], $targetPath)) {
                    $data['item_image'] = $fileName;
                }
            }

            $success = $this->inventoryModel->add_item($data);

            if ($success) {
                setToast('Item Added Successfully', 'success');
            } else {
                // setToast('Failed to add item. Please try again.', 'failure');
            }

            redirect('inventorymanager/inventory');
            exit;
        }
    }

    public function update_item()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'inventory_id' => (int) ($_POST['inventory_id'] ?? 0),
                'company_id' => $this->company_id,
                'item_name' => trim($_POST['item_name'] ?? ''),
                'category_id' => (int) ($_POST['category_id'] ?? 0),
                'quantity' => (int) ($_POST['quantity'] ?? 0),
                'unit_price' => (float) ($_POST['unit_price'] ?? 0),
                'buying_price' => (float) ($_POST['buying_price'] ?? 0),
                'item_image' => null
            ];

            // Handle image upload on edit
            if (isset($_FILES['itemPhoto']) && $_FILES['itemPhoto']['error'] == 0) {
                $uploadDir = APPROOT . '/../public/img/inventory/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = time() . '_' . basename($_FILES['itemPhoto']['name']);
                if (move_uploaded_file($_FILES['itemPhoto']['tmp_name'], $uploadDir . $fileName)) {
                    $data['item_image'] = $fileName;
                }
            }

            $success = $this->inventoryModel->update_item($data);

            if ($success) {
                setToast('Item Updated Successfully', 'success');
            } else {
                setToast('Failed to update item.', 'failure');
            }

            redirect('inventorymanager/inventory');
            exit;
        }
    }


    public function delete_item()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            $inventory_id = (int) ($_POST['inventory_id'] ?? 0);
            $success = $this->inventoryModel->delete_item($inventory_id);

            if ($success) {
                setToast('Item deleted successfully', 'success');
            } else {
                setToast('Failed to delete item', 'failure');
            }

            redirect('inventorymanager/inventory');
            exit;
        }
    }

    // --- View Single Item ---
    public function item($id = null)
    {
        if (!$id) {
            redirect('inventorymanager/inventory');
            return;
        }

        // Fetch item details from DB (or use sample data for now)
        $item = $this->inventoryModel->get_item_by_id($id);

        if (!$item) {
            setToast('Item not found', 'failure');
            redirect('inventorymanager/inventory');
            return;
        }

        $data = [
            'user' => $this->user,
            'item' => $item,
        ];

        $this->view('pages/inventory_manager/item_view', $data, layout: 'dashboard');
    }



    public function suppliers()
    {
        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/inventory_manager/suppliers', $data, layout: 'dashboard');
    }

    // --- Purchases Management Page ---
    public function purchases($statusFilter = 'all')
    {
        $orders = $statusFilter !== 'all'
            ? $this->inventoryModel->get_orders_by_status($statusFilter)
            : $this->inventoryModel->get_all_orders();

        $stats = $this->inventoryModel->get_order_stats();

        $data = [
            'user' => $this->user,
            'orders' => $orders ?? [],
            'stats' => $stats,
            'status_filter' => $statusFilter,
        ];

        $this->view('pages/common/purchases', $data, layout: 'dashboard');
    }

    // --- Create Purchase Order ---
    public function create_purchase()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // TODO: Implement create purchase logic
        }
        redirect('inventorymanager/purchases');
    }

    // --- Settings Page ---
    public function settings()
    {
        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/inventory_manager/settings', $data, layout: 'dashboard');
    }

    // --- Update Settings ---
    public function update_settings()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // TODO: Implement update settings logic
        }
        redirect('inventorymanager/settings');
    }

    // --- Update Notification Preferences ---
    public function update_notifications()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // TODO: Implement update notifications logic
        }
        redirect('inventorymanager/settings');
    }

    public function reports()
    {
        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/inventory_manager/reports', $data, layout: 'dashboard');
    }

    public function profile()
    {
        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/inventory_manager/profile', $data, layout: 'dashboard');
    }

    // --- Notifications ---
    public function notifications()
    {
        $data = [
            'user' => $this->user,
        ];

        $this->view('pages/common/notifications', $data, layout: 'dashboard');
    }

    public function help()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $helpModel = $this->model('M_Help');
            $userId = $_SESSION['user_id'];

            $data = [
                'user_id' => $userId,
                'type' => $this->user['role'],
                'full_name' => $_SESSION['user_name'] ?? 'Inventory Manager',
                'title' => trim($_POST['title']), // Capture title from view
                'description' => trim($_POST['notes']), // Map 'notes' from view to 'description'
            ];

            if ($helpModel->add_complaint($data)) {
                setToast('Support request submitted!', 'success');
                redirect('inventorymanager/help');
            } else {
                setToast('Database error. Please try again.', 'error');
            }
        }

        $data = ['user' => $this->user];
        $this->view('pages/inventory_manager/help', $data, layout: 'dashboard');
    }



}

?>