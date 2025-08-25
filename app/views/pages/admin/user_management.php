
 <?php
// --- Dummy Data ---
$users = [
    ['userID' => '671','name' => 'Akash Sandeepa', 'email' => 'akash@email.com', 'role' => 'Homeowner', 'registered_date' => '2025-08-20', 'status' => 'Active', 'avatar' => 'AS'],
    ['userID' => '12','name' => 'Eco Power Solutions', 'email' => 'contact@ecopower.lk', 'role' => 'Installer', 'registered_date' => '2025-08-18', 'status' => 'Active', 'avatar' => 'EP'],
    ['userID' => '333','name' => 'Nimali Silva', 'email' => 'nimali@email.com', 'role' => 'Homeowner', 'registered_date' => '2025-08-15', 'status' => 'Deactivated', 'avatar' => 'NS'],
    ['userID' => '54','name' => 'Anura Kumara', 'email' => 'anura@ecopower.lk', 'role' => 'ServiceAgent', 'registered_date' => '2025-08-18', 'status' => 'Active', 'avatar' => 'AK'],
    ['userID' => '9955','name' => 'CEB Colombo', 'email' => 'ceb.colombo@email.com', 'role' => 'CEBAgent', 'registered_date' => '2025-08-12', 'status' => 'Active', 'avatar' => 'CEB'],
];

function getStatusClass($status) {
    return $status === 'Active' ? 'bg-success' : 'bg-error';
}
?>

<!-- Link to custom CSS for this page -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/pages/admin/user_management.css">

<div class="content-area">
    <!-- Header Section -->
    <div class="d-flex justify-between align-center mb-6">
        <div>
            <h1 class="text-4xl font-bold">User Management</h1>
            <p class="text-secondary">Add, view, and manage all users on the platform.</p>
        </div>
        <div>
            <a href="#" id="add-user-btn" class="btn btn-primary btn-lg rounded-lg text-decoration-none">
                <i class="fas fa-plus mr-2"></i> Add User
            </a>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card shadow-lg rounded-xl">
        <div class="card-body">
            <!-- Tabs and Search -->
            <div class="d-flex justify-between align-center mb-4">
                <div class="tabs-container">
                    <a href="#" class="tab active" data-role="All">All Users</a>
                    <a href="#" class="tab" data-role="Homeowner">Homeowners</a>
                    <a href="#" class="tab" data-role="Installer">Installers</a>
                    <a href="#" class="tab" data-role="ServiceAgent">Service Agents</a>
                    <a href="#" class="tab" data-role="CEBAgent">CEB Agents</a>
                </div>
                <div class="input input--has-icon" style="min-width: 300px;">
                    <i class="input__icon fas fa-search"></i>
                    <input type="text" id="search-bar" class="input__field" placeholder="Search by name or email...">
                </div>
            </div>

            <!-- User Table -->
            <div class="table-responsive" >

                <table class="user-table">

                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Date Registered</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="user-table-body">
                        <?php foreach($users as $user): ?>

                        <tr data-role="<?php echo $user['role']; ?>">
                            <td>
                                <div class="d-flex align-center" style="background-color: green;">
                                    <div class="avatar"><?php echo $user['avatar']; ?></div>
                                    <div>
                                        <div class="font-semibold"><?php echo $user['name']; ?></div>
                                        <div class="text-secondary text-sm"><?php echo $user['email']; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo $user['role']; ?></td>
                            <td><?php echo $user['registered_date']; ?></td>
                            <td>
                                <span class="status-badge <?php echo getStatusClass($user['status']); ?>">
                                    <?php echo $user['status']; ?>
                                </span>
                            </td>
                            <td>
                                 <a href="#" class="btn btn-sm btn-primary rounded-lg mr-2" id="view_btn">View</a>
                                <a href="#" class="btn btn-sm btn-secondary rounded-lg mr-2" id="edit_btn">Edit</a>
                                <a href="#" class="btn btn-sm btn-error rounded-lg deactivate_btn" data-userid="<?php echo $user['userID'];?>">Deactivate</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>
</div>

