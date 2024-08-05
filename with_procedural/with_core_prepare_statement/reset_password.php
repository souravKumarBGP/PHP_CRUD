<?php
    // Logic to check session is start or not if session is not start then start the session
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    if(isset($_SESSION["CRUD_login_user"])){
        header("Location: ./index.php");
    }
    include("./db_connection.php");

    // Logic to initlize some variable because use for validation message
    $success_display_prop = "display:none;";
    $error_display_prop = "display:none;";
    $error_message = "Something went wrong !";

    // Logic to check email is registred or not when user click on login submit button
    if(isset($_POST["reset_submit_btn"])){
        // Logic to apply senitization on form data because protact from sql injection
        $reset_form_data_array = [];
        foreach($_POST as $key => $value){
            if($key != "reset_submit_btn"){
                $reset_form_data_array[$key] = mysqli_real_escape_string($conn, trim($value));
            }
        }

        // Logic to apply serever side validation when user sumbitlogin form
        foreach($reset_form_data_array as $key => $value){

            // Logic to check feilds are not empty
            if($value != ""){

                // Logic to check max-lenth is 200 if cross the max-length then show error
                if(strlen($value) < 200){

                    // Logic to check email is exixt or not if email is not exixt then show error message
                    $result = mysqli_prepare($conn, "SELECT email_id FROM signup_form_data WHERE email_id = ?");
                    mysqli_stmt_bind_param($result, "s", $reset_form_data_array["email_id"]);
                    mysqli_stmt_execute($result);
                    mysqli_stmt_store_result($result);

                    if(mysqli_stmt_num_rows($result) == 1){

                        
                        // Logic to check password is same or not if password is not same then show error message
                        if($reset_form_data_array["new_password"] == $reset_form_data_array["confirm_password"]){

                            // Logic to update password from database
                            $pass = password_hash($reset_form_data_array["confirm_password"], PASSWORD_DEFAULT);

                            $result = mysqli_prepare($conn, "UPDATE signup_form_data SET new_password = ? WHERE email_id = ? ");
                            mysqli_stmt_bind_param($result, "ss", $pass, $reset_form_data_array["email_id"] );
                            mysqli_stmt_execute($result);
                            
                            if($result){
                                $success_display_prop = "display: block;";
                                $error_display_prop = "display:none;";
                                break;
                            }else{
                                $error_display_prop = "display:block;";
                                $success_display_prop = "display:none;";
                                $error_message = "Something went wrong try after some time !";
                                break;
                            }
                        }else{
                            $error_display_prop = "display:block;";
                            $success_display_prop = "display:none;";
                            $error_message = "Please Enter same password !";
                            break; 
                        }
                        
                    }else{
                        $error_display_prop = "display:block;";
                        $success_display_prop = "display:none;";
                        $error_message = "This email is not registred <a href='./registration.php' style='color:yellow !important;'>Register Now</a> !";
                        break; 
                    }
                    
                }else{
                    $error_display_prop = "display:block;";
                    $success_display_prop = "display:none;";
                    $error_message = "Maximum lenth allowed only 199 cherectors !";
                    break;
                }
                
            }else{
                $error_display_prop = "display:block;";
                $success_display_prop = "display:none;";
                $error_message = "Please fill all input box !";
                break;
            }
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
                    <button style="<?php echo $success_display_prop?> background:green; width:100%; padding:10px; color:#fff; border:none;">Password reset seccess fully <a style="color: yellow !important;" href="./login.php">Login Now</a></button>
                    <button style="<?php echo $error_display_prop?> background:#ef2f0d; width:100%; padding:10px; color:#fff; border:none;"><?php echo $error_message?></button>
                    
                    <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="post" id="reset_password_form" autocomplete="off">
                        <h5 class="formHeading">Reset You Password</h5>
                        <h6 class="subHeading">Get stated with your free account</h6>

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

                        <div class="inputBox my-2">
                            <p>Confirm Password:</p>
                            <div class="input_group">
                                <button type="button"><i class="fa-solid fa-key"></i></button>
                                <input type="password" name="confirm_password" id="confirm_password" autocomplete="on" required />
                            </div><!--***End of input_group-->
                            <label for="confirm_password" class="error"></label>

                        </div><!--***End o input box-->

                        <button type="submit" name="reset_submit_btn" class="submitBtn my-3">Reset Now</button>

                        <div class="backLink">
                            <span>You have already account :-</span>
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
            // Apply jquerry validation
            $(document).ready(function(){
                
                $( "#reset_password_form" ).validate({
                    rules:{
                        
                        email_id:{
                            email: true
                        },
                        new_password:{
                            minlength: 8
                        },
                        confirm_password:{
                            equalTo: "#new_password"
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
                        },
                        confirm_password:{
                            equalTo: "Enter same password"
                        }


                    }
                });
                

            })
        </script>
    
    </body>
</html>