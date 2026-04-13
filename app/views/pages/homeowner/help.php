<div class="content-area" style="padding: 2rem;">
    <?php
    $config = [
        'title' => 'System Support & Help',
        'description' => 'Report technical issues or request assistance with your dashboard.',
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <div class="card shadow-sm rounded-xl" style="max-width: 800px; margin: 0 auto;">
        <div class="card-body" style="padding: 2.5rem;">
            <div class="text-center mb-6">
                <i class="fas fa-headset text-primary" style="font-size: 3rem; opacity: 0.8;"></i>
                <h2 class="mt-4">What can we help you with?</h2>
                <p class="text-secondary">Please describe the issue you are facing in detail.</p>
            </div>

            <form action="<?php echo URLROOT; ?>/homeowner/help" method="POST" class="help-form">
                <div class="mb-4">
                    <label class="form-label font-bold">Subject / Issue Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g., Inverter sync error on dashboard" required>
                </div>

                <div class="mb-4">
                    <label class="form-label font-bold">Detailed Description</label>
                    <textarea name="notes" class="form-control" rows="5" placeholder="Explain the problem..." required></textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-6">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>