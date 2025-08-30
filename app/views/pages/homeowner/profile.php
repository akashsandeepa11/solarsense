

  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      margin: 0;
      padding: 20px;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .row {
      display: flex;         /* keeps left + right side by side */
      gap: 1.5rem;           /* space between left and right */
    }

    .col-8 {
      flex: 2;               /* ~2/3 width */
    }

    .col-4 {
      flex: 1;               /* ~1/3 width */
    }

    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      padding: 20px;
      margin-bottom:20px ;
    }

    .form-group {
      display: flex;
      /* align-items: center; */
      flex-direction: column;
      justify-content: space-between;
      margin-bottom: 16px;
    }

    .A{
      display: flex;
    }
   


    .form-control {
      flex: 1;
      color:gray;
      background-color: #f6f6f67e!important;  /* light background */
      color: #0000008f;               /* text color */
      border: 1px solid #e5e5e594 !important;   
    }

    
    .form-group button {
      background: none;
      border: none;
      cursor: pointer;
      margin-left: 8px;
      font-size: 16px;
    }

    .profile-pic-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .avatar {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      background: #ddd;
    }

    .upload-btn {
      display: inline-block;
      margin-top: 10px;
      cursor: pointer;
      color: #007bff;
    }

    .d-flex {
      display: flex;
    }

    .justify-center {
      justify-content: center;
    }

    .justify-between {
      justify-content: space-between;
    }

    .gap-2 {
      gap: 0.5rem;
    }

    .gap-4 {
      gap: 1rem;
    }

    .gap-6 {
      gap: 1.5rem;
    }

    .text-center {
      text-align: center;
    }

    .mb-1 { margin-bottom: 0.25rem; }
    .mb-3 { margin-bottom: 1rem; }
    .mb-4 { margin-bottom: 1.5rem; }
    .mb-6 { margin-bottom: 2rem; }

    .star {
      width: 20px;
      height: 20px;
      background: gold;
      clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 
                         79% 91%, 50% 70%, 21% 91%, 32% 57%, 
                         2% 35%, 39% 35%);
      display: inline-block;
    }

    .star.inactive {
      background: #ccc;
    }

   .social-icon {
  font-size: 2rem;         /* make icons larger */
  color: #1877f2; /* default color */
  transition: color 0.3s;  /* smooth hover effect */
   }

.social-icon:hover {
  color: #1877f2; /* change this per network */
}

#summary-linkedin:hover { color: #0a66c2; } /* LinkedIn blue */
#summary-facebook:hover { color: #1877f2; } /* Facebook blue */
#summary-x:hover { color: black; }          /* Twitter/X black */

.stats {
  display: flex;
  align-items: center;     /* makes divider centered automatically */
  justify-content: center;
  gap: 2rem;               /* space between blocks */
  margin-top: 1rem;
}

.stat-block {
  flex: 1;
  text-align: center;
}

.stat-block h5 {
  margin: 0;              /* remove default margin */
  margin-bottom: 3px;     /* tiny space below number */
}

.stat-block p {
  margin: 0;              /* remove default margin */
  font-size: 0.9rem;      /* optional: make text a bit smaller */
  color: #666;            /* softer look */
}


.stats-divider {
  width: 1px; 
  height: 70px;
  background-color: rgba(0, 0, 0, 0.1); /* lighter = looks thinner */
  align-self: baseline;    /* makes line match height of both stat blocks */
}



.avatar {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  background: #ddd;
  background-size: cover;
  background-position: center;
}

.card-name {
  margin-bottom: 1px;
  margin-left: 1rem;  /* same as label width */
}



  </style>
</head>
<body>

<div class="container my-6">
  <div class="row">
    <!-- Left Column -->
    <div class="col-8">

      <!-- first column -->
      <div class="card">
        <h5 class='card-name'>Personal Details</h5>
        <div class="card-body">
            <!-- Full Name -->
          <div class="form-group">
            <label for="full-name">Full Name</label>
            <div style="display: flex; align-items:center; position: relative;">
              <input type="text" class="form-control" id="full-name" value="Nadith Nemal" readonly>
              <button class="edit-btn" style="position:absolute; right:15px;">
                <i class="fas fa-pen"></i>
              </button>
            </div>
          </div>

             <!-- Email -->
          <div class="form-group">
            <label for="email">Email</label>
            <div style="display: flex; align-items:center; position: relative;">
              <input type="text" class="form-control" id="email" value="nadithnemal2002@gmail.com" readonly>
              <button class="edit-btn" style="position:absolute; right:15px;">
                <i class="fas fa-pen"></i>
              </button>
            </div>
          </div>

          <!-- Phone Number -->
          <div class="form-group">
            <label for="phone">Phone number</label>
            <div style="display: flex; align-items:center; position: relative;">
              <input type="text" class="form-control" id="phone" value="+54 548 654 65" readonly>
              <button class="edit-btn" style="position:absolute; right:15px;">
                <i class="fas fa-pen"></i>
              </button>
            </div>
          </div>

          <!-- Address -->
          <div class="form-group">
            <label for="address">Address</label>
            <div style="display: flex; align-items:center; position: relative;">
              <input type="text" class="form-control" id="address" value="No. 47, Lakeview Lane, Colombo 07, Sri Lanka" readonly>
              <button class="edit-btn" style="position:absolute; right:15px;">
                <i class="fas fa-pen"></i>
              </button>
            </div>
          </div>

          <!-- District (not editable) -->
          <div class="form-group">
            <label for="district">District</label>
            <div style="display: flex; align-items:center; position: relative;">
              <input type="text" class="form-control" id="district" value="Colombo" disabled>
            </div>
          </div> 

        </div>
      </div>
