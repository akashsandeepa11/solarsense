<?php
// Updated dummy complaint data
$tasks = [
    [
        "title" => "Inverter Fault Detected",
        "customer" => "John Doe",
        "address" => "123 Main Street, Colombo",
        "date" => "2025-09-01",
        "notes" => "Inverter shuts down intermittently during peak hours.",
        "status" => "pending"
    ],
    [
        "title" => "Install Solar Battery",
        "customer" => "Sarah Smith",
        "address" => "45 Green Lane, Kandy",
        "date" => "2025-08-30",
        "notes" => "Customer requested hybrid battery setup.",
        "status" => "done"
    ],
    [
        "title" => "Routine Maintenance",
        "customer" => "Michael Lee",
        "address" => "78 Oak Road, Negombo",
        "date" => "2025-08-29",
        "notes" => "Quarterly inspection and cleaning.",
        "status" => "pending"
    ],
    [
        "title" => "Panel Cleaning Request",
        "customer" => "Anusha Perera",
        "address" => "12 Temple Road, Galle",
        "date" => "2025-10-02",
        "notes" => "Dust accumulation reducing efficiency.",
        "status" => "done"
    ],
    [
        "title" => "System Not Generating Power",
        "customer" => "David Fernando",
        "address" => "21 Hill View, Matara",
        "date" => "2025-10-10",
        "notes" => "No power output since morning; inverter shows error 03.",
        "status" => "pending"
    ],
    [
        "title" => "Battery Backup Issue",
        "customer" => "Rashmi Silva",
        "address" => "56 Beach Drive, Trincomalee",
        "date" => "2025-09-20",
        "notes" => "Battery not charging properly during night mode.",
        "status" => "done"
    ],
    [
        "title" => "Voltage Fluctuation",
        "customer" => "Ishara Gunasekara",
        "address" => "102 Main Street, Kurunegala",
        "date" => "2025-10-03",
        "notes" => "Voltage fluctuates between 210–240V during operation.",
        "status" => "pending"
    ],
    [
        "title" => "App Connectivity Issue",
        "customer" => "Nuwan Jayasinghe",
        "address" => "88 Central Avenue, Colombo",
        "date" => "2025-09-25",
        "notes" => "Customer unable to view energy stats via mobile app.",
        "status" => "done"
    ]
];
?>

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f9fafb;
    color: #333;
}
.main {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 30px 20px;
}

/* Title */
.main h2 {
    font-size: 26px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #111827;
}

/* Counter cards */
.task-counter {
    display: flex;
    justify-content: space-between;
    width: 900px;
    max-width: 100%;
    margin-bottom: 25px;
    gap: 15px;
}
.counter-card {
    flex: 1;
    background: #fff;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    text-align: center;
}
.counter-card h4 {
    font-size: 15px;
    color: #6b7280;
    margin-bottom: 8px;
}
.counter-card p {
    font-size: 28px;
    font-weight: 700;
    color: #1f2937;
}

/* Toolbar */
.toolbar {
    display: flex;
    flex-wrap: wrap;
    width: 900px;
    max-width: 100%;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    gap: 10px;
}
.toolbar input,
.toolbar select {
    padding: 10px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    flex: 1;
    font-size: 14px;
    min-width: 180px;
    transition: border 0.2s;
}
.toolbar input:focus,
.toolbar select:focus {
    border-color: #3b82f6;
    outline: none;
}

/* Task list */
.task-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    width: 900px;
    max-width: 100%;
}

/* Task card */
.task-card {
    background: white;
    padding: 20px;
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-left: 6px solid #e5e7eb;
    transition: all 0.2s;
}
.task-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.12);
}

/* Info */
.task-info {
    flex: 1;
}
.task-info h3 {
    margin: 0 0 5px;
    font-size: 18px;
    font-weight: 600;
    color: #111827;
}
.task-info p {
    margin: 3px 0;
    font-size: 14px;
    color: #4b5563;
}

