<head>
    <style>
        .container {
    max-width: 900px;
    margin: 2rem auto;
    padding: 1rem 2rem;
}

/* Card style for request form and history items */
.card {
    background: #fff;
    border: 1px solid #ff7a00;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 6px 15px rgba(0,0,0,0.08);
    transform: translateY(-3px);
}

/* Form styling */
.card form label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #333;
}

.card form input,
.card form select,
.card form textarea {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 1rem;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 1rem;
}

.card form textarea {
    resize: vertical;
}

.card form .btn {
    background-color: #ff7a00;
    color: #fff;
    border: none;
    padding: 8px 18px;
    font-size: 0.9rem;
    border-radius: 20px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.3s;
}

.card form .btn:hover {
    background-color: #e56a00;
}

/* Service history layout */
.history-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.history-item {
    background: #fff;
    border: 1px solid #ff7a00;
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    align-items: center;
}

.history-item .info {
    flex: 1;
    min-width: 200px;
}

.history-item .info p {
    margin: 4px 0;
}

.history-item .status {
    font-weight: 600;
    color: #fff;
    background-color: #ff7a00;
    padding: 4px 12px;
    border-radius: 12px;
    text-align: center;
    min-width: 90px;
}

.history-item.completed .status {
    background-color: #28a745; /* green for completed */
}

.history-item.pending .status {
    background-color: #ffc107; /* yellow for pending */
}

@media (max-width: 600px) {
    .history-item {
        flex-direction: column;
        align-items: flex-start;
    }
    .history-item .status {
        margin-top: 10px;
    }
}
    </style>
</head>

<body>
    <div class="container">
    <h1>Maintenance Service</h1>

    <!-- Service Request Form -->
    <div class="card">
        <h2>Request a Maintenance Service</h2>
        <form>
            <label for="customer-name">Full Name</label>
            <input type="text" id="customer-name" name="customer-name" placeholder="Enter your full name" required>

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>

            <label for="panel-id">Solar Panel ID / Location</label>
            <input type="text" id="panel-id" name="panel-id" placeholder="Enter panel ID or location" required>

            <label for="service-type">Service Type</label>
            <select id="service-type" name="service-type" required>
                <option value="">Select service type</option>
                <option value="inspection">Inspection</option>
                <option value="repair">Repair</option>
                <option value="cleaning">Cleaning</option>
            </select>

            <label for="details">Additional Details</label>
            <textarea id="details" name="details" rows="4" placeholder="Enter any specific issues or notes"></textarea>

            <button type="submit" class="btn">Request Service</button>
        </form>
    </div>

    <!-- Service History -->
    <div class="history-list">
        <h2>Service History</h2>

        <div class="history-item completed">
            <div class="info">
                <p><strong>Date:</strong> 2025-08-10</p>
                <p><strong>Panel ID:</strong> Panel-101</p>
                <p><strong>Service Type:</strong> Cleaning</p>
                <p><strong>Remarks:</strong> Cleaned and inspected</p>
            </div>
            <div class="status">Completed</div>
        </div>

        <div class="history-item pending">
            <div class="info">
                <p><strong>Date:</strong> 2025-07-15</p>
                <p><strong>Panel ID:</strong> Panel-205</p>
                <p><strong>Service Type:</strong> Repair</p>
                <p><strong>Remarks:</strong> Waiting for parts</p>
            </div>
            <div class="status">Pending</div>
        </div>

        <div class="history-item completed">
            <div class="info">
                <p><strong>Date:</strong> 2025-06-20</p>
                <p><strong>Panel ID:</strong> Panel-310</p>
                <p><strong>Service Type:</strong> Inspection</p>
                <p><strong>Remarks:</strong> All systems normal</p>
            </div>
            <div class="status">Completed</div>
        </div>

    </div>
</div>
</body>