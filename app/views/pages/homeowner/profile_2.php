

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
      background-color: #f3f6fa;  /* light background */
      color: #333;               /* text color */
      border: 1px solid #ccc;    /* subtle border */
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


  </style>
</head>
<body>

<div class="container my-6">
  <div class="row">
    <!-- Left Column -->
    <div class="col-8">
      <div class="card">
        <div class="card-body">
          
            <!-- Full Name -->
<div class="form-group">
  <label for="full-name">Full Name</label>
  <div style="display: flex; align-items:center; position: relative;">
    <input type="text" class="form-control" id="full-name" value="Alexa Rawles Rogdrigo" readonly>
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

<!-- District (not editable) -->
<div class="form-group">
  <label for="district">District</label>
  <div style="display: flex; align-items:center; position: relative;">
    <input type="text" class="form-control" id="district" value="Colombo" disabled>
  </div>
</div>

<!-- Agent ID -->
<div class="form-group">
  <label for="agent-id">Agent-ID</label>
  <div style="display: flex; align-items:center; position: relative;">
    <input type="text" class="form-control" id="agent-id" value="CMB23039D" disabled>
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

<!-- Work since -->
<div class="form-group">
  <label for="work since">Work Since</label>
  <div style="display: flex; align-items:center; position: relative;">
    <input type="text" class="form-control" id="work-since" value="03/04/2020" readonly>
  </div>
</div>


<!-- Facebook -->
<div class="form-group">
  <label for="facebook">facebook</label>
  <div style="display: flex; align-items:center; position: relative;">
    <input type="url" class="form-control" id="Facebook" value="https://www.facebook.com/johndoe" readonly>
    <button class="edit-btn" style="position:absolute; right:15px;">
      <i class="fas fa-pen"></i>
    </button>
  </div>
</div>


<!-- Linkedin -->
<div class="form-group">
  <label for="linkedin">LinkedIn</label>
  <div style="display: flex; align-items:center; position: relative;">
    <input type="url" class="form-control" id="linkedin" value="https://www.linkedin.com/in/johndoe" readonly>
    <button class="edit-btn" style="position:absolute; right:15px;">
      <i class="fas fa-pen"></i>
    </button>
  </div>
</div>


<!-- X -->
<div class="form-group">
  <label for="X">X</label>
  <div style="display: flex; align-items:center; position: relative;">
    <input type="url" class="form-control" id="x" value="https://x.com/johndoe" readonly>
    <button class="edit-btn" style="position:absolute; right:15px;">
      <i class="fas fa-pen"></i>
    </button>
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

          <!-- Rating -->
          <div class="d-flex justify-center gap-2 mb-4">
            <span class="star"></span>
            <span class="star"></span>
            <span class="star"></span>
            <span class="star"></span>
            <span class="star inactive"></span>
          </div>

 
          <!-- Social -->
 <div class="d-flex justify-center gap-4 mb-6">
  <a href="https://www.linkedin.com" target="_blank" id="summary-linkedin" class="social-icon">
    <i class="fab fa-linkedin"></i>
  </a>
  <a href="https://www.facebook.com" target="_blank" id="summary-facebook" class="social-icon">
    <i class="fab fa-facebook"></i>
  </a>
  <a href="https://twitter.com" target="_blank" id="summary-x" class="social-icon">
    <i class="fa-brands fa-x-twitter"></i>


<!-- new Twitter/X icon -->
  </a>
</div>

          <!-- Stats -->
          <div class="d-flex justify-between">
            <div>
              <h5>10 Years</h5>
              <p class="text-muted">Experience</p>
            </div>
            <div>
              <h5>46</h5>
              <p class="text-muted">Total Works</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
const buttons = document.querySelectorAll('.edit-btn'); // Select all edit buttons

buttons.forEach(button => {
  button.addEventListener('click', () => {
    const inputs = document.querySelectorAll('.form-control'); // All input fields
    const selectedInput = button.parentElement.querySelector('.form-control'); // Input next to clicked button

    // Make all other inputs readonly
    inputs.forEach(input => {
      if (input !== selectedInput) {
        input.setAttribute('readonly', true); // Lock input
        input.style.border = 'none';          // Remove border for read-only inputs
      }
    });

    // Enable the clicked input for editing
    selectedInput.removeAttribute('readonly');  // Unlock input
    selectedInput.style.border = '1px solid #000'; // Add border to show editable
    selectedInput.focus();                       // Focus the input for typing
  });
});
</script>


