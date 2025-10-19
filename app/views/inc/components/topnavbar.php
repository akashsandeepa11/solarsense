<!-- Linked styles -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar.css">
<!-- Ensure no default body margin leaves space above the navbar -->
<style>
    html, body { margin: 0; padding: 0; }
</style>

<header id="main-topnav" class="navbar d-flex align-items-center justify-between p-3"
    style="position:sticky; top:0; left:0; right:0; z-index:1100; background:var(--page-bg, #fff);">
    <!-- Left Side -->
    <div class="d-flex align-center">
        <button class="btn border-0 mr-3" id="sidebar-toggle-btn">
            <i class="fas fa-solid fa-bars"></i>
        </button>
    </div>

    <!-- Right Side -->
    <div class="d-flex align-items-center mr-8">
        <a href="#" class="btn border-0 navbar-icon-btn mr-3"><i class="fas fa-regular fa-bell"></i></a>

        <div class="d-flex align-items-center">
            <img src="https://i.pravatar.cc/40?u=akash" alt="User Avatar" class="navbar-user-avatar">
            <div class="ml-3">
                <div class="font-semibold">Akash Sandeepa</div>
                <div class="text-sm" style="color: #6c757d;">SOLAR OWNER</div>
            </div>
        </div>
    </div>
</header>
<script>
// Ensure page content isn't hidden behind the sticky navbar
;(function(){
    const nav = document.getElementById('main-topnav');
    if (!nav) return;
    
    // Run on load and on resize
    if (document.readyState === 'complete' || document.readyState === 'interactive') adjustBodyPadding();
    window.addEventListener('load', adjustBodyPadding);
    window.addEventListener('resize', adjustBodyPadding);
})();
</script>
