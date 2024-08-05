<?php

    // Logic to create objec of CRUD_operation_class
    include("./db_crud_oops.inc.php");
    $CRUD_class_obj = new CRUD_operation_class();

    // Logic to check session is start or not if session is not start then start the session
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    if(isset($_SESSION["CRUD_login_user"])){
        header("Location: ./index.php");
    }

    // Logic to initlize some variable because use for validation message
    $success_display_prop = "display:none;";
    $error_display_prop = "display:none;";
    $error_message = "Something went wrong !";

    // Logic to check email is registred or not when user click on login submit button
    if(isset($_POST["login_submit_btn"])){
        // Logic to apply senitization on form data because protact from sql injection
        $login_form_data_array = [];
        foreach($_POST as $key => $value){
            if($key != "login_submit_btn"){

                $login_form_data_array[$key] = $CRUD_class_obj->conn->real_escape_string(trim($value));
                
            }
        }

        // Logic to initlize a greet variable to check all validation is applied or not if all validation are applied then greet value is 1 else 0
        $greet = 1;

        // Logic to apply server side validation when user submit signup form if validation is successfull then save data into database
        foreach($login_form_data_array as $key => $value){
            
            // Logic to check any feild are not blank if any field is blank then show blank error
            if($value != ""){
                // Logic to apply max-length validation
                if(strlen($value) < 200){

                    // Logic to check  email id is exit or not
                    $email_exix = $CRUD_class_obj->select("signup_form_data" , "email_id, new_password" , "WHERE email_id = '{$login_form_data_array["email_id"]}'");

                    if($CRUD_class_obj->total_rows == 1){
                        if(password_verify($login_form_data_array["new_password"], $email_exix[0]["new_password"])){
                            $greet = 1;
                            break;
                        }else{
                            $greet = 0;
                            $error_display_prop = "display:block;";
                            $success_display_prop = "display:none;";
                            $error_message = "Wrong password ! &nbsp; <a href='./reset_password.php' style='color:yellow !important;'> Reset Password </a> ";
                            break;
                        }
                        
                    }else{
                        $greet = 0;
                        $error_display_prop = "display:block;";
                        $success_display_prop = "display:none;";
                        $error_message = "Email is Not registred ! <a href='./registration.php' style='color:yellow !important;'> Registred Now </a> ";
                        break;
                    }
                        
                }else{
                    $greet = 0;
                    $error_display_prop = "display:block;";
                    $success_display_prop = "display:none;";
                    $error_message = "Meximum length of cherector 199 !";
                    break;
                }
                
            }else{

                $greet = 0;
                $success_display_prop = "display:none;";
                $error_display_prop = "display:block;";
                $error_message = "Please fill all input box !";
                break;
            }
        }



        // Logic to check is $greet = 1; if $greet = 1 then execute update method
        if($greet == 1){

            $_SESSION["CRUD_login_user"] = $login_form_data_array["email_id"];
            $success_display_prop = "display:block;";
            $error_display_prop = "display:none;";
            echo '<meta http-equiv="refresh" content="2; url=./index.php" />';
        }

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
                    <button style="<?php echo $success_display_prop?> background:green; width:100%; padding:10px; color:#fff; border:none;">Login seccess fully</button>
                    <button style="<?php echo $error_display_prop?> background:#ef2f0d; width:100%; padding:10px; color:#fff; border:none;"><?php echo $error_message?></button>
                    
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
            // Apply jquerry validation
            $(document).ready(function(){
                
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
                            required: "Enter your password !",
                            minlength: "Enter minimum 8 digit password !"
                        }

                    }
                });
                

            })
        </script>
    
    </body>
</html>