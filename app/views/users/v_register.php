<?php require APPROOT.'/views/inc/header.php'; ?>
    <!-- Top Navigation -->
    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>


    <div class="form-container">
        <div class="form-header">
            <h1>User sign up</h1>
        </div>
        <form action="" method="POST">
            <!-- name -->
             <div class="form-input-title">Name</div>
             <input type="text" name="name" id="name" class="name">
             <span class="form-invalid"><?php echo $data['name_err']?></span>
             
            <!-- Email -->
             <div class="form-input-title">Email</div>
             <input type="text" name="email" id="email" class="email">
             <span class="form-invalid"><?php echo $data['email_err']?></span>
             
            <!-- password -->
             <div class="form-input-title">Password</div>
             <input type="text" name="password" id="password" class="password">
             <span class="form-invalid"><?php echo $data['password_err']?></span>
             
            <!-- Confirm Password -->
             <div class="form-input-title">Confirm Password</div>
             <input type="text" name="confirm_password" id="confirm_password" class="confirm_password">
             <span class="form-invalid"><?php echo $data['confirm_password_err']?></span>

             <input type="submit" value="Register" class="form-btn">

        </form>
    </div>
<?php require APPROOT.'/views/inc/footer.php'; ?>
