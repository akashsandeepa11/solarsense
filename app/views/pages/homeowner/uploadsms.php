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
                    <label for="smsContent">CEB SMS Message</label>
                    <textarea 
                        id="smsContent" 
                        name="smsContent" 
                        class="form-control" 
                        rows="5" 
                        required></textarea>
                    <small class="text-secondary">Copy and paste the entire SMS message exactly as received</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Upload Message</button>
                    <button type="reset" class="btn btn-secondary">Clear</button>
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