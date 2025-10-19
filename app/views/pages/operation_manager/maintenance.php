

<?php
// Dummy task data
$tasks = [
    [
        "title" => "Inverter Fault Detected",
        "customer" => "John Doe",
        "address" => "123 Main Street",
        "date" => "2025-09-01",
        "assighned_date" => "- ",
        "priority" => "", // Initially no priority
        "agent" => "",    // Initially no agent
        "panelId" => "P-1001",
        "notes" => "Inverter shutting down frequently",
        "status" => "unassigned"
    ],
    [
        "title" => "Install Solar Battery",
        "customer" => "Sarah Smith",
        "address" => "45 Green Lane",
        "date" => "2025-08-30",
        "assighned_date" => "- ",
        "priority" => "",
        "agent" => "",
        "panelId" => "P-1002",
        "notes" => "New battery installation",
        "status" => "unassigned"
    ],
    [
        "title" => "Routine Maintenance",
        "customer" => "Michael Lee",
        "address" => "78 Oak Road",
        "date" => "2025-08-29",
        "assighned_date" => "- ",
        "priority" => "",
        "agent" => "",
        "panelId" => "P-1003",
        "notes" => "Quarterly checkup",
        "status" => "unassigned"
    ]
];

// Dummy agent list
$agents = ["Agent A", "Agent B", "Agent C"];
?>



<style>

