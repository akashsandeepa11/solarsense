<?php
// Dummy task data 
$tasks = [
    [
        "title" => "Inverter Fault Detected",
        "customer" => "John Doe",
        "address" => "123 Main Street",
        "date" => "2025-09-01",
        "priority" => "high",
        "panelId" => "P-1001",
        "notes" => "Inverter shutting down frequently",
        "status" => "pending"
    ],
    [
        "title" => "Install Solar Battery",
        "customer" => "Sarah Smith",
        "address" => "45 Green Lane",
        "date" => "2025-08-30",
        "priority" => "medium",
        "panelId" => "P-1002",
        "notes" => "New battery installation",
        "status" => "in-progress"
    ],
    [
        "title" => "Routine Maintenance",
        "customer" => "Michael Lee",
        "address" => "78 Oak Road",
        "date" => "2025-08-29",
        "priority" => "low",
        "panelId" => "P-1003",
        "notes" => "Quarterly checkup",
        "status" => "pending"
    ]
];
?>

<style>
/* Main container */
.main { display: flex; flex-direction: column; align-items: center; }

/* Task counter cards */
.task-counter { display: flex; justify-content: space-between; width: 800px; max-width: 100%; margin-bottom: 20px; gap: 15px; }
.counter-card { flex: 1; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-align: center; transition: transform 0.2s, box-shadow 0.2s; cursor: default; }
.counter-card h4 { margin: 0 0 10px; font-size: 16px; color: #555; font-weight: 600; }
.counter-card p { margin: 0; font-size: 24px; font-weight: bold; }
.counter-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }

