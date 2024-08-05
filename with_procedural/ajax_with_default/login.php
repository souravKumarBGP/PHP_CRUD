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
                <div class="col-4 mx-auto mt-4">
                    <button class="success_message" style=" display:none; background:green; width:100%; padding:10px; color:#fff; border:none;">Login seccess fully</button>
                    <button class="error_message" style=" display:none; background:#ef2f0d; width:100%; padding:10px; color:#fff; border:none;"><?php echo $error_message?></button>
                    
                    <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="post" id="login_form" autocomplete="off">
                        <h5 class="formHeading">Login Form</h5>
                        <h6 class="subHeading">Get stated with your free account</h6>

                        <button type="button" class="d-block googleLogin"><i class="fa-brands fa-google"></i> Login With Google</button>
                        <button type="button" class="d-block faceBookLogin"><i class="fa-brands fa-facebook-f"></i> Login With FaceBook</button>

                        <div class="or mt-4 d-flex align-content-center justify-content-between">
                            <div class="line"></div>
                            <span style="margin-top: -12px;">Or</span>
                            <div class="line"></div>
                        </div>

                        <div class="inputBox my-2">
                            <p>Enter Email ID:</p>
                            <div class="input_group">
                                <button type="button"><i class="fa-regular fa-envelope"></i></button>
                                <input type="email" name="email_id" id="email_id" autofocus required />
                            </div><!--***End of input_group-->
                            <label for="email_id" class="error"></label>

                        </div><!--***End o input box-->

                        <div class="inputBox my-2">
                            <p>Ener Password:</p>
                            <div class="input_group">
                                <button type="button"><i class="fa-solid fa-key"></i></button>
                                <input type="password" name="new_password" id="new_password" autocomplete="on" required />
                            </div><!--***End of input_group-->
                            <label for="new_password" class="error"></label>

                        </div><!--***End o input box-->

                        <button type="submit" name="login_submit_btn" class="submitBtn my-3">Login</button>

                        <div class="backLink">
                            <span>New user create account :-</span>
                            <a href="./registration.php">Create Now</a>
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
                $( "#login_form" ).validate({
                    rules:{
                        
                        email_id:{
                            email: true
                        },
                        new_password:{
                            minlength: 8
                        }
                    },
                    messages:{
                        
                        email_id:{
                            required: "Enter you email id !",
                            email: "Enter you valid email id !"
                        },
                        new_password:{
                            required: "Create new password !",
                            minlength: "Enter minimum 8 digit password !"
                        }

                    }
                });

                $("#login_form").on("submit", function(event){
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
                    if(inputs_feilds.length == greet){
                        const email_id = $("#email_id").val();
                        const new_password = $("#new_password").val();
                        
                        
                        $.ajax({
                            url: "./login_form_process.php",
                            type:"post",
                            data: {"email_id": email_id, "new_password": new_password},
                            success: function(responce){

                                if(responce == "successfull"){
                                    $(".success_message").css({"display":"block"});
                                    $("#login_form")[0].reset();
                                    setTimeout(() => {
                                        window.location.href = "./index.php";
                                    }, 2000);

                                }
                                if(responce == "emaii_not_registred"){
                                    $(".error_message").css({"display":"block"});
                                    $(".error_message").html("This email is not registred <a href='./registration.php' style='color:yellow !important;'>Register Now</a> !");
                                }
                                if(responce == "password_not_match"){
                                    $(".error_message").css({"display":"block"});
                                    $(".error_message").html("Password is not matched <a href='./reset_password.php' style='color:yellow !important;'> Reset Password </a> !");
                                }
                                if(responce == "maxLength_error"){
                                    alert("Max-length allowed only 199 cherectors");
                                }
                                if(responce == "input_blank"){
                                    alert("Fill all the input box !");
                                }
                            }

                        })
                    }


                });
                
                
            })
        </script>
    
    </body>
</html>