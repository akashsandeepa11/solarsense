<?php require APPROOT.'/views/inc/header.php'; ?>
    <!-- Top Navigation -->
    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>


    <div class="form-container">
        <div class="form-header">
            <h1>User Login</h1>
        </div>
        <form action="" method="POST">
 
            <!-- Email -->
             <div class="form-input-title">Email</div>
             <input type="text" name="email" id="email" class="email">
             <span class="form-invalid"></span>
             
            <!-- password -->
             <div class="form-input-title">Password</div>
             <input type="text" name="password" id="password" class="password">
             <span class="form-invalid"></span>

             <input type="submit" value="Register" class="form-btn">

        </form>
    </div>
<?php require APPROOT.'/views/inc/footer.php'; ?>