/* Toolbar */
.toolbar { display: flex; flex-wrap: wrap; width: 800px; max-width: 100%; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 10px; }
.toolbar input, .toolbar select { padding: 8px; border: 1px solid #ccc; border-radius: 5px; flex: 1; min-width: 150px; }

/* Task list */
.task-list { display: flex; flex-direction: column; align-items: center; gap: 15px; }

/* Task card */
.task-card { width: 800px; max-width: 100%; background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); border-left: 5px solid #ccc; display: flex; justify-content: space-between; align-items: center; text-decoration: none; color: inherit; transition: box-shadow 0.2s; }
.task-card:hover { box-shadow: 0 6px 15px rgba(0,0,0,0.15); transform: translateY(-3px); }

/* Task info */
.task-info h3 { margin: 0 0 5px; font-size: 18px; }
.task-info p { margin: 2px 0; font-size: 14px; color: #555; }

/* Priority badges */
.priority { padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
.high { background: #fee2e2; color: #b91c1c; border-left-color: #b91c1c; }
.medium { background: #fef3c7; color: #92400e; border-left-color: #f59e0b; }
.low { background: #dcfce7; color: #166534; border-left-color: #22c55e; }

/* Buttons */
.task-buttons button { margin-left: 10px; padding: 6px 12px; border: none; border-radius: 5px; cursor: pointer; font-size: 13px; transition: background 0.2s; }
.view-btn { background-color: #3b82f6; color: white; }
.view-btn:hover { background-color: #2563eb; }
.done-btn { background-color: #16a34a; color: white; }
.done-btn:hover { background-color: #15803d; }

/* Status badges */
.status { padding: 4px 8px; border-radius: 6px; font-size: 12px; margin-left: 8px; font-weight: bold; }
.pending { background: #f3f4f6; color: #374151; }
.in-progress { background: #dbeafe; color: #1d4ed8; }
.done { background: #dcfce7; color: #166534; }

/* Modal */
.modal { display: block; opacity: 0; pointer-events: none; position: fixed; z-index: 5000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); transition: opacity 0.3s ease; }
.modal.show { opacity: 1; pointer-events: auto; }
.modal-content { background-color: #fff; margin: 10% auto; padding: 20px; border-radius: 10px; width: 400px; max-width: 90%; box-shadow: 0 4px 10px rgba(0,0,0,0.2); transform: translateY(-20px); transition: transform 0.3s ease; }
.modal.show .modal-content { transform: translateY(0); }
.close-btn { float: right; font-size: 18px; cursor: pointer; color: #555; }
.close-btn:hover { color: black; }
.confirmBtns { display: flex; align-items: center; justify-content: space-between; }
.task-counter span { padding: 6px 12px; border-radius: 8px; background: #f3f4f6; font-weight: bold; }
.task-counter span strong { margin-right: 5px; }
</style>



<div class="main">
    <!-- Task Counter -->
    <div class="task-counter" id="taskCounter">
        <div class="counter-card">
            <h4>Pending</h4>
            <p id="pendingCount">0</p>
        </div>
        <div class="counter-card">
            <h4>In Progress</h4>
            <p id="inProgressCount">0</p>
        </div>
        <div class="counter-card">
            <h4>Done</h4>
            <p id="doneCount">0</p>
        </div>
    </div>

    <!-- Toolbar -->
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
            <option value="pending">Pending</option>
            <option value="in-progress">In Progress</option>
            <option value="done">Done</option>
        </select>
        <select id="sortOption">
            <option value="priority">Sort by: Priority</option>
            <option value="date">Sort by: Date</option>
            <option value="customer">Sort by: Customer</option>
        </select>
    </div>

    <!-- Task List -->
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

<!-- Done Confirmation Modal -->
<!-- <div id="doneModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" id="closeDoneModal">&times;</span>
        <h3>Confirm Task Completion</h3>
        <p>Are you sure you want to mark this task as completed?</p>
        <div class="confirmBtns">
            <button class="btn btn-secondary rounded-lg" id="cancelDoneBtn">No</button>
            <button class="btn btn-primary rounded-lg" id="confirmDoneBtn">Yes</button>
        </div>
    </div>
</div> -->

<script>
const tasks = <?php echo json_encode($tasks); ?>;

const taskList = document.getElementById("taskList");
const searchBar = document.getElementById("searchBar");
const priorityFilter = document.getElementById("priorityFilter");
const statusFilter = document.getElementById("statusFilter");
const sortOption = document.getElementById("sortOption");

// View Modal elements
const taskModal = document.getElementById("taskModal");
const closeModal = document.getElementById("closeModal");
const modalTitle = document.getElementById("modalTitle");
const modalCustomer = document.getElementById("modalCustomer");
const modalAddress = document.getElementById("modalAddress");
const modalDate = document.getElementById("modalDate");
const modalPanel = document.getElementById("modalPanel");
const modalNotes = document.getElementById("modalNotes");

// // Done Modal elements
// const doneModal = document.getElementById("doneModal");
// const closeDoneModal = document.getElementById("closeDoneModal");
// const cancelDoneBtn = document.getElementById("cancelDoneBtn");
// const confirmDoneBtn = document.getElementById("confirmDoneBtn");
// let selectedTask = null;

// Render tasks
function renderTasks() {
    taskList.innerHTML = "";

    // Filter tasks
    let filteredTasks = tasks.filter(task => {
        const matchesSearch = task.title.toLowerCase().includes(searchBar.value.toLowerCase()) ||
                              task.customer.toLowerCase().includes(searchBar.value.toLowerCase());
        const matchesPriority = priorityFilter.value === "all" || task.priority === priorityFilter.value;
        const matchesStatus = statusFilter.value === "all" || task.status === statusFilter.value;
        return matchesSearch && matchesPriority && matchesStatus;
    });

    // Sort tasks
    if (sortOption.value === "date") {
        filteredTasks.sort((a, b) => new Date(b.date) - new Date(a.date));
    } else if (sortOption.value === "customer") {
        filteredTasks.sort((a, b) => a.customer.localeCompare(b.customer));
    } else if (sortOption.value === "priority") {
        const priorityOrder = { high: 1, medium: 2, low: 3 };
        filteredTasks.sort((a, b) => priorityOrder[a.priority] - priorityOrder[b.priority]);
    }

    // Update counters
    document.getElementById("pendingCount").textContent = tasks.filter(t => t.status === "pending").length;
    document.getElementById("inProgressCount").textContent = tasks.filter(t => t.status === "in-progress").length;
    document.getElementById("doneCount").textContent = tasks.filter(t => t.status === "done").length;

    // Render each task
    filteredTasks.forEach(task => {
        const card = document.createElement("div");
        card.className = `task-card ${task.priority}`;

        // Determine buttons
        let actionButtons = "";
        if (task.status === "pending") {
            actionButtons = `<button class="start-btn">Start</button>`;
        } else if (task.status === "in-progress") {
            actionButtons = `<button class="done-btn">Done</button>`;
        }

        card.innerHTML = `
            <div class="task-info">
                <h3>${task.title}</h3>
                <p>Customer: ${task.customer}</p>
                <p>Address: ${task.address}</p>
                <p>Assigned: ${task.date}</p>
            </div>
            <div class="task-buttons">
                <span class="priority ${task.priority}">${task.priority.charAt(0).toUpperCase() + task.priority.slice(1)}</span>
                <span class="status ${task.status}">${task.status.charAt(0).toUpperCase() + task.status.slice(1)}</span>
                <button class="view-btn">View</button>
                ${actionButtons}
            </div>
        `;

        // View button
        card.querySelector(".view-btn").addEventListener("click", () => {
            modalTitle.textContent = task.title;
            modalCustomer.textContent = task.customer;
            modalAddress.textContent = task.address;
            modalDate.textContent = task.date;
            modalPanel.textContent = task.panelId;
            modalNotes.textContent = task.notes;
            taskModal.classList.add("show");
        });

        // Start button
        const startBtn = card.querySelector(".start-btn");
        if (startBtn) {
            startBtn.addEventListener("click", () => {
                task.status = "in-progress";
                renderTasks();
            });
        }

        // Done button
        const doneBtn = card.querySelector(".done-btn");
        if (doneBtn) {
            doneBtn.addEventListener("click", () => {
                selectedTask = task;
                // doneModal.classList.add("show");
                window.location.href = "<?php echo URLROOT?>/serviceagent/report/"+task.panelId;


            });
        }

        taskList.appendChild(card);
    });
}

// Modal close events
closeModal.addEventListener("click", () => taskModal.classList.remove("show"));
window.addEventListener("click", e => { if (e.target === taskModal) taskModal.classList.remove("show"); });

// closeDoneModal.addEventListener("click", () => doneModal.classList.remove("show"));
// cancelDoneBtn.addEventListener("click", () => doneModal.classList.remove("show"));
// window.addEventListener("click", e => { if (e.target === doneModal) doneModal.classList.remove("show"); });

// // Confirm Done
// confirmDoneBtn.addEventListener("click", () => {
//     if (selectedTask) {
//         selectedTask.status = "done";
//         renderTasks();
//         doneModal.classList.remove("show");
//     }
// });

// Filters & sorting
searchBar.addEventListener("input", renderTasks);
priorityFilter.addEventListener("change", renderTasks);
statusFilter.addEventListener("change", renderTasks);
sortOption.addEventListener("change", renderTasks);

// Initial render
renderTasks();
</script>

