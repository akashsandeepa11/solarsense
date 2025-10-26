<!-- Loading Spinner Overlay -->
<div id="loadingSpinner" class="loading-spinner-overlay">
    <div class="loading-spinner-container">
        <div class="solar-panel-spinner">
            <div class="solar-panel">
                <div class="panel-grid">
                    <div class="panel-cell"></div>
                    <div class="panel-cell"></div>
                    <div class="panel-cell"></div>
                    <div class="panel-cell"></div>
                    <div class="panel-cell"></div>
                    <div class="panel-cell"></div>
                </div>
                <div class="sun-icon">
                    <i class="fas fa-sun"></i>
                </div>
            </div>
        </div>
        <p class="loading-text text-primary">Powering Up...</p>
    </div>
</div>

<style>
/* Loading Spinner Overlay */
.loading-spinner-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(248, 250, 252, 0.5);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99999;
    opacity: 1;
    transition: opacity 0.3s ease;
}

.loading-spinner-overlay.fade-out {
    opacity: 0;
    pointer-events: none;
}

.loading-spinner-container {
    text-align: center;
}

/* Solar Panel Spinner */
.solar-panel-spinner {
    position: relative;
    width: 100px;
    height: 100px;
    margin: 0 auto 1.5rem;
}

.solar-panel {
    position: relative;
    width: 100%;
    height: 100%;
    animation: tilt 3s ease-in-out infinite;
}

@keyframes tilt {
    0%, 100% { transform: perspective(400px) rotateY(-15deg) rotateX(10deg); }
    50% { transform: perspective(400px) rotateY(15deg) rotateX(-10deg); }
}

.panel-grid {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    border: 3px solid #fe9630;
    border-radius: 8px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: repeat(2, 1fr);
    gap: 3px;
    padding: 6px;
    box-shadow: 0 10px 30px rgba(254, 150, 48, 0.3);
    position: relative;
    margin: 0 auto;
}

.panel-cell {
    background: linear-gradient(135deg, #2563eb 0%, #60a5fa 100%);
    border-radius: 2px;
    animation: energize 1.5s ease-in-out infinite;
}

.panel-cell:nth-child(1) { animation-delay: 0s; }
.panel-cell:nth-child(2) { animation-delay: 0.1s; }
.panel-cell:nth-child(3) { animation-delay: 0.2s; }
.panel-cell:nth-child(4) { animation-delay: 0.3s; }
.panel-cell:nth-child(5) { animation-delay: 0.4s; }
.panel-cell:nth-child(6) { animation-delay: 0.5s; }

@keyframes energize {
    0%, 100% {
        background: linear-gradient(135deg, #2563eb 0%, #60a5fa 100%);
        box-shadow: inset 0 0 5px rgba(254, 150, 48, 0.3);
    }
    50% {
        background: linear-gradient(135deg, #60a5fa 0%, #93c5fd 100%);
        box-shadow: inset 0 0 10px rgba(254, 150, 48, 0.8);
    }
}

/* Sun Icon */
.sun-icon {
    position: absolute;
    top: -15px;
    right: -15px;
    font-size: 2rem;
    color: #fbbf24;
    animation: pulse-sun 2s ease-in-out infinite;
    filter: drop-shadow(0 0 10px rgba(251, 191, 36, 0.6));
}

@keyframes pulse-sun {
    0%, 100% {
        transform: scale(1);
        color: #fbbf24;
    }
    50% {
        transform: scale(1.1);
        color: #fcd34d;
    }
}

/* Energy rays from sun */
.sun-icon::before {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    animation: rotate 4s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.loading-text {
    /* color: #1e40af; */
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0;
    animation: fade-text 2s ease-in-out infinite;
}

@keyframes fade-text {
    0%, 100% { opacity: 0.6; }
    50% { opacity: 1; }
}

/* Power indicator dots */
.loading-text::after {
    content: '';
    animation: dots 1.5s steps(4) infinite;
}

@keyframes dots {
    0%, 20% { content: ''; }
    40% { content: '.'; }
    60% { content: '..'; }
    80%, 100% { content: '...'; }
}

/* Dark theme support */
@media (prefers-color-scheme: dark) {
    .loading-spinner-overlay {
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
    
    .loading-text {
        color: #60a5fa;
    }
}

/* Mobile responsive */
@media (max-width: 768px) {
    .solar-panel-spinner {
        width: 80px;
        height: 80px;
    }
    
    .panel-grid {
        width: 64px;
        height: 64px;
    }
    
    .sun-icon {
        font-size: 1.5rem;
        top: -12px;
        right: -12px;
    }
    
    .loading-text {
        font-size: 1rem;
    }
}
</style>

<script>
// Hide loading spinner when page is fully loaded
window.addEventListener('load', function() {
    const spinner = document.getElementById('loadingSpinner');
    if (spinner) {
        spinner.classList.add('fade-out');
        setTimeout(function() {
            spinner.style.display = 'none';
        }, 300);
    }
});

// Show loading spinner function (can be called from anywhere)
function showLoadingSpinner() {
    const spinner = document.getElementById('loadingSpinner');
    if (spinner) {
        spinner.style.display = 'flex';
        spinner.classList.remove('fade-out');
    }
}

// Hide loading spinner function (can be called from anywhere)
function hideLoadingSpinner() {
    const spinner = document.getElementById('loadingSpinner');
    if (spinner) {
        spinner.classList.add('fade-out');
        setTimeout(function() {
            spinner.style.display = 'none';
        }, 300);
    }
}

// Show spinner on form submissions
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            // Don't show spinner for forms with data-no-spinner attribute
            if (!form.hasAttribute('data-no-spinner')) {
                showLoadingSpinner();
            }
        });
    });
});

// Show spinner on page navigation (links)
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('a:not([target="_blank"]):not([href^="#"]):not([data-no-spinner])');
    links.forEach(function(link) {
        link.addEventListener('click', function(e) {
            // Only show spinner for same-origin links
            if (link.hostname === window.location.hostname) {
                showLoadingSpinner();
            }
        });
    });
});
</script>
