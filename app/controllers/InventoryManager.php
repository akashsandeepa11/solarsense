<?php

    class InventoryManager extends Controller{

        private $user = [
            'role' => ROLE_INVENTORY_MANAGER,
        ];

        private $inventoryModel;

        public function __construct(){
                    // Load the model
                $this->inventoryModel = $this->model('M_inventory');

        }

        // --- Admin-Specific Pages ---

       #  // --- Inventory Page ---
    public function inventory(){


        // ✅ Check if form was submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
            $data = [
                'company_id' => 1, // or $_SESSION['company_id'] if dynamic
                'item_name'  => trim($_POST['itemName']),
                'category'   => trim($_POST['itemCategory']),
                'quantity'   => (int) $_POST['itemQty'],
                'unit_price' => (float) $_POST['itemPrice']
            ];

            // Save to DB
            if ($this->inventoryModel->add_item($data)) {
                setToast('Item Added Successfully', 'success');
                redirect('inventorymanager/inventory');
                exit;
               
                
            } else {
                
                   setToast( 'Failed to add item. Please try again.', 'failure');

            }
        }

        // --- Fetch items from DB ---

        $items = $this->inventoryModel->get_all_items();
        
        $data = [
            'user'  => $this->user,
            'items' => $items?? []
        ];

        $this->view('pages/inventory_manager/inventory', $data, layout: 'dashboard');





    }

    public function update_item() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

       

        $data = [
            'inventory_id' => (int)($_POST['inventory_id'] ?? 0),
            'company_id'   => 1,
            'item_name'    => trim($_POST['itemName']),
            'category'     => trim($_POST['itemCategory']),
            'quantity'     => (int)$_POST['itemQty'],
            'unit_price'   => (float)$_POST['itemPrice']
        ];

       

        $success = $this->inventoryModel->update_item($data);

        if($success){
            setToast('Item Updated Successfully','success');
        } else {
            setToast('Failed to update item.','failure');
        }

        redirect('inventorymanager/inventory'); // <-- reload the page
        exit;
    }
    }


    public function delete_item() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {

      
        $inventory_id = (int)($_POST['inventory_id'] ?? 0);
        $success = $this->inventoryModel->delete_item($inventory_id);

        if($success){
            setToast('Item deleted successfully', 'success');
        } else {
            setToast('Failed to delete item', 'failure');
        }

        redirect('inventorymanager/inventory');
        exit;
    }
    }



        public function suppliers(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/inventory_manager/suppliers', $data, layout: 'dashboard');
        }

        public function profile(){
            $data = [
                'user' => $this->user,
            ];
            
            $this->view('pages/inventory_manager/profile', $data, layout: 'dashboard');
        }
        
        
    }

?>
