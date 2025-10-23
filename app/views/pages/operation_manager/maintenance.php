

<?php
$tasks = [
    ["id"=>"T-1001","title"=>"Inverter Fault Detected","customer"=>"John Doe","address"=>"123 Main Street","date"=>"2025-09-01","panelId"=>"P-1001","notes"=>"Inverter shutting down frequently","priority"=>"High","agent"=>"Agent A","status"=>"assigned"],
    ["id"=>"T-1002","title"=>"Install Solar Battery","customer"=>"Sarah Smith","address"=>"45 Green Lane","date"=>"2025-08-30","panelId"=>"P-1002","notes"=>"New battery installation","priority"=>"Medium","agent"=>"","status"=>"unassigned"],
    ["id"=>"T-1003","title"=>"Routine Maintenance","customer"=>"Michael Lee","address"=>"78 Oak Road","date"=>"2025-08-29","panelId"=>"P-1003","notes"=>"Quarterly checkup","priority"=>"Low","agent"=>"Agent B","status"=>"assigned"],
    ["id"=>"T-1004","title"=>"Panel Cleaning","customer"=>"Emily Davis","address"=>"56 Sunny Avenue","date"=>"2025-09-05","panelId"=>"P-1004","notes"=>"Quarterly panel cleaning required","priority"=>"Low","agent"=>"","status"=>"unassigned"],
    ["id"=>"T-1005","title"=>"Wiring Inspection","customer"=>"Robert Wilson","address"=>"89 Power Lane","date"=>"2025-09-08","panelId"=>"P-1005","notes"=>"Annual wiring inspection","priority"=>"High","agent"=>"Agent C","status"=>"assigned"]
];
$agents = ["Agent A", "Agent B", "Agent C"];
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/operation_manager/maintenance.css">

<div class="content-area">
    <!-- Page Header -->
    <?php
    $pageHeaderConfig = [
        'title' => 'Maintenance Task Management',
        'description' => 'Assign and track solar equipment maintenance tasks',
    ];
    $config = $pageHeaderConfig;
    require APPROOT . '/views/inc/components/page_header.php';
    ?>

    <!-- Stats Grid -->
    <div class="stats-grid mb-6">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Total Tasks</p>
                <p class="stat-value" id="totalTasks">0</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Assigned Tasks</p>
                <p class="stat-value" id="assignedTasks">0</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Unassigned Tasks</p>
                <p class="stat-value" id="unassignedTasks">0</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon error">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">High Priority</p>
                <p class="stat-value" id="highPriorityTasks">0</p>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card shadow-lg rounded-xl mb-4">
        <div class="card-body">
            <div class="toolbar">
                <input type="text" id="searchInput" placeholder="Search by title or customer..." class="form-control">
                <select id="priorityFilter" class="form-control">
                    <option value="all">All Priorities</option>
                    <option value="High">High</option>
                    <option value="Medium">Medium</option>
                    <option value="Low">Low</option>
                </select>
                <select id="statusFilter" class="form-control">
                    <option value="all">All Status</option>
                    <option value="assigned">Assigned</option>
                    <option value="unassigned">Unassigned</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="card shadow-lg rounded-xl">
        <div class="card-body">
            <div class="table-header mb-4">
                <h3 class="text-2xl font-semibold">Maintenance Tasks</h3>
                <button class="btn btn-primary rounded-lg" onclick="showAddModal()">
                    <i class="fas fa-plus mr-2"></i>Create New Task
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-sm font-semibold text-secondary">Task ID</th>
                            <th class="text-sm font-semibold text-secondary">Title</th>
                            <th class="text-sm font-semibold text-secondary">Customer</th>
                            <th class="text-sm font-semibold text-secondary">Priority</th>
                            <th class="text-sm font-semibold text-secondary">Agent</th>
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

