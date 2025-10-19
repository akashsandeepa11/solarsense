<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/homeowner/uploadsms.css">

<div class="upload-sms-container">
    <div class="card">
        <div class="card-header">
            <h2>Upload CEB Message</h2>
            <p class="text-secondary">Please paste the SMS message you received from CEB below</p>
        </div>

        <div class="card-body">
            <form id="smsUploadForm" method="POST">
                <div class="form-group">
                    <?php 
                    $textareaConfig = [
                        'id' => 'smsContent',
                        'name' => 'smsContent',
                        'label' => 'CEB SMS Message',
                        'value' => $data['smsContent'] ?? '',
                        'error' => $data['smsContent_err'] ?? '',
                        'icon' => 'fas fa-message',
                        'rows' => 5,
                        'placeholder' => 'Copy and paste the entire SMS message exactly as received',
                        'required' => true
                    ];
                    require APPROOT . '/views/inc/components/textarea_field.php';
                    ?>
                    <small class="text-secondary d-block mt-2">Copy and paste the entire SMS message exactly as received</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload mr-2"></i>Upload Message
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-redo mr-2"></i>Clear
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Instructions Panel -->
    <div class="instructions-card">
        <h3>How to Upload CEB Messages</h3>
        <ol>
            <li>Open the SMS message from CEB on your phone</li>
            <li>Select and copy the entire message</li>
            <li>Paste the message in the text box above</li>
            <li>Click "Upload Message" to save</li>
        </ol>
        <div class="note">
            <i class="fas fa-info-circle"></i>
            <p>Your uploaded messages help us track your electricity consumption and provide better insights about your solar system performance.</p>
        </div>
    </div>
</div>

<script>
document.getElementById('smsUploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get the message content
    const smsContent = document.getElementById('smsContent').value;
    
    if (smsContent.trim() === '') {
        alert('Please enter the SMS message');
        return;
    }
    
    // Just show success message and clear form for now
    alert('Message received');
    this.reset();
});