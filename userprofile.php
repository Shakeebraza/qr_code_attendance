<?php
include_once('global.php');
$res = $funObject->checksession();



if ($res == 0) {
    header('Location: login.php');
    exit(); 
}

if(!empty($_SESSION['profile']) || $_SESSION['verified'] == 1){
       header('Location: empmasterlist.php');
    exit();
}
$chksAdmin = $funObject->isAdmin();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Configuración de perfil</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .hidden-step { display: none; }
  </style>
</head>
<body class="bg-blue-50">

  <!-- Container for profile setup page with steps -->
  <div class="flex justify-center items-center min-h-screen p-6 bg-blue-100">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-3xl">
      
      <!-- Step Progress Bar -->
      <div class="w-full bg-gray-200 h-2 mb-8 rounded-full">
        <div id="progressBar" class="bg-blue-600 h-2 rounded-full" style="width: 33%;"></div>
      </div>

      <!-- Title -->
      <h1 class="text-2xl font-bold text-blue-800 text-center mb-6">Configura tu perfil</h1>

      <!-- Profile Setup Form with Steps -->
      <form id="profileForm" enctype="multipart/form-data">
        <!-- Step 1: Upload Profile Picture -->
        <div id="step1" class="step">
          <div class="flex justify-between items-center mb-4">
            <label for="profilePic" class="text-lg font-medium text-gray-900">Subir Foto de Perfil</label>
            <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-blue-600 bg-gray-100 flex items-center justify-center">
              <img id="profilePreview" src="https://via.placeholder.com/150" alt="Preview" class="object-cover w-full h-full hidden">
              <span id="profilePlaceholder" class="text-gray-400">Sin imagen</span>
            </div>
          </div>
          <input type="file" id="profilePic" name="profilePic" accept="image/*" class="w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none p-2">
        </div>

       <!-- Step 2: Upload ID -->
<div id="step2" class="hidden-step step">
  <label for="pdfUpload" class="text-lg font-medium text-gray-900">Subir ID</label>
  <input type="file" id="pdfUpload" name="pdfUpload" class="block w-full p-2 text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none mb-4">
</div>

<!-- Step 3: Enter Test ID -->
<div id="step3" class="hidden-step step">
  <label for="testId" class="text-lg font-medium text-gray-900">Ingresar Test ID</label>
  <input type="file" id="testId" name="testId" class="block w-full p-2 text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:outline-none mb-4">
</div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between mt-6">
          <button type="button" id="prevBtn" class="py-2 px-4 bg-gray-500 text-white rounded-lg focus:outline-none hidden">Anterior</button>
          <button type="button" id="nextBtn" class="py-2 px-4 bg-blue-600 text-white rounded-lg focus:outline-none">Siguiente</button>
        </div>
      </form>

      <!-- Success Message -->
      <div id="successMessage" class="hidden text-center mt-6 text-green-600 font-bold">
        ¡Formulario enviado con éxito!
      </div>

    </div>
  </div>

<script>
  let currentStep = 1;
  const progressBar = document.getElementById('progressBar');
  const nextBtn = document.getElementById('nextBtn');
  const prevBtn = document.getElementById('prevBtn');
  const steps = ['step1', 'step2', 'step3'];

  // Function to show the current step
  function showStep(step) {
    steps.forEach((stepId, index) => {
      document.getElementById(stepId).classList.add('hidden-step');
      if (index + 1 === step) {
        document.getElementById(stepId).classList.remove('hidden-step');
      }
    });

    progressBar.style.width = (step / steps.length) * 100 + '%';
    prevBtn.style.display = step > 1 ? 'inline-block' : 'none';
    nextBtn.innerHTML = step === steps.length ? 'Guardar' : 'Siguiente';
  }

  nextBtn.addEventListener('click', () => {
    if (validateStep(currentStep)) {
      if (currentStep < steps.length) {
        currentStep++;
        showStep(currentStep);
      } else {
        submitForm();
      }
    }
  });

  prevBtn.addEventListener('click', () => {
    if (currentStep > 1) {
      currentStep--;
      showStep(currentStep);
    }
  });

  // Show the first step initially
  showStep(currentStep);

  // Preview Profile Picture
  document.getElementById('profilePic').addEventListener('change', function(event) {
    const [file] = event.target.files;
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('profilePreview').src = e.target.result;
        document.getElementById('profilePreview').classList.remove('hidden');
        document.getElementById('profilePlaceholder').classList.add('hidden');
      };
      reader.readAsDataURL(file);
    }
  });

  // Function to validate the current step
  function validateStep(step) {
    let isValid = true;
    if (step === 1) {
      const profilePic = document.getElementById('profilePic').files[0];
      if (!profilePic) {
        alert('Por favor, sube una foto de perfil.');
        isValid = false;
      }
    } else if (step === 2) {
      const pdfUpload = document.getElementById('pdfUpload').files[0];
      if (!pdfUpload) {
        alert('Por favor, sube tu ID en formato PDF.');
        isValid = false;
      }
    } else if (step === 3) {
      const testId = document.getElementById('testId').files[0];
      if (!testId) {
        alert('Por favor, sube tu Test ID en formato PDF.');
        isValid = false;
      }
    }
    return isValid;
  }

  // Function to submit the form via AJAX
  function submitForm() {
    const formData = new FormData(document.getElementById('profileForm'));

    $.ajax({
      url: '<?php echo $urlval?>ajax/submit_profile.php', // Replace with your server-side script
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        $('#successMessage').removeClass('hidden'); // Show success message
        console.log('Form submission successful:', response);
        setTimeout(function() {
          window.location.href = 'galleryuser.php'; // Redirect to the desired page
        }, 2000);
      },
      error: function(xhr, status, error) {
        console.error('Error submitting form:', error);
      }
    });
  }
</script>

</body>
</html>