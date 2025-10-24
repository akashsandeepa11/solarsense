<?php

/**
 * Toast Helper - Display toast notifications
 */

/**
 * Set a toast message in session
 * 
 * @param string $message - The message to display
 * @param string $type - Type of toast: 'success', 'error', 'warning', 'info'
 */
function setToast($message, $type = 'info'){
    $_SESSION['toast'] = [
        'message' => $message,
        'type' => $type
    ];
}

/**
 * Get and clear the toast message from session
 * 
 * @return array|null - Toast data or null if no toast
 */
function getToast(){
    if(isset($_SESSION['toast'])){
        $toast = $_SESSION['toast'];
        unset($_SESSION['toast']);
        return $toast;
    }
    return null;
}

/**
 * Display toast message if it exists
 * Call this in your header/layout view
 */
function displayToast(){
    $toast = getToast();
    if($toast):
        $bgColor = '';
        $textColor = '';
        switch($toast['type']){
            case 'success':
                $bgColor = '#d4edda';
                $textColor = '#155724';
                break;
            case 'error':
                $bgColor = '#f8d7da';
                $textColor = '#721c24';
                break;
            case 'warning':
                $bgColor = '#fff3cd';
                $textColor = '#856404';
                break;
            case 'info':
            default:
                $bgColor = '#d1ecf1';
                $textColor = '#0c5460';
                break;
        }

        

        ?>

        <div class="toast toast-<?php echo $toast['type']; ?>" id="toast" style="background-color: <?php echo $bgColor; ?>; color: <?php echo $textColor; ?>;">
            <span class="toast-message"><?php echo htmlspecialchars($toast['message']); ?></span>
            <button class="toast-close" onclick="closeToast()">×</button>
        </div>
        <script>
            function closeToast(){
                const toast = document.getElementById('toast');
                toast.classList.add('hide');
                setTimeout(() => toast.remove(), 300000);
            }
            
            // Auto-hide toast after 5 seconds
            setTimeout(closeToast, 5000);
        </script>
        <?php
    endif;
}

?>
