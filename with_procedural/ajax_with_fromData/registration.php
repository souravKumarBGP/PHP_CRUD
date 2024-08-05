<?php
    // Logic to check session is start or not if session is not start then start the session
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    if(isset($_SESSION["CRUD_login_user"])){
        header("Location: ./index.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>CRUD Operation with core php And apply jQuerry validation</title>
        <meta name="keyword" content="Core php CRUD with jquerry" />
        <meta name="discription" content="This is CRUD operation with jQuerry validation">
        <meta name="author" content="souravKumarBGP" />
        <meta name="robots" content="index,following" />

        <!-- ********************* Main Templates file linking ********************** -->
        <link rel="stylesheet" href="./style.css" />

        <!-- ********************* External file link's ***************************** -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
    </head>
    <body>
        
        <!-- ************************ Start nav section ******************************** -->
        <nav class="navbar navbar-expand-lg bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Navbar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link active" aria-current="page" href="./index.php">Home</a>
                        <?php
                            // Logic to check user is login or not if user is login then show logit link
                            if(!isset($_SESSION["CRUD_login_user"])){
                                echo'<a class="nav-link" href="./registration.php">Register</a>
                                     <a class="nav-link" href="./login.php">Login</a>';
                            }else{
                                echo'<a class="nav-link" href="./logout.php">Logout</a>';
                            }
                        ?>
                    </div>
                    <?php
                        // Logic to check user is login or not 
                        if(isset($_SESSION["CRUD_login_user"])){
                            echo"<span style='color: #fff;'>WealCome To: {$_SESSION['CRUD_login_user']}</span>";
                        }
                    ?>
                    
                </div>
            </div>
        </nav><!--*** End of nav section-->
    
        <!-- *********************** Start registration form section ******************* -->
        <section class="container">
            <div class="row">
                <div class="col-4 mx-auto my-2 mb-4 ">
                    
                    <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="post" id="signup_form" enctype="multipart/form-data">
                        <h5 class="formHeading">Create Account</h5>
                        <h6 class="subHeading mb-2">Get stated with your free account</h6>

                        <div class="inputBox my-2">
                            <p>Your Name:</p>
                            <div class="input_group">
                                <button type="button"><i class="fa-regular fa-user"></i></button>
                                <input type="text" name="user_name" id="user_name" autofocus required />
                            </div><!--***End of input_group-->
                            <label for="user_name" class="error"></label>

                        </div><!--***End o input box-->

                        <div class="inputBox my-2">
                            <p>Your Phone No:</p>
                            <div class="input_group">
                                <button type="button"><i class="fa-solid fa-phone"></i></button>
                                <input type="text" name="phone_number" id="phone_number" required />
                            </div><!--***End of input_group-->
                            <label for="phone_number" class="error"></label>
                
                        </div><!--***End o input box-->

                        <div class="inputBox my-2">
                            <p>Your Email ID:</p>
                            <div class="input_group">
                                <button type="button"><i class="fa-regular fa-envelope"></i></button>
                                <input type="email" name="email_id" id="email_id" required />
                            </div><!--***End of input_group-->
                            <label for="email_id" class="error"></label>

                        </div><!--***End o input box-->

                        <div class="inputBox my-2">
                            <p>Create New Password:</p>
                            <div class="input_group">
                                <button type="button"><i class="fa-solid fa-key"></i></button>
                                <input type="password" name="new_password" id="new_password" autocomplete="on" required />
                            </div><!--***End of input_group-->
                            <label for="new_password" class="error"></label>

                        </div><!--***End o input box-->

                        <div class="inputBox my-2">
                            <p>Select Your Profile Pic:</p>
                            <div class="input_group">
                                <button type="button"><i class="fa-regular fa-image"></i></button>
                                <input type="file" name="profile_pic" id="profile_pic" required accept=".png, .jpg, .jpeg" />
                            </div><!--***End of input_group-->
                            <label for="profile_pic" class="error"></label>

                        </div><!--***End o input box-->

                        <div class="inputBox my-2">
                            <p>Select Gender:</p>
                            <div class="input_grou">
                                <label> Male: <input type="radio" class="me-3 gender" value="Male" checked name="gender" id="gender" required /> </label>
                                <label> Female: <input type="radio" class="me-3 gender" value="Female" name="gender" /> </label>
                                <label> Other's: <input type="radio" class="gender" value="Others" name="gender" /> </label>
                            </div><!--***End of input_group-->
                            <label for="gender" class="error"></label>

                        </div><!--***End o input box-->

                        <button type="submit" name="submit_button" class="submitBtn my-2">Submit</button>

                        <div class="backLink">
                            <span>Do you have already account :-</span>
                            <a href="./login.php">Login Now</a>
                        </div>
                        
                    </form>
                </div>
            </div>                        
        </section>
    
        <!-- ********************* External file link's ****************************** -->
        <script src="./js/jquery-3.7.1.min.js"></script>
        <script src="./js/jquery.validate.min.js"></script>

        <script>
            $(document).ready(function(){
                
                // Apply clint side validation using jquerry validation pluggin
                $( "#signup_form" ).validate({
                    rules:{
                        user_name:{
                            required: true
                        },
                        phone_number:{
                            digits: true,
                            rangelength: [10, 10]
                        },
                        email_id:{
                            email: true
                        },
                        new_password:{
                            minlength: 8
                        },
                        profile_pic:{
                            required: true
                        }
                    },
                    messages:{
                        user_name:{
                            required: "Fill Your name !"
                        },
                        phone_number:{
                            required: "Enter your number !",
                            digits: "Enter your valid phone number !",
                            rangelength: "Enter your valid phone number !"
                        },
                        email_id:{
                            required: "Enter you email id !",
                            email: "Enter you valid email id !"
                        },
                        new_password:{
                            required: "Create new password !",
                            minlength: "Enter minimum 8 digit password !"
                        },
                        profile_pic:{
                            required: "Select your profile picture"
                        }

                    }
                });

                $("#signup_form").on("submit", function(event){
                    event.preventDefault();

                    // Logic to initlize a greet variable becaust to check check all inputs feild is valid or not if all input feild is valid then make a ajax request
                    let greet = 0;
                    let inputs_feilds = document.querySelectorAll("input, select, textarea");
                      for(items of inputs_feilds){
                        if(items.classList.contains("valid")){
                            greet++;
                        }
                    }
                    
                    // Now logic to make a ajax request 
                    if (inputs_feilds.length == greet) {
                        var formData = new FormData($("#signup_form")[0]);
                        formData.append('file', $('#profile_pic')[0].files[0]);

                        $.ajax({
                            url: "./registration_form_insert.php",
                            type: "post",
                            data: formData,
                            processData: false,  // Important: tell jQuery not to process the data
                            contentType: false,  // Important: tell jQuery not to set contentType
                            success: function(response) {
                                console.log(response);

                                if (response.trim() === "Successfully") {
                                    alert("Data inserted successfully");
                                    $("#signup_form")[0].reset();  // Reset the form after successful submission
                                } else if (response.trim() === "invalid_phone_no") {
                                    alert("Enter valid mobile number !");
                                } else if (response.trim() === "email_exist") {
                                    alert("Email is already exist !");
                                } else if (response.trim() === "max_length") {
                                    alert("Max-length allowed only 199 characters");
                                } else if (response.trim() === "field_empty") {
                                    alert("Fill all the input boxes !");
                                }
                            }
                        });
                    }



                });
                
            })
        </script>

    </body>
</html>