<?php
class M_Notification
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Fetch notifications for a user, newest first.
     * Returns an array of associative arrays ready for the notification_panel component.
     *
     * @param int $user_id
     * @param int $limit   Max rows to return (default 10 for dropdown panel)
     * @return array
     */
    public function get_notifications(int $user_id, int $limit = 10): array
    {
        $this->db->query("
            SELECT
                notification_id AS id,
                type,
                title,
                message,
                created_at
            FROM notifications
            WHERE user_id = :user_id
            ORDER BY created_at DESC
            LIMIT :limit
        ");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':limit',   $limit, PDO::PARAM_INT);

        $rows = $this->db->resultSet();

        $result = [];
        foreach ($rows as $row) {
            $type = $row->type ?? 'info';
            $result[] = [
                'id'        => (int) $row->id,
                'type'      => $type,
                'icon'      => NOTIFICATION_ICONS[$type] ?? 'fas fa-bell',
                'title'     => $row->title,
                'message'   => $row->message,
                'timestamp' => $this->timeAgo($row->created_at),
            ];
        }
        return $result;
    }

    /**
     * Get all notifications for the full notifications page (no limit).
     *
     * @param int $user_id
     * @return array
     */
    public function get_all_notifications(int $user_id): array
    {
        return $this->get_notifications($user_id, 100);
    }

    /**
     * Delete all notifications for a user (Clear All button).
     *
     * @param int $user_id
     * @return bool
     */
    public function delete_all(int $user_id): bool
    {
        $this->db->query("DELETE FROM notifications WHERE user_id = :user_id");
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }

    /**
     * Insert a new notification.
     *
     * @param int    $user_id
     * @param string $type    error | warning | info | success
     * @param string $title
     * @param string $message
     * @return bool
     */
    public function add(int $user_id, string $type, string $title, string $message): bool
    {
        $this->db->query("
            INSERT INTO notifications (user_id, type, title, message)
            VALUES (:user_id, :type, :title, :message)
        ");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':type',    $type);
        $this->db->bind(':title',   $title);
        $this->db->bind(':message', $message);
        return $this->db->execute();
    }

    // ------------------------------------------------------------------ //
    //  Private helpers
    // ------------------------------------------------------------------ //

    /**
     * Convert a datetime string into a human-readable "time ago" string.
     *
     * @param string $datetime  MySQL datetime e.g. "2026-04-16 14:00:00"
     * @return string           e.g. "2 minutes ago", "3 hours ago", "Yesterday"
     */
    private function timeAgo(string $datetime): string
    {
        $now  = new DateTime();
        $then = new DateTime($datetime);
        $diff = $now->diff($then);

        if ($diff->y > 0) return $diff->y . ' year'   . ($diff->y  > 1 ? 's' : '') . ' ago';
        if ($diff->m > 0) return $diff->m . ' month'  . ($diff->m  > 1 ? 's' : '') . ' ago';
        if ($diff->d > 1) return $diff->d . ' days ago';
        if ($diff->d === 1) return 'Yesterday';
        if ($diff->h > 0) return $diff->h . ' hour'   . ($diff->h  > 1 ? 's' : '') . ' ago';
        if ($diff->i > 0) return $diff->i . ' minute' . ($diff->i  > 1 ? 's' : '') . ' ago';
        return 'Just now';
    }
}
?>
