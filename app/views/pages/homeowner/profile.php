<?php require APPROOT.'layout_upper.php'; ?>
 
<?php
// --- DUMMY DATA ---
// This data would be fetched from your database in a real controller.
$homeowner_data = [
    'full_name' => 'Akash Sandeepa',
    'email' => 'akashsandeepa@email.com',
    'district' => 'Colombo',
    'system_capacity' => 5.5,
    'panel_tilt' => 15,
    'panel_azimuth' => 'South-facing',
    'current_installer_id' => 2,
];

$installers = [
    ['id' => 1, 'company_name' => 'Eco Power Solutions'],
    ['id' => 2, 'company_name' => 'Lanka Solar Experts'],
    ['id' => 3, 'company_name' => 'Green Energy Systems'],
    ['id' => 4, 'company_name' => 'SunForce Sri Lanka'],
];

// --- FORM HANDLING LOGIC ---
$update_success_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs and update the database here.
    // For this example, we'll just show a success message.
    $update_success_message = "Profile updated successfully!";
}

// In your actual project, your header would be included here.
// For example: require APPROOT . '/views/inc/header.php';
?>
 
 <!-- Main Content Area --> 
    <div class="flex-1 flex flex-col overflow-hidden">
        
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
                            <p class="text-sm font-semibold text-[#212121]"><?php echo htmlspecialchars($homeowner_data['full_name']); ?></p>
                            <p class="text-xs text-gray-500">Solar Owner</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Profile Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 md:p-8">
            <div class="container mx-auto">
                
                <!-- Success Message -->
                <?php if (!empty($update_success_message)): ?>
                    <div class="bg-[#4CAF50]/10 border-l-4 border-[#4CAF50] text-[#0a6b3d] p-4 mb-6 rounded-md" role="alert">
                        <p class="font-bold">Success</p>
                        <p><?php echo $update_success_message; ?></p>
                    </div>
                <?php endif; ?>

                <!-- Profile Form -->
                <form action="" method="POST">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <!-- Column 1: Personal & Installer Info -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Personal Information Card -->
                            <div class="bg-[#FFFFFF] shadow-md rounded-lg p-6">
                                <h2 class="text-xl font-semibold text-[#212121] mb-4">Personal Information</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="full_name" class="block text-sm font-medium text-gray-600">Full Name</label>
                                        <input type="text" name="full_name" id="full_name" value="<?php echo htmlspecialchars($homeowner_data['full_name']); ?>" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#FE9630] focus:border-[#FE9630]">
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-600">Email Address</label>
                                        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($homeowner_data['email']); ?>" class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm" readonly disabled>
                                    </div>
                                    <div>
                                        <label for="district" class="block text-sm font-medium text-gray-600">District</label>
                                        <input type="text" name="district" id="district" value="<?php echo htmlspecialchars($homeowner_data['district']); ?>" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#FE9630] focus:border-[#FE9630]">
                                    </div>
                                </div>
                            </div>

                            <!-- My Installer Card -->
                            <div class="bg-[#FFFFFF] shadow-md rounded-lg p-6">
                                <h2 class="text-xl font-semibold text-[#212121] mb-4">My Installer</h2>
                                <p class="text-sm text-gray-600 mb-4">Select your solar system installer from this list. This allows them to monitor your system for faults and provide proactive service.</p>
                                <div>
                                    <label for="installer_id" class="block text-sm font-medium text-gray-600">Select Installer</label>
                                    <select name="installer_id" id="installer_id" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#FE9630] focus:border-[#FE9630]">
                                        <option value="">-- No Installer Selected --</option>
                                        <?php foreach ($installers as $installer): ?>
                                            <option value="<?php echo $installer['id']; ?>" <?php echo ($installer['id'] == $homeowner_data['current_installer_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($installer['company_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Column 2: Solar System Details -->
                        <div class="lg:col-span-1">
                            <div class="bg-[#FFFFFF] shadow-md rounded-lg p-6">
                                <h2 class="text-xl font-semibold text-[#212121] mb-4">Solar System Details</h2>
                                <p class="text-sm text-gray-600 mb-4">This information is set by your installer and cannot be changed.</p>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">System Capacity</p>
                                        <p class="text-lg font-semibold text-[#212121]"><?php echo htmlspecialchars($homeowner_data['system_capacity']); ?> kWp</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Panel Tilt</p>
                                        <p class="text-lg font-semibold text-[#212121]"><?php echo htmlspecialchars($homeowner_data['panel_tilt']); ?>°</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Panel Azimuth</p>
                                        <p class="text-lg font-semibold text-[#212121]"><?php echo htmlspecialchars($homeowner_data['panel_azimuth']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-[#FE9630] text-white font-semibold rounded-lg shadow-md hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FE9630] transition-colors">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>


 
<?php require APPROOT.'layout_lower.php'; ?>
