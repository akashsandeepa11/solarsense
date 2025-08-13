<?php
// Step control and data retrieval
$step = 1; // default step 1

//after very first submit this work
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['previous'])) {
        // User clicked Previous button
        $step = 1;
    } elseif (isset($_POST['step'])) {
        $step = (int) $_POST['step'];
    }
}

// Get all submitted values to preserve on going back/forth
$name = $_POST['name'] ?? '';
$NIC = $_POST['NIC'] ?? '';
$phone = $_POST['phone'] ?? '';
$tilt = $_POST['tilt'] ?? '';
$capacity = $_POST['capacity'] ?? '';
$azimuth = $_POST['azimuth'] ?? '';

$email = $_POST['email'] ?? '';
$district= $_POST['district'] ?? '';
?>





<?php if ($step === 1): ?>

    <div class="text-center">   
        <h4>Step 1</h4> 
        <h2>Customer Details</h2>
    </div>


    <div  class="p-4 bg-surface w-75" style="display: block; margin: 0 auto;">

        <form method="post" action="">
            <input type="hidden" name="step" value="2">
            Name: <input type="text"   class="form-control" name="name" required value="<?php echo htmlspecialchars($name); ?>"><br>
            NIC:<input type="NIC" class="form-control" name="NIC" required value="<?php echo htmlspecialchars($NIC); ?>"><br>
           

            District: <select class="form-control" name="district" required>
                <option value="">--Select District--</option>

            
            <?php
            
            $districts = [
                "Colombo","Gampaha","Kalutara","Kandy","Matale","Nuwara Eliya",
                "Galle","Matara","Hambantota","Jaffna","Kilinochchi","Mannar",
                "Vavuniya","Mullaitivu","Batticaloa","Ampara","Trincomalee",
                "Kurunegala","Puttalam","Anuradhapura","Polonnaruwa",
                "Badulla","Moneragala","Ratnapura","Kegalle"
            ];
            foreach($districts as $d) {
                $selected = ($district == $d) ? "selected" : "";
                echo "<option value=\"$d\" $selected>$d</option>";
            }
            ?>
        </select><br>


            Phone Number: <input type="text" class="form-control"name="phone" required maxlength="10" pattern="0\d{9}" value="<?php echo htmlspecialchars($phone); ?>"><br>
            
            Email Address<input type="text" class="form-control"name="email" required value="<?php echo htmlspecialchars($email); ?>"><br>
          
            <button class="btn btn-primary rounded-xl"  style="display: block; margin: 5px auto;">Next</button>
        </form>

     </div>

<?php elseif ($step === 2): ?>

    <div class="text-center">   
        <h4>Step 2</h4> 
        <h2>Solar Panel Details</h2>
    </div>


    <div  class="p-4 bg-surface w-75" style="display: block; margin: 0 auto;">

        <form method="post" action="save.php">
            <!-- Pass customer data again as hidden inputs -->
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <input type="hidden" name="NIC" value="<?php echo htmlspecialchars($NIC); ?>">
            <input type="hidden" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <input type="hidden" name="district" value="<?php echo htmlspecialchars($district); ?>">


            <input type="hidden" name="step" value="2">

            Tilt(degrees):<input type="text" class="form-control" name="Tilt" required value="<?php echo htmlspecialchars($tilt); ?>"><br>
            Capacity (kW): <input type="number" class="form-control" name="capacity" required value="<?php echo htmlspecialchars($capacity); ?>"><br>
            Azimuth(degrees): <input type="number" class="form-control" name="Azimuth" required value="<?php echo htmlspecialchars($Azimuth); ?>"><br>

            <!-- Previous button sends POST back here -->
            <button type="submit" name="previous" value="1" formaction="/solarsense/installer/add_customer" formnovalidate class="btn btn-primary rounded-xl" style="display: block; margin: 0 auto;">Previous</button>

            <!--  <button type="submit" name="previous" value="1" formaction="<?php echo $_SERVER['PHP_SELF']; ?>"> Previous</button>     -->

            <button  name="save" value="1"  class="btn btn-primary rounded-xl" style="display: block; margin: 5px auto;">Save</button>
        </form>
    </div>

<?php endif; ?>




