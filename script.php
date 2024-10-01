    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <!-- Instascan JS -->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <!-- Data Table -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
 
    
    
    <script>
$(document).ready(function() {
    $('#status').change(function() {
        var selectedStatus = $(this).val();
        
        if (selectedStatus === 'Sin trabajo' || selectedStatus === 'noWork') {
            // Hide the arrival dropdown and show the text input
            $('.arrival').hide();
            $('.arrival').val(null).prop('disabled', true); // Ensure dropdown is disabled
            $('#nullArrivalGroup').show(); // Show the input field with null value
        } else {
            // Show the arrival dropdown and hide the text input
            $('.arrival').show();
            $('.arrival').prop('disabled', false).prop('required', true); // Ensure dropdown is enabled and required
            $('#nullArrivalGroup').hide(); // Hide the null input field
        }
    });
});

$(document).ready(function() {
    $('#status2').change(function() {
        var selectedStatus = $(this).val();
        
        if (selectedStatus === 'Sin trabajo' || selectedStatus === 'noWork') {
            // Hide the arrival dropdown and show the text input
            $('.arrival2').hide();
            $('.arrival2').val(null).prop('disabled', true); // Ensure dropdown is disabled
            $('#nullArrivalGroup2').show(); // Show the input field with null value
        } else {
            // Show the arrival dropdown and hide the text input
            $('.arrival2').show();
            $('.arrival2').prop('disabled', false).prop('required', true); // Ensure dropdown is enabled and required
            $('#nullArrivalGroup2').hide(); // Hide the null input field
        }
    });
});
    </script>