.main { display: flex; flex-direction: column; align-items: center; }
.task-counter { display: flex; justify-content: space-between; width: 800px; max-width: 100%; margin-bottom: 20px; gap: 15px; }
.counter-card { flex: 1; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-align: center; }
.counter-card h4 { margin: 0 0 10px; font-size: 16px; color: #555; font-weight: 600; }
.counter-card p { margin: 0; font-size: 24px; font-weight: bold; }

.toolbar { display: flex; flex-wrap: wrap; width: 800px; max-width: 100%; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 10px; }
.toolbar input, .toolbar select { padding: 8px; border: 1px solid #ccc; border-radius: 5px; flex: 1; min-width: 150px; }

.task-list { display: flex; flex-direction: column; align-items: center; gap: 15px; }

.task-card { width: 800px; max-width: 100%; background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); border-left: 5px solid #ccc; display: flex; justify-content: space-between; align-items: center; text-decoration: none; color: inherit; transition: box-shadow 0.2s; }
.task-card:hover { box-shadow: 0 6px 15px rgba(0,0,0,0.15); transform: translateY(-3px); }

.task-info h3 { margin: 0 0 5px; font-size: 18px; }
.task-info p { margin: 2px 0; font-size: 14px; color: #555; }

.priority { padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; margin-left: 5px; }
.high { background: #fee2e2; color: #b91c1c; border-left-color: #b91c1c; }
.medium { background: #fef3c7; color: #92400e; border-left-color: #f59e0b; }
.low { background: #dcfce7; color: #166534; border-left-color: #22c55e; }

.task-buttons button { margin-left: 10px; padding: 6px 12px; border: none; border-radius: 5px; cursor: pointer; font-size: 13px; transition: background 0.2s; }
.view-btn { background-color: #3b82f6; color: white; }
.view-btn:hover { background-color: #2563eb; }
.assign-btn { background-color: #16a34a; color: white; }
.assign-btn:hover { background-color: #15803d; }

.modal { display: block; opacity: 0; pointer-events: none; position: fixed; z-index: 5000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); transition: opacity 0.3s ease; }
.modal.show { opacity: 1; pointer-events: auto; }
.modal-content { background-color: #fff; margin: 10% auto; padding: 20px; border-radius: 10px; width: 400px; max-width: 90%; box-shadow: 0 4px 10px rgba(0,0,0,0.2); transform: translateY(-20px); transition: transform 0.3s ease; }
.modal.show .modal-content { transform: translateY(0); }
.close-btn { float: right; font-size: 18px; cursor: pointer; color: #555; }
.close-btn:hover { color: black; }
.confirmBtns { display: flex; align-items: center; justify-content: space-between; margin-top: 10px; }

.assign-select { width: 48%; padding: 5px; margin-top: 10px; border-radius: 5px; border: 1px solid #ccc; }
.agent { font-size: 12px; color: #444; margin-left: 5px; font-weight: bold; }
</style>

<div class="main">

    <div class="task-counter">
        <div class="counter-card"><h4>Unassigned</h4><p id="unassignedCount">0</p></div>
        <div class="counter-card"><h4>Assigned</h4><p id="doneCount">0</p></div>
    </div>

    <div class="toolbar">
        <input type="text" id="searchBar" placeholder="Search tasks...">
        <select id="priorityFilter">
            <option value="all">All Priorities</option>
            <option value="high">High</option>
            <option value="medium">Medium</option>
            <option value="low">Low</option>
        </select>
        <select id="statusFilter">
            <option value="all">All Status</option>
            <option value="unassigned">Unassigned</option>
            <option value="pending">Assigned</option>
        </select>
        <select id="sortOption">
            <option value="priority">Sort by: Priority</option>
            <option value="date">Sort by: Date</option>
            <option value="customer">Sort by: Customer</option>
        </select>
    </div>

    <div class="task-list" id="taskList"></div>
</div>

<!-- View Modal -->
<div id="taskModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" id="closeModal">&times;</span>
        <h2 id="modalTitle"></h2>
        <p><strong>Customer:</strong> <span id="modalCustomer"></span></p>
        <p><strong>Address:</strong> <span id="modalAddress"></span></p>
        <p><strong>Date Assigned:</strong> <span id="modalDate"></span></p>
        <p><strong>Panel ID:</strong> <span id="modalPanel"></span></p>
        <p><strong>Notes:</strong> <span id="modalNotes"></span></p>
    </div>
</div>

<!-- Assign Modal -->
<div id="assignModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" id="closeAssignModal">&times;</span>
        <h3>Assign Task</h3>
        <p>Choose a priority and assign an agent:</p>
        <select id="assignPriority" class="assign-select">
            <option value="">Select Priority</option>
            <option value="high">High</option>
            <option value="medium">Medium</option>
            <option value="low">Low</option>
        </select>
        <select id="assignAgent" class="assign-select">
            <option value="">Select Agent</option>
            <?php foreach($agents as $agent): ?>
            <option value="<?php echo $agent; ?>"><?php echo $agent; ?></option>
            <?php endforeach; ?>
        </select>
        <div class="confirmBtns">
            <button class="btn btn-secondary" id="cancelAssign">Cancel</button>
            <button class="btn btn-primary" id="confirmAssign">Assign</button>
        </div>
    </div>
</div>

<script>
const tasks = <?php echo json_encode($tasks); ?>;
let selectedTask = null;

const taskList = document.getElementById("taskList");
const searchBar = document.getElementById("searchBar");
const priorityFilter = document.getElementById("priorityFilter");
const statusFilter = document.getElementById("statusFilter");
const sortOption = document.getElementById("sortOption");

// View modal elements
const taskModal = document.getElementById("taskModal");
const closeModal = document.getElementById("closeModal");
const modalTitle = document.getElementById("modalTitle");
const modalCustomer = document.getElementById("modalCustomer");
const modalAddress = document.getElementById("modalAddress");
const modalDate = document.getElementById("modalDate");
const modalPanel = document.getElementById("modalPanel");
const modalNotes = document.getElementById("modalNotes");

// Assign modal elements
const assignModal = document.getElementById("assignModal");
const closeAssignModal = document.getElementById("closeAssignModal");
const cancelAssign = document.getElementById("cancelAssign");
const confirmAssign = document.getElementById("confirmAssign");
const assignPriority = document.getElementById("assignPriority");
const assignAgent = document.getElementById("assignAgent");

function renderTasks(){
    taskList.innerHTML = "";

    let filteredTasks = tasks.filter(task=>{
        const matchesSearch = task.title.toLowerCase().includes(searchBar.value.toLowerCase()) ||
                              task.customer.toLowerCase().includes(searchBar.value.toLowerCase());
        const matchesPriority = priorityFilter.value==="all" || task.priority===priorityFilter.value;
        const matchesStatus = statusFilter.value==="all" || task.status===statusFilter.value;
        return matchesSearch && matchesPriority && matchesStatus;
    });

    if(sortOption.value==="date") filteredTasks.sort((a,b)=>new Date(b.date)-new Date(a.date));
    else if(sortOption.value==="customer") filteredTasks.sort((a,b)=>a.customer.localeCompare(b.customer));
    else if(sortOption.value==="priority"){
        const order={"high":1,"medium":2,"low":3,"":4};
        filteredTasks.sort((a,b)=>order[a.priority]-order[b.priority]);
    }

    document.getElementById("unassignedCount").textContent = tasks.filter(t=>t.status==="unassigned").length;
    document.getElementById("doneCount").textContent = tasks.filter(t=>t.status==="pending").length;

    filteredTasks.forEach(task=>{
        const card = document.createElement("div");
        card.className = `task-card ${task.priority}`;
        card.innerHTML = `
            <div class="task-info">
                <h3>${task.title}</h3>
                <p>Customer: ${task.customer}</p>
                <p>Address: ${task.address}</p>
                <p>Assigned: ${task.assighned_date}</p>
            </div>
            <div class="task-buttons">
                <span class="priority ${task.priority}">${task.priority?task.priority.charAt(0).toUpperCase()+task.priority.slice(1):"None"}</span>
                <span class="agent">${task.agent?task.agent:"Unassigned"}</span>
                <button class="view-btn">View</button>
                ${!task.priority && !task.agent?'<button class="assign-btn">Assign</button>':''}
            </div>
        `;

        // View button
        card.querySelector(".view-btn").addEventListener("click",()=>{
            modalTitle.textContent = task.title;
            modalCustomer.textContent = task.customer;
            modalAddress.textContent = task.address;
            modalDate.textContent = task.assighned_date;
            modalPanel.textContent = task.panelId;
            modalNotes.textContent = task.notes;
            taskModal.classList.add("show");
        });

        // Assign button
        const assignBtn = card.querySelector(".assign-btn");
        if(assignBtn){
            assignBtn.addEventListener("click", ()=>{
                selectedTask = task;
                assignPriority.value = task.priority || "";
                assignAgent.value = task.agent || "";
                assignModal.classList.add("show");
            });
        }

        taskList.appendChild(card);
    });
}

// View modal events
closeModal.addEventListener("click", ()=>taskModal.classList.remove("show"));
window.addEventListener("click", e=>{ if(e.target===taskModal) taskModal.classList.remove("show"); });

// Assign modal events
closeAssignModal.addEventListener("click", ()=>assignModal.classList.remove("show"));
cancelAssign.addEventListener("click", ()=>assignModal.classList.remove("show"));
window.addEventListener("click", e=>{ if(e.target===assignModal) assignModal.classList.remove("show"); });

confirmAssign.addEventListener("click", ()=>{
    if(selectedTask){
        if(assignPriority.value && assignAgent.value){
            selectedTask.priority = assignPriority.value;
            selectedTask.agent = assignAgent.value;
            selectedTask.status = "pending";

             // Update the assignment date to today
            const today = new Date();
            selectedTask.assighned_date = today.getFullYear() + "-" + 
                                String(today.getMonth()+1).padStart(2,"0") + "-" +
                                String(today.getDate()).padStart(2,"0");
                                
            renderTasks();
            assignModal.classList.remove("show");
        } else {
            alert("Please select both priority and agent before assigning.");
        }
    }
});

// Filters & search
searchBar.addEventListener("input", renderTasks);
priorityFilter.addEventListener("change", renderTasks);
statusFilter.addEventListener("change", renderTasks);
sortOption.addEventListener("change", renderTasks);

// Initial render
renderTasks();
</script>

