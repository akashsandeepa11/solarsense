<?php
// Dummy task data (all done tasks)
$tasks = [
    [
        "title" => "Inverter Fault Detected",
        "customer" => "John Doe",
        "address" => "123 Main Street",
        "date" => "2025-09-01",
        "priority" => "high",
        "panelId" => "P-1001",
        "notes" => "Inverter shutting down frequently",
        "status" => "done"
    ],
    [
        "title" => "Install Solar Battery",
        "customer" => "Sarah Smith",
        "address" => "45 Green Lane",
        "date" => "2025-08-30",
        "priority" => "medium",
        "panelId" => "P-1002",
        "notes" => "New battery installation",
        "status" => "done"
    ],
    [
        "title" => "Routine Maintenance",
        "customer" => "Michael Lee",
        "address" => "78 Oak Road",
        "date" => "2025-08-29",
        "priority" => "low",
        "panelId" => "P-1003",
        "notes" => "Quarterly checkup",
        "status" => "done"
    ]
];
?>

<style>
/* Main container */
.main { display: flex; flex-direction: column; align-items: center; }

/* Toolbar */
.toolbar { display: flex; flex-direction: column; width: 800px; max-width: 100%; gap: 10px; margin-bottom: 20px; }
.toolbar-row { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
.toolbar-row input, .toolbar-row select { padding: 8px; border: 1px solid #ccc; border-radius: 5px; flex: 1; min-width: 120px; }
.toolbar-row label { display: flex; align-items: center; gap: 5px; }
.toolbar-row.date {display: flex; justify-content: space-between; }


/* Task list */
.task-list { display: flex; flex-direction: column; align-items: center; gap: 15px; }

/* Task card */
.task-card { width: 800px; max-width: 100%; background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); border-left: 5px solid #ccc; display: flex; justify-content: space-between; align-items: center; text-decoration: none; color: inherit; transition: box-shadow 0.2s; }
.task-card:hover { box-shadow: 0 6px 15px rgba(0,0,0,0.15); transform: translateY(-3px); }

.task-info h3 { margin: 0 0 5px; font-size: 18px; }
.task-info p { margin: 2px 0; font-size: 14px; color: #555; }

/* Priority badge */
.priority { padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
.high { background: #fee2e2; color: #b91c1c; border-left-color: #b91c1c; }
.medium { background: #fef3c7; color: #92400e; border-left-color: #f59e0b; }
.low { background: #dcfce7; color: #166534; border-left-color: #22c55e; }

/* Status badge */
.status { padding: 4px 8px; border-radius: 6px; font-size: 12px; margin-left: 8px; font-weight: bold; }

/* Buttons */
.task-buttons button { margin-left: 10px; padding: 6px 12px; border: none; border-radius: 5px; cursor: pointer; font-size: 13px; transition: background 0.2s; }
.view-btn { background-color: #3b82f6; color: white; }
.view-btn:hover { background-color: #2563eb; }

/* Modal */
.modal { display: block; opacity: 0; pointer-events: none; position: fixed; z-index: 5000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); transition: opacity 0.3s ease; }
.modal.show { opacity: 1; pointer-events: auto; }
.modal-content { background-color: #fff; margin: 10% auto; padding: 20px; border-radius: 10px; width: 400px; max-width: 90%; box-shadow: 0 4px 10px rgba(0,0,0,0.2); transform: translateY(-20px); transition: transform 0.3s ease; }
.modal.show .modal-content { transform: translateY(0); }
.close-btn { float: right; font-size: 18px; cursor: pointer; color: #555; }
.close-btn:hover { color: black; }
</style>

<div class="main">
    <h2>Service Agent History</h2>

    <!-- Toolbar -->
    <div class="toolbar">
        <!-- Row 1 -->
        <div class="toolbar-row">
            <input type="text" id="searchBar" placeholder="Search completed tasks...">
            <select id="priorityFilter">
                <option value="all">All Priorities</option>
                <option value="high">High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
            </select>
            <select id="sortOption">
                <option value="date">Sort by: Date</option>
                <option value="customer">Sort by: Customer</option>
                <option value="priority">Sort by: Priority</option>
            </select>
        </div>

        <!-- Row 2: Date range -->
        <div class="toolbar-row date">
            <label>From: <input type="date" id="startDate"></label>
            <label>To: <input type="date" id="endDate"></label>
        </div>
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

<script>
const tasks = <?php echo json_encode($tasks); ?>;
const taskList = document.getElementById("taskList");
const searchBar = document.getElementById("searchBar");
const priorityFilter = document.getElementById("priorityFilter");
const sortOption = document.getElementById("sortOption");
const startDate = document.getElementById("startDate");
const endDate = document.getElementById("endDate");

// Modal elements
const taskModal = document.getElementById("taskModal");
const closeModal = document.getElementById("closeModal");
const modalTitle = document.getElementById("modalTitle");
const modalCustomer = document.getElementById("modalCustomer");
const modalAddress = document.getElementById("modalAddress");
const modalDate = document.getElementById("modalDate");
const modalPanel = document.getElementById("modalPanel");
const modalNotes = document.getElementById("modalNotes");

// Render history tasks
function renderTasks() {
    taskList.innerHTML = "";

    // Only done tasks
    let filteredTasks = tasks.filter(task => task.status === "done");

    // Search filter
    filteredTasks = filteredTasks.filter(task =>
        task.title.toLowerCase().includes(searchBar.value.toLowerCase()) ||
        task.customer.toLowerCase().includes(searchBar.value.toLowerCase())
    );

    // Priority filter
    if (priorityFilter.value !== "all") {
        filteredTasks = filteredTasks.filter(task => task.priority === priorityFilter.value);
    }

    // Date range filter
    if (startDate.value) {
        filteredTasks = filteredTasks.filter(task => new Date(task.date) >= new Date(startDate.value));
    }
    if (endDate.value) {
        filteredTasks = filteredTasks.filter(task => new Date(task.date) <= new Date(endDate.value));
    }

    // Sorting
    if (sortOption.value === "date") {
        filteredTasks.sort((a, b) => new Date(b.date) - new Date(a.date));
    } else if (sortOption.value === "customer") {
        filteredTasks.sort((a, b) => a.customer.localeCompare(b.customer));
    } else if (sortOption.value === "priority") {
        const priorityOrder = { high: 1, medium: 2, low: 3 };
        filteredTasks.sort((a, b) => priorityOrder[a.priority] - priorityOrder[b.priority]);
    }

    // Render each task
    filteredTasks.forEach(task => {
        const card = document.createElement("div");
        card.className = `task-card ${task.priority}`;
        card.innerHTML = `
            <div class="task-info">
                <h3>${task.title}</h3>
                <p>Customer: ${task.customer}</p>
                <p>Address: ${task.address}</p>
                <p>Assigned: ${task.date}</p>
            </div>
            <div class="task-buttons">
                <span class="priority ${task.priority}">${task.priority.charAt(0).toUpperCase() + task.priority.slice(1)}</span>
                <button class="view-btn">View</button>
            </div>
        `;

        card.querySelector(".view-btn").addEventListener("click", () => {
            modalTitle.textContent = task.title;
            modalCustomer.textContent = task.customer;
            modalAddress.textContent = task.address;
            modalDate.textContent = task.date;
            modalPanel.textContent = task.panelId;
            modalNotes.textContent = task.notes;
            taskModal.classList.add("show");
        });

        taskList.appendChild(card);
    });
}

// Modal close
closeModal.addEventListener("click", () => taskModal.classList.remove("show"));
window.addEventListener("click", e => { if (e.target === taskModal) taskModal.classList.remove("show"); });

// Filters & search
searchBar.addEventListener("input", renderTasks);
priorityFilter.addEventListener("change", renderTasks);
sortOption.addEventListener("change", renderTasks);
startDate.addEventListener("change", renderTasks);
endDate.addEventListener("change", renderTasks);

// Initial render
renderTasks();
</script>
