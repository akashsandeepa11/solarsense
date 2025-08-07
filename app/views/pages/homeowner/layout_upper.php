<?php require APPROOT.'/views/inc/header.php'; ?>
    
    <!-- Navbar/Top Bar Component -->
    <header class="h-16 bg-[#FFFFFF] border-b border-gray-200 flex items-center justify-between px-6">
        <div class="flex items-center">
            <button class="sm:hidden mr-4 text-gray-600">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <h1 class="text-xl font-semibold text-[#212121]">My Profile</h1>
        </div>
        <div class="flex items-center space-x-6">
            <div class="relative">
                <i class="fa-solid fa-search text-gray-500 absolute top-1/2 left-3 -translate-y-1/2"></i>
                <input type="text" placeholder="Search" class="pl-10 pr-4 py-2 w-64 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FE9630]">
            </div>
            <div class="flex items-center space-x-4">
                 <button class="text-gray-600 hover:text-[#FE9630]">
                    <i class="fa-solid fa-bell text-xl"></i>
                </button>
                <div class="flex items-center">
                    <img class="h-10 w-10 rounded-full object-cover" src="https://placehold.co/100x100/FE9630/FFFFFF?text=AS" alt="User Avatar">
                    <div class="ml-3 text-left">
                        <p class="text-sm font-semibold text-[#212121]">Akash Sandeepa</p>
                        <p class="text-xs text-gray-500">Solar Owner</p>
                    </div>
                </div>
            </div>
        </div>
    </header>
<body>

 