<!-- second coloum  -->
      <div class="card">
        <h5 class='card-name'>System Specification</h5>   
        <div class="card-body">  
            <!-- System Capacity -->
          <div class="form-group">
            <label for="system capacity">System Capacity</label>
            <div style="display: flex; align-items:center; position: relative;">
              <input type="text" class="form-control" id="system capacity" value="5 kWp" disabled>
            </div>
          </div>

            <!-- Panel Tilt -->
          <div class="form-group">
            <label for="Panel Tilt">Panel Tilt</label>
            <div style="display: flex; align-items:center; position: relative;">
              <input type="text" class="form-control" id="Panel Tilt" value="30`" disabled>
            </div>
          </div>

          <!-- Panel Azimuth -->
          <div class="form-group">
            <label for="Panel Azimuth">Panel Azimuth</label>
            <div style="display: flex; align-items:center; position: relative;">
              <input type="text" class="form-control" id="Panel Azimuth" value="North" disabled>
            </div>
          </div>

          <!-- Installation Date -->
          <div class="form-group">
            <label for="Installation Date">Installation Date</label>
            <div style="display: flex; align-items:center; position: relative;">
              <input type="text" class="form-control" id="Installation Date" value="01/05/2017" disabled>
            </div>
          </div>

          <!-- Panel Brand -->
          <div class="form-group">
            <label for="Panel Brand">Panel Brand</label>
            <div style="display: flex; align-items:center; position: relative;">
              <input type="text" class="form-control" id="Panel Brand" value="SunPower" disabled>
            </div>
          </div>

          <!-- Inverter Brand -->
          <div class="form-group">
            <label for="Inverter Brand">Inverter Brand</label>
            <div style="display: flex; align-items:center; position: relative;">
              <input type="text" class="form-control" id="Inverter Brand" value="SMA" disabled>
            </div>
          </div>
          
        </div>
      </div>

            <!-- Third Column -->
      <div class="card">
        <h5 class='card-name'>Utility Details</h5>
        <div class="card-body">  
            <!-- CEB Account -->
          <div class="form-group">
            <label for="CEB Account">CEB Account</label>
            <div style="display: flex; align-items:center; position: relative;">
              <input type="text" class="form-control" id="CEB Account" value="123456VC" disabled>
            </div>
          </div>

            <!-- Provider -->
          <div class="form-group">
            <label for="Provider">Provider</label>
            <div style="display: flex; align-items:center; position: relative;">
              <input type="text" class="form-control" id="Provider" value="Ceylon Electricity Board" disabled>
            </div>
          </div>
   
        </div>
      </div>


    </div>

    

    <!-- Right Column -->
    <div class="col-4">
      <div class="card text-center">
        <div class="card-body">
          <!-- Avatar Upload -->
          <div class="profile-pic-wrapper mb-4">
            <div class="avatar mx-auto" id="profile-avatar"></div>
            <input type="file" id="avatar-upload" accept="image/*" hidden>
            <label for="avatar-upload" class="upload-btn">Change</label>
          </div>

          <!-- Profile Info -->
          <h4 class="mb-1" id="summary-name">Alexa Rawles</h4>
          <p class="text-muted mb-1" id="summary-email">alexarawles@gmail.com</p>
          <p class="text-muted mb-1" id="summary-location">Colombo 07, Sri Lanka</p>
          <p class="mb-3" id="summary-phone">+54 548 654 65</p>

           <!-- Stats -->
          <div class="stats">
            <div class="stat-block">
              <h5>5 kWp</h5>
              <p class="text-muted">System Capacity</p>
            </div>

            <div class="stats-divider"></div>

            <div class="stat-block">
              <h5>123456VC</h5>
              <p class="text-muted">CEB Account</p>
            </div>
          </div>


        </div>
      </div>
    </div>
  </div>
</div>


<script>
// Select all edit buttons
const buttons = document.querySelectorAll('.edit-btn'); 

buttons.forEach(button => {
  button.addEventListener('click', () => {
    const inputs = document.querySelectorAll('.form-control'); 
    const selectedInput = button.parentElement.querySelector('.form-control'); 

    // Lock all other inputs
    inputs.forEach(input => {
      if (input !== selectedInput) {
        input.setAttribute('readonly', true);
        input.style.border = 'none';
      }
    });

    // Enable the clicked input
    selectedInput.removeAttribute('readonly');  
    selectedInput.style.border = '1px solid #000'; 
    selectedInput.focus();                       

    // Update right profile card in real-time
    selectedInput.addEventListener('input', () => {
      const value = selectedInput.value;

      switch(selectedInput.id) {
        case 'full-name':
          document.getElementById('summary-name').textContent = value;
          break;
        case 'email':
          document.getElementById('summary-email').textContent = value;
          break;
        case 'phone':
          document.getElementById('summary-phone').textContent = value;
          break;
        case 'address':
          document.getElementById('summary-location').textContent = value;
          break;
      }
    });
  });
});

const avatarUpload = document.getElementById('avatar-upload');
const profileAvatar = document.getElementById('profile-avatar');

avatarUpload.addEventListener('change', function () {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      profileAvatar.style.backgroundImage = `url(${e.target.result})`;
      profileAvatar.style.backgroundSize = "cover";
      profileAvatar.style.backgroundPosition = "center";
    };
    reader.readAsDataURL(file);
  }
});

</script>
