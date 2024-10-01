<?php
include_once('header.php');
$data=$funObject->GetAttendance();
$users=$funObject->GetAllUser();
$usersdata=$funObject->GetUserAttendance(date('y-m-d'));
?>




        <!-- Content Start -->
        



            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-6">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-line fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2" id="allusertag">All User</p>
                                <h6 class="mb-0"><?php echo $users['count'] ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-6   ">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-bar fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2" id="todayatt">Today Attendance</p>
                                <h6 class="mb-0"><?php
                                echo $data['count'];
                                ?></h6>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
            <!-- Sale & Revenue End -->





            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4">
            <div class="col-sm-12 col-xl-12">
                        <div class="bg-secondary rounded h-100 p-4">
                            <h6 class="mb-4" id="presentemploye">Present Employe</h6>
                            <div class="owl-carousel testimonial-carousel">
                            <?php
                            if(isset($usersdata['records'])){
                                foreach($usersdata['records'] as $val){
                                    if(!empty($val["profile"])){
                                        $imgurl=$urlval.$val["profile"];
                                        
                                    }else{
                                        $imgurl=$urlval."admin/img/user.jpg";

                                    }
                                    echo '
                                <div class="testimonial-item text-center">
                                    <img class="img-fluid rounded-circle mx-auto mb-4" src="'.$imgurl.'" style="width: 100px; height: 100px;">
                                    <h5 class="mb-1">'.$val["username"].'</h5>
                                    <p>User</p>
                                </div>
                                
                                ';
                                
                            }
                        }
                        ?>
                        </div>  

                        </div>
                    </div>
            </div>
            <!-- Widgets End -->

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

 <?php
 include_once('footer.php');
 ?>

<script>
    let currentLanguage = 'es';

    function loadLanguage(language) {
        fetch(`<?php echo $urlval ?>language/admin/${language}.json`)
            .then(response => response.json())
            .then(data => {
                console.log(data);

                // Update text based on the selected language
                const elementById = {
                    gowebsite: document.getElementById('gowebsite'),
                    myprofile: document.getElementById('myprofile'),
                    settings: document.getElementById('settings'),
                    logout: document.getElementById('logout'),
                    dashboard: document.getElementById('dashboard'),
                    todayattendance: document.getElementById('todayattendance'),
                    filterattendance: document.getElementById('filterattendance'),
                    qrtoday: document.getElementById('qrtoday'),
                    Users: document.getElementById('Users'),
                    alluser: document.getElementById('alluser'),
                    adduser: document.getElementById('adduser'),
                    room: document.getElementById('room'),
                    addroom: document.getElementById('addroom'),
                    allroom: document.getElementById('allroom'),
                    admin:document.getElementById("useradmin"),
                    copyright:document.getElementById("copyright"),
                    allrightreserved:document.getElementById("allrightreserved"),
                    designedby:document.getElementById("designedby"),
                    
                    // dashboard data
                    alluser:document.getElementById("allusertag"),
                    todayattendance:document.getElementById("todayatt"),
                    presentemploye:document.getElementById("presentemploye")
                };

                // Update text for elements by ID
                for (const [key, element] of Object.entries(elementById)) {
                    if (element) {
                        element.innerText = data[key] || '';
                    } else {
                        console.warn(`Element with ID '${key}' not found.`);
                    }
                }
            })
            .catch(error => console.error('Error loading localization file:', error));
    }

    // Load initial language
    loadLanguage(currentLanguage);

    // Handle language selection change
    document.getElementById('languageSelector').addEventListener('change', function() {
        const selectedLanguage = this.value;
        if (selectedLanguage !== currentLanguage) {
            currentLanguage = selectedLanguage;
            loadLanguage(selectedLanguage);
        }
    });
</script>

</body>

</html>