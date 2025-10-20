<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/installer_admin/team.css">

<div class="team-container">
    <!-- Header Section -->
    <div class="team-header mb-6">
        <div class="d-flex align-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Service Agents</h1>
                <p class="text-secondary">Manage your team of service agents and track their tasks</p>
            </div>
            <a href="<?php echo URLROOT; ?>/installeradmin/team/add_service_agent" class="btn btn-success" style="text-decoration: none;">
                <i class="fas fa-plus mr-2"></i>Add New Agent
            </a>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="team-filters mb-6 card p-4">
        <div class="row gap-4">
            <div class="col-md-4">
                <div class="form-group mb-0">
                    <label class="form-label">Search Agents</label>
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; z-index: 1;"></i>
                        <input 
                            type="text" 
                            class="form-control pl-5" 
                            placeholder="Search by name or email..."
                            id="searchAgents"
                        >
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <label class="form-label">Status</label>
                    <select class="form-control">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="on_leave">On Leave</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <label class="form-label">Workload</label>
                    <select class="form-control">
                        <option value="">All Workloads</option>
                        <option value="high">High (5+ tasks)</option>
                        <option value="medium">Medium (2-4 tasks)</option>
                        <option value="low">Low (0-1 tasks)</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2 d-flex align-end">
                <button class="btn btn-secondary w-100">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Teams Stats Section -->
    <div class="team-stats mb-6 row">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Total Agents</div>
                    <div class="stat-value">12</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Active</div>
                    <div class="stat-value">10</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Total Tasks</div>
                    <div class="stat-value">48</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-accent">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Pending Tasks</div>
                    <div class="stat-value">15</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Agents List -->
    <div class="team-list card">
        <div class="card-header border-bottom">
            <h3 class="card-title">Team Members</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="team-table">
                    <thead>
                        <tr>
                            <th>Agent</th>
                            <th>Contact</th>
                            <th>Assigned Tasks</th>
                            <th>Completed</th>
                            <th>Pending</th>
                            <th>Status</th>
                            <th>Last Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Agent 1 -->
                        <tr class="team-table-row">
                            <td>
                                <div class="agent-info d-flex align-center gap-3">
                                    <div class="agent-avatar">
                                        <img src="https://ui-avatars.com/api/?name=John+Doe&background=fe9630&color=fff" alt="John Doe">
                                    </div>
                                    <div class="agent-details">
                                        <div class="agent-name font-semibold">John Doe</div>
                                        <div class="agent-role text-secondary text-sm">Service Agent</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <div class="email text-sm">john@example.com</div>
                                    <div class="phone text-secondary text-sm">+94 77 123 4567</div>
                                </div>
                            </td>
                            <td>
                                <div class="task-count">
                                    <span class="badge bg-primary">5</span>
                                </div>
                            </td>
                            <td>
                                <div class="task-progress">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 60%"></div>
                                    </div>
                                    <div class="progress-text text-sm">3</div>
                                </div>
                            </td>
                            <td>
                                <div class="pending-tasks">
                                    <span class="badge bg-warning">2</span>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-active">
                                    <i class="fas fa-circle text-success mr-1"></i>Active
                                </span>
                            </td>
                            <td>
                                <div class="last-active text-secondary text-sm">Today, 2:30 PM</div>
                            </td>
                            <td>
                                <div class="actions-menu d-flex gap-2 justify-center">
                                    <a href="<?php echo URLROOT; ?>/installeradmin/team/agent_details/1" class="btn-icon" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-icon btn-icon-danger" title="Remove">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Agent 2 -->
                        <tr class="team-table-row">
                            <td>
                                <div class="agent-info d-flex align-center gap-3">
                                    <div class="agent-avatar">
                                        <img src="https://ui-avatars.com/api/?name=Sarah+Smith&background=22c55e&color=fff" alt="Sarah Smith">
                                    </div>
                                    <div class="agent-details">
                                        <div class="agent-name font-semibold">Sarah Smith</div>
                                        <div class="agent-role text-secondary text-sm">Senior Agent</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <div class="email text-sm">sarah@example.com</div>
                                    <div class="phone text-secondary text-sm">+94 77 234 5678</div>
                                </div>
                            </td>
                            <td>
                                <div class="task-count">
                                    <span class="badge bg-primary">8</span>
                                </div>
                            </td>
                            <td>
                                <div class="task-progress">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 75%"></div>
                                    </div>
                                    <div class="progress-text text-sm">6</div>
                                </div>
                            </td>
                            <td>
                                <div class="pending-tasks">
                                    <span class="badge bg-warning">2</span>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-active">
                                    <i class="fas fa-circle text-success mr-1"></i>Active
                                </span>
                            </td>
                            <td>
                                <div class="last-active text-secondary text-sm">2 hours ago</div>
                            </td>
                            <td>
                                <div class="actions-menu d-flex gap-2 justify-center">
                                    <a href="<?php echo URLROOT; ?>/installeradmin/team/agent_details/2" class="btn-icon" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-icon btn-icon-danger" title="Remove">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Agent 3 -->
                        <tr class="team-table-row">
                            <td>
                                <div class="agent-info d-flex align-center gap-3">
                                    <div class="agent-avatar">
                                        <img src="https://ui-avatars.com/api/?name=Mike+Johnson&background=f59e0b&color=fff" alt="Mike Johnson">
                                    </div>
                                    <div class="agent-details">
                                        <div class="agent-name font-semibold">Mike Johnson</div>
                                        <div class="agent-role text-secondary text-sm">Service Agent</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <div class="email text-sm">mike@example.com</div>
                                    <div class="phone text-secondary text-sm">+94 77 345 6789</div>
                                </div>
                            </td>
                            <td>
                                <div class="task-count">
                                    <span class="badge bg-primary">3</span>
                                </div>
                            </td>
                            <td>
                                <div class="task-progress">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 33%"></div>
                                    </div>
                                    <div class="progress-text text-sm">1</div>
                                </div>
                            </td>
                            <td>
                                <div class="pending-tasks">
                                    <span class="badge bg-warning">2</span>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-on-leave">
                                    <i class="fas fa-circle text-warning mr-1"></i>On Leave
                                </span>
                            </td>
                            <td>
                                <div class="last-active text-secondary text-sm">Yesterday</div>
                            </td>
                            <td>
                                <div class="actions-menu d-flex gap-2 justify-center">
                                    <a href="<?php echo URLROOT; ?>/installeradmin/team/agent_details/3" class="btn-icon" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-icon btn-icon-danger" title="Remove">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Agent 4 -->
                        <tr class="team-table-row">
                            <td>
                                <div class="agent-info d-flex align-center gap-3">
                                    <div class="agent-avatar">
                                        <img src="https://ui-avatars.com/api/?name=Lisa+Brown&background=00bcd4&color=fff" alt="Lisa Brown">
                                    </div>
                                    <div class="agent-details">
                                        <div class="agent-name font-semibold">Lisa Brown</div>
                                        <div class="agent-role text-secondary text-sm">Service Agent</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <div class="email text-sm">lisa@example.com</div>
                                    <div class="phone text-secondary text-sm">+94 77 456 7890</div>
                                </div>
                            </td>
                            <td>
                                <div class="task-count">
                                    <span class="badge bg-primary">6</span>
                                </div>
                            </td>
                            <td>
                                <div class="task-progress">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 50%"></div>
                                    </div>
                                    <div class="progress-text text-sm">3</div>
                                </div>
                            </td>
                            <td>
                                <div class="pending-tasks">
                                    <span class="badge bg-warning">3</span>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-active">
                                    <i class="fas fa-circle text-success mr-1"></i>Active
                                </span>
                            </td>
                            <td>
                                <div class="last-active text-secondary text-sm">30 min ago</div>
                            </td>
                            <td>
                                <div class="actions-menu d-flex gap-2 justify-center">
                                    <a href="<?php echo URLROOT; ?>/installeradmin/team/agent_details/4" class="btn-icon" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-icon btn-icon-danger" title="Remove">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="card-footer d-flex align-center justify-between">
            <div class="pagination-info text-secondary text-sm">
                Showing 1 to 4 of 12 results
            </div>
            <div class="pagination d-flex gap-2">
                <button class="btn btn-sm btn-secondary">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-sm btn-primary">1</button>
                <button class="btn btn-sm btn-secondary">2</button>
                <button class="btn btn-sm btn-secondary">3</button>
                <button class="btn btn-sm btn-secondary">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>