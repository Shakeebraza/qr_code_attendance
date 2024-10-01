           <!-- Footer Start -->
           <div class="container-fluid pt-4 px-4">
                <div class="bg-secondary rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; <a href="https://www.fissionfox.com/"><span id="copyright"></span></a>, <span id="allrightreserved"></span> 
                        </div>
                        <div class="col-12 col-sm-6 text-center text-sm-end">
                            <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                           <span id="designedby"></span> <a href="https://www.fissionfox.com/" >Fission Fox</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer End -->
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    
    <script>
            $(document).ready(function() {
                var currentUrl = window.location.pathname.split("/").pop(); 
            
                $('.nav-item.nav-link').each(function() {
                    var href = $(this).attr('href');
            
                    if (href === currentUrl || (currentUrl === '' && href === 'index.php')) {
                        $('.nav-item.nav-link').removeClass('active'); 
                        $(this).addClass('active');
                    }
                });
            
                // Loop through dropdown menu links
                $('.dropdown-menu a').each(function() {
                    var href = $(this).attr('href');
            
                    if (href === currentUrl) {
                        $('.dropdown-item').removeClass('active'); 
                        $(this).addClass('active');
                    }
                });
            });
    </script>





