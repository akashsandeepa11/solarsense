<?php
// performer_list (operation_manager)
// Expects $title and $performers array of ['name'=>..., 'score'=>...]
?>
<div class="performer-list">
    <h4 class="font-semibold mb-2"><?php echo htmlspecialchars($title ?? ''); ?></h4>
    <ul class="list-none m-0 p-0">
        <?php foreach ((array) $performers as $p): ?>
            <?php
                $name = $p['name'] ?? $p['fullname'] ?? '—';
                $score = $p['score'] ?? $p['performance'] ?? $p['score_value'] ?? null;
            ?>
            <li class="py-1 border-b d-flex justify-between items-center">
                <span class="text-sm"><?php echo htmlspecialchars($name); ?></span>
                <span class="text-sm text-muted"><?php echo htmlspecialchars($score !== null ? (string) $score : '-'); ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