<!-- Add Task Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <h3 class="text-2xl font-semibold mb-4">Create New Task</h3>
        
        <div class="card-body">
            <?php
            $inputConfig = [
                'id' => 'taskTitle',
                'name' => 'title',
                'label' => 'Task Title',
                'type' => 'text',
                'icon' => 'fas fa-heading',
                'value' => '',
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/input_field.php';
            ?>

            <?php
            $inputConfig = [
                'id' => 'customerName',
                'name' => 'customer',
                'label' => 'Customer Name',
                'type' => 'text',
                'icon' => 'fas fa-user',
                'value' => '',
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/input_field.php';
            ?>

            <?php
            $inputConfig = [
                'id' => 'customerAddress',
                'name' => 'address',
                'label' => 'Address',
                'type' => 'text',
                'icon' => 'fas fa-map-marker-alt',
                'value' => '',
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/input_field.php';
            ?>

            <?php
            $inputConfig = [
                'id' => 'panelId',
                'name' => 'panelId',
                'label' => 'Panel ID',
                'type' => 'text',
                'icon' => 'fas fa-microchip',
                'value' => '',
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/input_field.php';
            ?>

            <?php
            $selectConfig = [
                'id' => 'prioritySelect',
                'name' => 'priority',
                'label' => 'Priority',
                'value' => 'Medium',
                'options' => [
                    'High' => 'High',
                    'Medium' => 'Medium',
                    'Low' => 'Low'
                ],
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/select_field.php';
            ?>
        </div>

        <div class="modal-buttons">
            <button class="btn btn-primary btn-sm rounded-lg" onclick="addTask()">
                <i class="fas fa-check mr-2"></i>Create Task
            </button>
            <button class="btn btn-secondary btn-sm rounded-lg" onclick="closeAddModal()">
                <i class="fas fa-times mr-2"></i>Cancel
            </button>
        </div>
    </div>
</div>

<!-- Assign Task Modal -->
<div id="assignModal" class="modal">
    <div class="modal-content">
        <h3 class="text-2xl font-semibold mb-4">Assign Task</h3>
        
        <div class="card-body">
            <p class="mb-4 text-secondary">Assign this task to an agent:</p>
            
            <?php
            $selectConfig = [
                'id' => 'assignAgent',
                'name' => 'agent',
                'label' => 'Select Agent',
                'value' => '',
                'options' => array_combine($agents, $agents),
                'required' => true,
                'wrapperClass' => 'mb-4'
            ];
            require APPROOT . '/views/inc/components/select_field.php';
            ?>
        </div>

        <div class="modal-buttons">
            <button class="btn btn-primary btn-sm rounded-lg" id="confirmAssignBtn">
                <i class="fas fa-check mr-2"></i>Assign
            </button>
            <button class="btn btn-secondary btn-sm rounded-lg" onclick="closeAssignModal()">
                <i class="fas fa-times mr-2"></i>Cancel
            </button>
        </div>
    </div>
</div>

<!-- Delete Task Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3 class="text-2xl font-semibold mb-4">Delete Task</h3>
        <p id="deleteMessage" class="text-secondary mb-6">Are you sure you want to delete this task?</p>
        
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
let tasks = <?php echo json_encode($tasks); ?>;
let agents = <?php echo json_encode($agents); ?>;
let currentAssignTask = null;
let currentDeleteTaskId = null;

// ---------- Cards Update ----------
function updateCards(){
  const total = tasks.length;
  const assigned = tasks.filter(t=>t.status==="assigned").length;
  const unassigned = tasks.filter(t=>t.status==="unassigned").length;
  const highPriority = tasks.filter(t=>t.priority==="High").length;

  document.getElementById("totalTasks").innerText = total;
  document.getElementById("assignedTasks").innerText = assigned;
  document.getElementById("unassignedTasks").innerText = unassigned;
  document.getElementById("highPriorityTasks").innerText = highPriority;
}

// Helper to update table and cards together
function refreshTableAndCards(){
  filterTasks();
  updateCards();
}

// ---------- Render Table ----------
function renderTable(data){
  const tbody=document.getElementById("tableBody");
  tbody.innerHTML="";
  data.forEach(t=>{
    const priorityClass = t.priority==="High"?"badge-error":t.priority==="Medium"?"badge-warning":"badge-success";
    const priorityIcon = t.priority==="High"?"fa-exclamation-circle":t.priority==="Medium"?"fa-minus-circle":"fa-check-circle";
    tbody.innerHTML+=`
      <tr>
        <td class="text-sm font-semibold">${t.id}</td>
        <td class="text-sm">${t.title}</td>
        <td class="text-sm">${t.customer}</td>
        <td class="text-sm">
          <span class="badge ${priorityClass}">
            <i class="fas ${priorityIcon} mr-1"></i>${t.priority}
          </span>
        </td>
        <td class="text-sm">${t.agent||'Unassigned'}</td>
        <td class="text-sm">
          <span class="badge ${t.status==="assigned"?"badge-success":"badge-warning"}">
            <i class="fas ${t.status==="assigned"?"fa-check-circle":"fa-clock"} mr-1"></i>${t.status===`assigned`?"Assigned":"Unassigned"}
          </span>
        </td>
        <td class="text-sm">
          <button class="btn btn-primary btn-sm rounded-lg bg-success" onclick="assignTask('${t.id}')">
            <i class="fas fa-user-plus mr-1"></i>Assign
          </button>
          <button class="btn btn-primary btn-sm rounded-lg bg-error" onclick="deleteTask('${t.id}')">
            <i class="fas fa-trash mr-1"></i>Delete
          </button>
        </td>
      </tr>`;
  });
}

refreshTableAndCards();

// ---------- Filtering ----------
function filterTasks(){
  const searchTerm=document.getElementById("searchInput").value.toLowerCase();
  const priority=document.getElementById("priorityFilter").value;
  const status=document.getElementById("statusFilter").value;

  let filtered = tasks.filter(t=>{
    return (t.title.toLowerCase().includes(searchTerm) || t.customer.toLowerCase().includes(searchTerm)) &&
           (priority==="all" || t.priority===priority) &&
           (status==="all" || t.status===status);
  });
  renderTable(filtered);
}

document.getElementById("searchInput").addEventListener("input",refreshTableAndCards);
document.getElementById("priorityFilter").addEventListener("change",refreshTableAndCards);
document.getElementById("statusFilter").addEventListener("change",refreshTableAndCards);

// ---------- Modal Functions ----------
function showAddModal(){ document.getElementById("addModal").classList.add("show"); }
function closeAddModal(){ document.getElementById("addModal").classList.remove("show"); }
function showAssignModal(){ document.getElementById("assignModal").classList.add("show"); }
function closeAssignModal(){ document.getElementById("assignModal").classList.remove("show"); currentAssignTask=null; }

function addTask(){
  const title=document.getElementById("taskTitle").value.trim();
  const customer=document.getElementById("customerName").value.trim();
  const address=document.getElementById("customerAddress").value.trim();
  const panelId=document.getElementById("panelId").value.trim();
  const priority=document.getElementById("prioritySelect").value;
  if(!title||!customer||!address||!panelId){ alert("Fill all fields"); return; }
  const id="T-"+(tasks.length+1001);
  const date=new Date().toISOString().split('T')[0];
  tasks.push({id,title,customer,address,date,panelId,notes:"",priority,agent:"",status:"unassigned"});
  refreshTableAndCards();
  closeAddModal();
  ["taskTitle","customerName","customerAddress","panelId"].forEach(id=>document.getElementById(id).value="");
  document.getElementById("prioritySelect").value="Medium";
}

function assignTask(id){
  const task=tasks.find(t=>t.id===id);
  if(!task) return;
  currentAssignTask=task;
  document.getElementById("assignAgent").value=task.agent||"";
  showAssignModal();
}

document.getElementById("confirmAssignBtn").addEventListener("click",()=>{
  if(!currentAssignTask) return;
  const agent=document.getElementById("assignAgent").value;
  if(!agent){ alert("Select an agent"); return; }
  currentAssignTask.agent=agent;
  currentAssignTask.status="assigned";
  refreshTableAndCards();
  closeAssignModal();
});

function deleteTask(id){
  const t=tasks.find(x=>x.id===id);
  if(!t) return;
  currentDeleteTaskId=id;
  document.getElementById("deleteMessage").innerText=`Are you sure you want to delete task "${t.title}" for ${t.customer}?`;
  document.getElementById("deleteModal").classList.add("show");
}

document.getElementById("confirmDeleteBtn").addEventListener("click",()=>{
  if(currentDeleteTaskId){
    tasks=tasks.filter(x=>x.id!==currentDeleteTaskId);
    refreshTableAndCards();
    currentDeleteTaskId=null;
    document.getElementById("deleteModal").classList.remove("show");
  }
});
document.getElementById("cancelDeleteBtn").addEventListener("click",()=>{
  currentDeleteTaskId=null;
  document.getElementById("deleteModal").classList.remove("show");
});

// Close modals on click outside
window.addEventListener("click",e=>{
  if(e.target===document.getElementById("addModal")) closeAddModal();
  if(e.target===document.getElementById("assignModal")) closeAssignModal();
  if(e.target===document.getElementById("deleteModal")){
    currentDeleteTaskId=null;
    document.getElementById("deleteModal").classList.remove("show");
  }
});
</script>