/* Buttons & status */
.task-buttons {
    display: flex;
    align-items: center;
    gap: 10px;
}
.status {
    padding: 5px 10px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    text-transform: capitalize;
}
.pending { background: #fef3c7; color: #92400e; }
.done { background: #dcfce7; color: #166534; }

.view-btn, .resolve-btn {
    padding: 8px 14px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s;
}
.view-btn {
    background-color: #3b82f6;
    color: #fff;
}
.view-btn:hover {
    background-color: #2563eb;
}
.resolve-btn {
    background-color: #16a34a;
    color: #fff;
}
.resolve-btn:hover {
    background-color: #15803d;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.4);
}
.modal.show {
    display: block;
}
.modal-content {
    background: #fff;
    margin: 10% auto;
    padding: 25px;
    border-radius: 14px;
    width: 400px;
    max-width: 90%;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}
.modal-content h2 {
    margin-top: 0;
    font-size: 20px;
    font-weight: 600;
}
.close-btn {
    float: right;
    cursor: pointer;
    color: #6b7280;
    font-size: 20px;
}
.close-btn:hover {
    color: #000;
}
</style>

<div class="main">
    <h2>Customer Complaints</h2>

    <div class="task-counter">
        <div class="counter-card">
            <h4>Pending</h4>
            <p id="pendingCount">0</p>
        </div>
        <div class="counter-card">
            <h4>Resolved</h4>
            <p id="doneCount">0</p>
        </div>
    </div>

    <div class="toolbar">
        <input type="text" id="searchBar" placeholder="Search by customer or issue...">
        <select id="statusFilter">
            <option value="all">All</option>
            <option value="pending">Pending</option>
            <option value="done">Resolved</option>
        </select>
    </div>

    <div class="task-list" id="taskList"></div>
</div>

<!-- Modal -->
<div id="taskModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" id="closeModal">&times;</span>
        <h2 id="modalTitle"></h2>
        <p><strong>Customer:</strong> <span id="modalUser"></span></p>
        <p><strong>Address:</strong> <span id="modalAddress"></span></p>
        <p><strong>Date:</strong> <span id="modalDate"></span></p>
        <p><strong>Notes:</strong> <span id="modalNotes"></span></p>
    </div>
</div>

<script>
const tasks = <?php echo json_encode($tasks); ?>;
const taskList = document.getElementById("taskList");
const searchBar = document.getElementById("searchBar");
const statusFilter = document.getElementById("statusFilter");
const pendingCount = document.getElementById("pendingCount");
const doneCount = document.getElementById("doneCount");

const modal = document.getElementById("taskModal");
const closeModal = document.getElementById("closeModal");

function renderTasks() {
    taskList.innerHTML = "";
    const search = searchBar.value.toLowerCase();
    const filter = statusFilter.value;

    const filtered = tasks.filter(task => {
        const matchesSearch = task.title.toLowerCase().includes(search) || task.customer.toLowerCase().includes(search);
        const matchesFilter = filter === "all" || task.status === filter;
        return matchesSearch && matchesFilter;
    });

    pendingCount.textContent = tasks.filter(t => t.status === "pending").length;
    doneCount.textContent = tasks.filter(t => t.status === "done").length;

    filtered.forEach(task => {
        const card = document.createElement("div");
        card.className = "task-card";

        card.innerHTML = `
            <div class="task-info">
                <h3>${task.title}</h3>
                <p><strong>${task.customer}</strong> — ${task.address}</p>
                <p>${task.date}</p>
            </div>
            <div class="task-buttons">
                <span class="status ${task.status}">${task.status}</span>
                <button class="view-btn">View</button>
                ${task.status === "pending" ? '<button class="resolve-btn">Resolve</button>' : ''}
            </div>
        `;

        card.querySelector(".view-btn").addEventListener("click", () => {
            document.getElementById("modalTitle").textContent = task.title;
            document.getElementById("modalUser").textContent = task.customer;
            document.getElementById("modalAddress").textContent = task.address;
            document.getElementById("modalDate").textContent = task.date;
            document.getElementById("modalNotes").textContent = task.notes;
            modal.classList.add("show");
        });

        const resolveBtn = card.querySelector(".resolve-btn");
        if (resolveBtn) {
            resolveBtn.addEventListener("click", () => {
                task.status = "done";
                renderTasks();
            });
        }

        taskList.appendChild(card);
    });
}

closeModal.addEventListener("click", () => modal.classList.remove("show"));
window.addEventListener("click", e => { if (e.target === modal) modal.classList.remove("show"); });

searchBar.addEventListener("input", renderTasks);
statusFilter.addEventListener("change", renderTasks);

renderTasks();
</script>
