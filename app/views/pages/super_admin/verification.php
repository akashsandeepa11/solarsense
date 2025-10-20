<?php
// Example: Dummy installer registration requests fetched from DB
$registrations = [
    [
        "companyName" => "SunTech Installers",
        "email" => "info@suntech.com",
        "contactNumber" => "0771234567",
        "physicalAddress" => "45 Solar Avenue, Colombo",
        "submittedDate" => "2025-10-19",
        "status" => "pending"
    ],
    [
        "companyName" => "EcoWatt Solutions",
        "email" => "support@ecowatt.lk",
        "contactNumber" => "0769876543",
        "physicalAddress" => "22 Green Park Road, Kandy",
        "submittedDate" => "2025-10-18",
        "status" => "verified"
    ],
    [
    "companyName" => "SolarRise Energy",
    "email" => "contact@solarrise.lk",
    "contactNumber" => "0714456789",
    "physicalAddress" => "10 Lighthouse Road, Galle",
    "submittedDate" => "2025-10-17",
    "status" => "pending"
],
[
    "companyName" => "GreenVolt Installers",
    "email" => "info@greenvolt.com",
    "contactNumber" => "0782345678",
    "physicalAddress" => "88 Powerline Street, Negombo",
    "submittedDate" => "2025-10-15",
    "status" => "verified"
],
[
    "companyName" => "SunPeak Engineering",
    "email" => "service@sunpeak.lk",
    "contactNumber" => "0759988776",
    "physicalAddress" => "5 Tech Park Avenue, Kurunegala",
    "submittedDate" => "2025-10-16",
    "status" => "pending"
],
[
    "companyName" => "BrightRay Solutions",
    "email" => "admin@brightray.com",
    "contactNumber" => "0701234987",
    "physicalAddress" => "32 Central Road, Matara",
    "submittedDate" => "2025-10-12",
    "status" => "verified"
]

];
?>

<style>
/* Reuse your existing styles */
.main { display: flex; flex-direction: column; align-items: center; }

.task-counter { display: flex; justify-content: space-between; width: 800px; max-width: 100%; margin-bottom: 20px; gap: 15px; }
.counter-card { flex: 1; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-align: center; transition: transform 0.2s, box-shadow 0.2s; cursor: default; }
.counter-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }

