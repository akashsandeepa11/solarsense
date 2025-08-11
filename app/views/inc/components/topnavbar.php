<style>
    /* Custom styles for the navbar component */
    .navbar-search-wrapper {
        position: relative;
        width: 350px;
    }
    .navbar-search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    .navbar-search-input {
        background-color: #f5f5f5; /* bg-background */
        border-color: transparent;
        border-radius: 0.75rem; /* 12px -> rounded-xl */
        padding-left: 2.5rem;
    }
    .navbar-search-input:focus {
        background-color: #ffffff; /* bg-surface */
        border-color: var(--color-primary, #fe9630);
        box-shadow: 0 0 0 3px rgba(254, 150, 48, 0.25);
    }
    .navbar-user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 9999px; /* rounded-full */
    }
    .navbar-icon-btn {
        font-size: 1.25rem; /* text-xl */
        color: #6c757d;
    }
</style>

<header class="navbar d-flex align-items-center justify-between p-3">
    <!-- Left Side -->
    <div class="d-flex align-center">
        <button class="btn border-0 mr-3" id="sidebar-toggle-btn">
            <i class="fa-solid fa-bars"></i>
        </button>
        <h4 class="font-bold mb-0">Dashboard</h4>
    </div>

    <!-- Center Search -->
    <div class="navbar-search-wrapper">
        <i class="fa-solid fa-magnifying-glass navbar-search-icon"></i>
        <input type="text" class="form-control navbar-search-input" placeholder="Search...">
    </div>

    <!-- Right Side -->
    <div class="d-flex align-items-center mr-8">
        <a href="#" class="btn border-0 navbar-icon-btn mr-2"><i class="fa-regular fa-moon"></i></a>
        <a href="#" class="btn border-0 navbar-icon-btn mr-3"><i class="fa-regular fa-bell"></i></a>

        <div class="d-flex align-items-center">
            <img src="https://i.pravatar.cc/40?u=akash" alt="User Avatar" class="navbar-user-avatar">
            <div class="ml-3">
                <div class="font-semibold">Akash Sandeepa</div>
                <div class="text-sm" style="color: #6c757d;">SOLAR OWNER</div>
            </div>
        </div>
    </div>
</header>
