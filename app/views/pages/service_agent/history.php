<?php
// Dummy task history data
$history = [
    [
        "title" => "Inverter Fault Detected",
        "customer" => "John Doe",
        "address" => "123 Main Street",
        "date" => "2025-08-20",
        "priority" => "high",
        "panelId" => "P-1001",
        "notes" => "Fault fixed after inverter replacement",
        "status" => "done",
        "completedOn" => "2025-08-21"
    ],
    [
        "title" => "Install Solar Battery",
        "customer" => "Sarah Smith",
        "address" => "45 Green Lane",
        "date" => "2025-08-15",
        "priority" => "medium",
        "panelId" => "P-1002",
        "notes" => "Battery installed successfully",
        "status" => "done",
        "completedOn" => "2025-08-16"
    ],
    [
        "title" => "Routine Maintenance",
        "customer" => "Michael Lee",
        "address" => "78 Oak Road",
        "date" => "2025-07-30",
        "priority" => "low",
        "panelId" => "P-1003",
        "notes" => "No issues found during quarterly checkup",
        "status" => "done",
        "completedOn" => "2025-07-30"
    ]
];
?>

  <title>Task History</title>
  <style>
  
    h1 { text-align: center; margin-bottom: 30px; font-size: 28px; color: #111827; }

    /* Toolbar */
    .toolbar { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-bottom: 25px; }
    .toolbar input, .toolbar select {
        padding: 8px 10px; border: 1px solid #d1d5db; border-radius: 6px; min-width: 160px;
    }

    /* Timeline container */
    .timeline { position: relative; margin: 20px auto; width: 90%; max-width: 900px; }
    .timeline::before {
        content: ""; position: absolute; left: 50%; top: 0; bottom: 0;
        width: 3px; background: #e5e7eb; transform: translateX(-50%);
    }

    /* Timeline items */
    .timeline-item {
        position: relative; width: 50%; padding: 20px; box-sizing: border-box;
        opacity: 0; transform: translateY(20px);
        transition: opacity 0.4s ease, transform 0.4s ease;
    }
    .timeline-item.show { opacity: 1; transform: translateY(0); }

    .timeline-item:nth-child(odd) { left: 0; text-align: right; }
    .timeline-item:nth-child(even) { left: 50%; }

    .timeline-item::before {
        content: ""; position: absolute; top: 20px; width: 15px; height: 15px;
        border-radius: 50%; border: 3px solid #fff;
        box-shadow: 0 0 0 3px #16a34a;
        left: 100%; transform: translateX(-50%);
    }
    .timeline-item:nth-child(even)::before { left: 0; transform: translateX(-50%); }

    /* Timeline markers by priority */
    .timeline-item.high::before { background: #b91c1c; box-shadow: 0 0 0 3px #b91c1c; }
    .timeline-item.medium::before { background: #92400e; box-shadow: 0 0 0 3px #92400e; }
    .timeline-item.low::before { background: #166534; box-shadow: 0 0 0 3px #166534; }

    /* Card style */
    .card {
        background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: inline-block; max-width: 90%;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }

    .card h3 { margin: 0 0 10px; font-size: 18px; color: #111827; }
    .card p { margin: 3px 0; font-size: 14px; color: #4b5563; }

    /* Priority tags */
    .priority { display: inline-block; padding: 4px 10px; border-radius: 10px; font-size: 12px; font-weight: bold; margin-top: 5px; }
    .high { background: #fee2e2; color: #b91c1c; }
    .medium { background: #fef3c7; color: #92400e; }
    .low { background: #dcfce7; color: #166534; }

    /* Completed date */
    .completed { font-size: 13px; color: #059669; font-weight: bold; margin-top: 8px; }

    /* Responsive */
    @media screen and (max-width: 700px) {
      .timeline-item {
        width: 100%; text-align: left !important; left: 0 !important;
      }
      .timeline-item::before { left: 20px !important; }
    }
  </style>
</head>
<body>
  <h1>Task History</h1>

  <!-- Toolbar -->
  <div class="toolbar">
    <input type="text" id="searchBar" placeholder="Search by title or customer...">
    <select id="priorityFilter">
        <option value="all">All Priorities</option>
        <option value="high">High</option>
        <option value="medium">Medium</option>
        <option value="low">Low</option>
    </select>
    <select id="sortOption">
        <option value="completed">Sort by: Completed Date</option>
        <option value="customer">Sort by: Customer</option>
        <option value="priority">Sort by: Priority</option>
    </select>
  </div>

  <!-- Timeline -->
  <div class="timeline" id="timeline"></div>

  <script>
    const history = <?php echo json_encode($history); ?>;
    const timeline = document.getElementById("timeline");
    const searchBar = document.getElementById("searchBar");
    const priorityFilter = document.getElementById("priorityFilter");
    const sortOption = document.getElementById("sortOption");

    function renderHistory() {
        timeline.innerHTML = "";

        // Filter
        let filtered = history.filter(task => {
            const searchValue = searchBar.value.toLowerCase();
            const matchesSearch = task.title.toLowerCase().includes(searchValue) ||
                                  task.customer.toLowerCase().includes(searchValue);
            const matchesPriority = priorityFilter.value === "all" || task.priority === priorityFilter.value;
            return matchesSearch && matchesPriority;
        });

        // Sort
        if (sortOption.value === "completed") {
            filtered.sort((a,b) => new Date(b.completedOn) - new Date(a.completedOn));
        } else if (sortOption.value === "customer") {
            filtered.sort((a,b) => a.customer.localeCompare(b.customer));
        } else if (sortOption.value === "priority") {
            const priorityOrder = { high: 1, medium: 2, low: 3 };
            filtered.sort((a,b) => priorityOrder[a.priority] - priorityOrder[b.priority]);
        }

        // Render
        filtered.forEach((task, index) => {
            const item = document.createElement("div");
            item.className = `timeline-item ${task.priority}`;
            item.innerHTML = `
                <div class="card">
                    <h3>${task.title}</h3>
                    <p><strong>Customer:</strong> ${task.customer}</p>
                    <p><strong>Address:</strong> ${task.address}</p>
                    <p><strong>Task Date:</strong> ${task.date}</p>
                    <p><strong>Panel ID:</strong> ${task.panelId}</p>
                    <p><strong>Notes:</strong> ${task.notes}</p>
                    <span class="priority ${task.priority}">${task.priority}</span>
                    <div class="completed">✔ Completed on ${task.completedOn}</div>
                </div>
            `;
            timeline.appendChild(item);

            // Animate with stagger
            setTimeout(() => {
                item.classList.add("show");
            }, index * 100);
        });
    }

    searchBar.addEventListener("input", renderHistory);
    priorityFilter.addEventListener("change", renderHistory);
    sortOption.addEventListener("change", renderHistory);

    // Initial render
    renderHistory();
  </script>