<!-- Add/Edit User Modal -->
<div id="user-modal" class="modal-overlay" style="display: none;">
    <div class="modal-content card shadow-2xl rounded-xl">
        <div class="card-body">
            <div class="d-flex justify-between align-center mb-4">
                <h2 id="modal-title" class="text-2xl font-bold">Add New User</h2>
                <button id="close-modal-btn" class="text-secondary text-xl">&times;</button>
            </div>
            <form id="user-form">
                <div class="form-group">
                    <label for="user-role" class="form-label">User Role <span class="text-error">*</span></label>
                    <select id="user-role" name="role" class="form-control" required>
                        <option value="Installer">Installer</option>
                        <option value="CEBAgent">CEB Agent</option>
                    </select>
                </div>
                <div class="form-group">
                    <?php $inputConfig = ['id' => 'userName', 'name' => 'name', 'label' => 'Full Name / Company Name', 'type' => 'text', 'icon' => 'fas fa-user', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                </div>
                <div class="form-group">
                    <?php $inputConfig = ['id' => 'userEmail', 'name' => 'email', 'label' => 'Email Address', 'type' => 'email', 'icon' => 'fas fa-envelope', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                </div>
                <div class="d-flex justify-between mt-6">
                    <button type="button" id="cancel-modal-btn" class="btn btn-secondary mr-2">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Account & Send Credentials</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- deactivate Modal -->
<div id="deactivate_modal" class="modal-overlay" style="display: none;">
    <div class="modal-content card shadow-2xl rounded-xl">
       <div class="body p-6">
                
            <div class="d-flex justify-between align-center mb-4">
                <h2 id="modal-title" class="text-2xl font-bold">Please confirm</h2>
                <button id="deactivate-close-modal-btn" class="text-secondary text-xl">&times;</button>
            </div>

            <div class="flex justify-center items-center h-32 text-center ">
                <p class="text-lg">Do you want to deactivate this user account?</p>
            </div>
            
            
            <form id="deactivate-form" action="database.php" method="POST">
                <div class="d-flex justify-between mt-6">
                    <input type="hidden" id="deactivate-user-id" name="userID" value="">
                    <button type="button" id="deactivate-cancel-modal-btn" class="btn btn-secondary mr-2">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                 </div>
            </form>



       </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // add user Modal Logic
    const addUserBtn = document.getElementById('add-user-btn');
    const userModal = document.getElementById('user-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const cancelModalBtn = document.getElementById('cancel-modal-btn');

    addUserBtn.addEventListener('click', () => userModal.style.display = 'flex');
    closeModalBtn.addEventListener('click', () => userModal.style.display = 'none');
    cancelModalBtn.addEventListener('click', () => userModal.style.display = 'none');
    window.addEventListener('click', (e) => {
        if (e.target === userModal) {
            userModal.style.display = 'none';
        }
    });

    //deactivate user Modal Logic
    const deActivateBtn=document.querySelectorAll('.deactivate_btn');
    const deActivateModal = document.getElementById('deactivate_modal');
    const dCloseModalBtn=document.getElementById('deactivate-close-modal-btn');
    const dCancelModalBtn=document.getElementById('deactivate-cancel-modal-btn');
    const deactivateUserIdInput = document.getElementById('deactivate-user-id');


    deActivateBtn.forEach(btn => {

            btn.addEventListener('click',(e)=>{
                
                e.preventDefault();
                const userID=btn.dataset.userid;
                deActivateModal.style.display='flex'
               console.log("Deactivating user:", userID);
                deactivateUserIdInput.value=userID;

            }
        );
    });

    dCancelModalBtn.addEventListener('click',()=>deActivateModal.style.display='none');
    dCloseModalBtn.addEventListener('click',()=>deActivateModal.style.display='none');
    window.addEventListener('click',(e)=>{
        if(e.target===deActivateModal){
            deActivateModal.style.display='none';
        }

    });


    // Tab Filtering Logic
    const tabs = document.querySelectorAll('.tab');
    const tableRows = document.querySelectorAll('#user-table-body tr');

    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const selectedRole = this.dataset.role;

            tableRows.forEach(row => {
                if (selectedRole === 'All' || row.dataset.role === selectedRole) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // Search Bar Logic
    const searchBar = document.getElementById('search-bar');
    searchBar.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            if (rowText.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});



</script>