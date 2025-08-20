    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/homeowner/service.css">

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