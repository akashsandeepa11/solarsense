<?php require APPROOT.'/views/inc/header.php'; ?>
 
<div class="h-screen flex ">

  <!-- Left Section -->
  <div class="w-1/2 bg-orange-500 flex items-center justify-center text-white p-10">
    <div>
      <h1 class="text-4xl font-bold mb-2">SolarSense</h1>
      <p class="mb-6">Unlock the true potential of your solar investment.</p>
      <button class="bg-white text-orange-600 px-4 py-2 rounded-full font-semibold">Read More</button>
    </div>
  </div>

  <!-- Right Section -->
  <div class="w-1/2 bg-white flex items-center justify-center p-10">
    <div class="w-full max-w-sm">
      <h2 class="text-2xl font-bold mb-1">Welcome to SolarSense</h2>
      <p class="text-gray-500 mb-6">Sign in to access your dashboard.</p>

      <input type="email" placeholder="Email Address" class="w-full px-4 py-2 mb-4 border rounded-lg focus:outline-none">
      <input type="password" placeholder="Password" class="w-full px-4 py-2 mb-4 border rounded-lg focus:outline-none">

      <button class="w-full bg-orange-500 text-white py-2 rounded-lg font-semibold">Login</button>

      <p class="text-center mt-4 text-gray-500 text-sm">Forgot Password</p>
    </div>
  </div>

</div>
<?php require APPROOT.'/views/inc/footer.php'; ?>