.toolbar { display: flex; flex-wrap: wrap; width: 800px; max-width: 100%; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 10px; }
.toolbar input, .toolbar select { padding: 8px; border: 1px solid #ccc; border-radius: 5px; flex: 1; min-width: 150px; }

.task-list { display: flex; flex-direction: column; align-items: center; gap: 15px; }

.task-card { width: 800px; max-width: 100%; background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); border-left: 5px solid #3b82f6; display: flex; justify-content: space-between; align-items: center; text-decoration: none; color: inherit; transition: box-shadow 0.2s; }
.task-card:hover { box-shadow: 0 6px 15px rgba(0,0,0,0.15); transform: translateY(-3px); }

.task-info h3 { margin: 0 0 5px; font-size: 18px; }
.task-info p { margin: 2px 0; font-size: 14px; color: #555; }

.task-buttons button { margin-left: 10px; padding: 6px 12px; border: none; border-radius: 5px; cursor: pointer; font-size: 13px; transition: background 0.2s; }
.view-btn { background-color: #3b82f6; color: white; }
.view-btn:hover { background-color: #2563eb; }
.verify-btn { background-color: #16a34a; color: white; }
.verify-btn:hover { background-color: #15803d; }

.status { padding: 4px 8px; border-radius: 6px; font-size: 12px; margin-left: 8px; font-weight: bold; }
.pending { background: #fef9c3; color: #92400e; }
.verified { background: #dcfce7; color: #166534; }

.modal { display: block; opacity: 0; pointer-events: none; position: fixed; z-index: 5000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); transition: opacity 0.3s ease; }
.modal.show { opacity: 1; pointer-events: auto; }
.modal-content { background-color: #fff; margin: 10% auto; padding: 20px; border-radius: 10px; width: 400px; max-width: 90%; box-shadow: 0 4px 10px rgba(0,0,0,0.2); transform: translateY(-20px); transition: transform 0.3s ease; }
.modal.show .modal-content { transform: translateY(0); }
.close-btn { float: right; font-size: 18px; cursor: pointer; color: #555; }
.close-btn:hover { color: black; }
.confirmBtns { display: flex; align-items: center; justify-content: space-between; }
</style>

<div class="main">
    <h3>Installer Verification Requests</h3>

    <!-- Toolbar -->
    <div class="toolbar">
        <input type="text" id="searchBar" placeholder="Search by company name...">
        <select id="statusFilter">
            <option value="all">All Status</option>
            <option value="pending">Pending</option>
            <option value="verified">Verified</option>
        </select>
    </div>

    <!-- Registration List -->
    <div class="task-list" id="registrationList"></div>
</div>

<!-- View Modal -->
<div id="registrationModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" id="closeModal">&times;</span>
        <h2 id="modalCompany"></h2>
        <p><strong>Email:</strong> <span id="modalEmail"></span></p>
        <p><strong>Contact:</strong> <span id="modalContact"></span></p>
        <p><strong>Address:</strong> <span id="modalAddress"></span></p>
        <p><strong>Submitted Date:</strong> <span id="modalDate"></span></p>
    </div>
</div>

<script>
const registrations = <?php echo json_encode($registrations); ?>;

const registrationList = document.getElementById("registrationList");
const statusFilter = document.getElementById("statusFilter");
const searchBar = document.getElementById("searchBar");

// Modal elements
const registrationModal = document.getElementById("registrationModal");
const closeModal = document.getElementById("closeModal");
const modalCompany = document.getElementById("modalCompany");
const modalEmail = document.getElementById("modalEmail");
const modalContact = document.getElementById("modalContact");
const modalAddress = document.getElementById("modalAddress");
const modalDate = document.getElementById("modalDate");

function renderRegistrations() {
    registrationList.innerHTML = "";

    let filtered = registrations.filter(reg => {
        const matchesSearch = reg.companyName.toLowerCase().includes(searchBar.value.toLowerCase());
        const matchesStatus = statusFilter.value === "all" || reg.status === statusFilter.value;
        return matchesSearch && matchesStatus;
    });

    filtered.forEach(reg => {
        const card = document.createElement("div");
        card.className = `task-card`;

        let verifyBtn = "";
        if (reg.status === "pending") {
            verifyBtn = `<button class="verify-btn">Verify</button>`;
        }

        card.innerHTML = `
            <div class="task-info">
                <h3>${reg.companyName}</h3>
                <p><strong>Email:</strong> ${reg.email}</p>
                <p><strong>Contact:</strong> ${reg.contactNumber}</p>
                <p><strong>Address:</strong> ${reg.physicalAddress}</p>
                <p><strong>Submitted:</strong> ${reg.submittedDate}</p>
            </div>
            <div class="task-buttons">
                <span class="status ${reg.status}">${reg.status.charAt(0).toUpperCase() + reg.status.slice(1)}</span>
                <button class="view-btn">View</button>
                ${verifyBtn}
            </div>
        `;

        // View button
        card.querySelector(".view-btn").addEventListener("click", () => {
            modalCompany.textContent = reg.companyName;
            modalEmail.textContent = reg.email;
            modalContact.textContent = reg.contactNumber;
            modalAddress.textContent = reg.physicalAddress;
            modalDate.textContent = reg.submittedDate;
            registrationModal.classList.add("show");
        });

        // Verify button
        const verifyButton = card.querySelector(".verify-btn");
        if (verifyButton) {
            verifyButton.addEventListener("click", () => {
                reg.status = "verified";
                renderRegistrations();
            });
        }

        registrationList.appendChild(card);
    });
}

// Close modal
closeModal.addEventListener("click", () => registrationModal.classList.remove("show"));
window.addEventListener("click", e => { if (e.target === registrationModal) registrationModal.classList.remove("show"); });

searchBar.addEventListener("input", renderRegistrations);
statusFilter.addEventListener("change", renderRegistrations);

// Initial render
renderRegistrations();
</script>
