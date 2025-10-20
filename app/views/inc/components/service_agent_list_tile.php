<?php
/**
 * Service Agent List Tile Component
 * 
 * Specialized list item component for displaying service agent information
 * with contact details, status, and task tracking
 * 
 * @param array $config Configuration array:
 *   - id (integer): Agent ID
 *   - name (string): Agent name
 *   - role (string): Agent role/title
 *   - avatar (string): Avatar image URL
 *   - email (string): Email address
 *   - phone (string): Phone number
 *   - assigned (integer): Number of assigned tasks
 *   - completed (integer): Number of completed tasks
 *   - pending (integer): Number of pending tasks
 *   - status (string): Agent status (Active, On Leave, Inactive)
 *   - last_active (string): Last active time
 *   - actions (array, optional): Action buttons
 */

if (!isset($config)) {
    $config = [];
}

$id = $config['id'] ?? '';
$name = $config['name'] ?? 'Agent Name';
$role = $config['role'] ?? 'Service Agent';
$avatar = $config['avatar'] ?? '';
$email = $config['email'] ?? '';
$phone = $config['phone'] ?? '';
$assigned = $config['assigned'] ?? '0';
$completed = $config['completed'] ?? '0';
$pending = $config['pending'] ?? '0';
$status = $config['status'] ?? 'Active';
$lastActive = $config['last_active'] ?? '';
$actions = $config['actions'] ?? [];

// Calculate completion percentage
$completionPercent = $assigned > 0 ? intval(($completed / $assigned) * 100) : 0;

// Determine status badge color
$statusClass = match($status) {
    'Active' => 'status-active',
    'On Leave' => 'status-on-leave',
    'Inactive' => 'status-inactive',
    default => 'status-active'
};
?>

<div class="service-agent-tile card mb-3">
    <div class="card-body p-4">
        <div class="d-flex align-start gap-4">
            <!-- Avatar -->
            <div class="agent-avatar">
                <img src="<?php echo htmlspecialchars($avatar); ?>" 
                     alt="<?php echo htmlspecialchars($name); ?>"
                     class="avatar-image">
            </div>

            <!-- Main Content -->
            <div class="agent-tile-content flex-1">
                <!-- Name and Status -->
                <div class="d-flex align-center justify-between mb-3">
                    <div>
                        <h4 class="mb-1 font-semibold text-lg"><?php echo htmlspecialchars($name); ?></h4>
                        <p class="text-secondary text-sm mb-0"><?php echo htmlspecialchars($role); ?></p>
                    </div>
                    <span class="status-badge <?php echo htmlspecialchars($statusClass); ?>">
                        <i class="fas fa-circle mr-1"></i><?php echo htmlspecialchars($status); ?>
                    </span>
                </div>

                <!-- Contact Info -->
                <div class="contact-info mb-3">
                    <div class="email text-sm font-medium mb-1"><?php echo htmlspecialchars($email); ?></div>
                    <div class="phone text-secondary text-sm"><?php echo htmlspecialchars($phone); ?></div>
                </div>

                <!-- Task Stats -->
                <div class="agent-stats d-flex gap-4 mb-3">
                    <div class="stat-item">
                        <div class="stat-label text-xs text-gray-500 font-medium mb-1">Assigned</div>
                        <div class="stat-value text-lg font-bold text-primary"><?php echo htmlspecialchars($assigned); ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label text-xs text-gray-500 font-medium mb-1">Completed</div>
                        <div class="task-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo $completionPercent; ?>%"></div>
                            </div>
                            <div class="progress-text text-sm"><?php echo htmlspecialchars($completed); ?>/<?php echo htmlspecialchars($assigned); ?></div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label text-xs text-gray-500 font-medium mb-1">Pending</div>
                        <div class="stat-value text-lg font-bold text-warning"><?php echo htmlspecialchars($pending); ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label text-xs text-gray-500 font-medium mb-1">Last Active</div>
                        <div class="stat-value text-sm"><?php echo htmlspecialchars($lastActive); ?></div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <?php if (!empty($actions)): ?>
                <div class="agent-tile-actions d-flex flex-column gap-2">
                    <?php foreach ($actions as $action): ?>
                        <?php
                        $actionClass = isset($action['class']) ? $action['class'] : '';
                        $actionOnclick = isset($action['onclick']) ? $action['onclick'] : '';
                        $actionUrl = isset($action['url']) ? $action['url'] : '';
                        ?>
                        <?php if (!empty($actionUrl)): ?>
                            <a href="<?php echo htmlspecialchars($actionUrl); ?>" 
                               class="btn-icon <?php echo htmlspecialchars($actionClass); ?>"
                               title="<?php echo htmlspecialchars($action['label']); ?>">
                                <i class="<?php echo htmlspecialchars($action['icon']); ?>"></i>
                            </a>
                        <?php else: ?>
                            <button class="btn-icon <?php echo htmlspecialchars($actionClass); ?>" title="<?php echo htmlspecialchars($action['label']); ?>" <?php echo $actionOnclick; ?>>
                                <i class="<?php echo htmlspecialchars($action['icon']); ?>"></i>
                            </button>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
