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

    // Logic to save form data in database when user submit signUP form
    if(isset($_POST["submit_button"])){
        
        // Logic to implement senitize on form data and save into from_data_array variable
        $form_data_array = [];
        foreach($_POST as $key => $value){
            if($key != "submit_button"){

                // Logic to implement senitize data
                if($key == "new_password"){
                    $form_data_array[mysqli_real_escape_string($conn, $key)] = password_hash(mysqli_real_escape_string($conn, trim($value)), PASSWORD_DEFAULT);
                }else{
                    $form_data_array[mysqli_real_escape_string($conn, $key)] = mysqli_real_escape_string($conn, trim($value));
                }
                
            }
        }
        
        // Logic to apply server side validation when user submit signup form if validation is successfull then save data into database
        foreach($form_data_array as $key => $value){

            // Logic to check all feild are not blank if any feild is blank then show errory
            if($value != ''){
                // Logic to apply max-lenth validation
                if(strlen($value) < 200){
    
                    $email_exit_result = mysqli_prepare($conn, "SELECT email_id FROM signup_form_data WHERE email_id = ?");
                    mysqli_stmt_bind_param($email_exit_result, 's', $form_data_array['email_id']);
                    mysqli_stmt_execute($email_exit_result);
                    mysqli_stmt_bind_result($email_exit_result, $email_id);
                    mysqli_stmt_store_result($email_exit_result);
                    
                    if(mysqli_stmt_num_rows($email_exit_result) == 0){
    
                        // Logic to check check valid phone number or not
                        if(strlen($form_data_array["phone_number"]) == 10){
    
                            $image_path = "./profile_uploded_img/".$_FILES["profile_pic"]["name"];
                            move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $image_path);
                            
                            $column_names = implode(", ", array_keys($form_data_array));

                            // Lotic to insert form data into database when user submit signup from 
                            $result = mysqli_prepare($conn, "INSERT INTO signup_form_data ({$column_names}, profile_pic) VALUES( ?, ?, ?, ?, ?, ?)" );
                            mysqli_stmt_bind_param($result, 'ssssss', $form_data_array["user_name"], $form_data_array["phone_number"], $form_data_array["email_id"], $form_data_array["new_password"], $form_data_array["gender"], $image_path );
                            mysqli_stmt_execute($result);

                            if($result){
                                $success_display_prop = "display:block;";
                                $error_display_prop = "display:none;";
                                break;
                            }
                            else{
                                $error_display_prop = "display:block;";
                                $success_display_prop = "display:none;";
                                $error_message = "Something went wrong try after some time !";
                                break;
                            }
    
                            mysqli_stmt_close($result);
                            
                        }else{
                            $error_display_prop = "display:block;";
                            $success_display_prop = "display:none;";
                            $error_message = "Enter valid phone number !";
                            break;
                        }
                        
                        mysqli_stmt_free_result($email_exit_result); // Free up the result set memory
                        mysqli_stmt_close($email_exit_result); // Close the prepared statement

                    }else{
                        $error_display_prop = "display:block;";
                        $success_display_prop = "display:none;";
                        $error_message = "Email is already registred !";
                        break;
                    }

    
                }else{
                    $error_display_prop = "display:block;";
                    $success_display_prop = "display:none;";
                    $error_message = "Meximum length of cherector 199 !";
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
                <div class="col-4 mx-auto my-2 mb-4 ">
                    <button style="<?php echo $success_display_prop?> background:green; width:100%; padding:10px; color:#fff; border:none;">Data insert seccess fully !<a style="color: yellow !important;" href="./login.php">Login Now</a> </button>
                    <button style="<?php echo $error_display_prop?> background:#ef2f0d; width:100%; padding:10px; color:#fff; border:none;"><?php echo $error_message?></button>
                    
                    <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="post" id="signup_form" enctype="multipart/form-data">
                        <h5 class="formHeading">Create Account</h5>
                        <h6 class="subHeading mb-2">Get stated with your free account</h6>

                        <div class="inputBox my-2">
                            <p>Your Name:</p>
                            <div class="input_group">
                                <button type="button"><i class="fa-regular fa-user"></i></button>
                                <input type="text" name="user_name" id="user_name" value="Sourav Kumar BGP" autofocus required />
                            </div><!--***End of input_group-->
                            <label for="user_name" class="error"></label>

                        </div><!--***End o input box-->

                        <div class="inputBox my-2">
                            <p>Your Phone No:</p>
                            <div class="input_group">
                                <button type="button"><i class="fa-solid fa-phone"></i></button>
                                <input type="text" name="phone_number" id="phone_number" value="9065608408" required />
                            </div><!--***End of input_group-->
                            <label for="phone_number" class="error"></label>
                
                        </div><!--***End o input box-->

                        <div class="inputBox my-2">
                            <p>Your Email ID:</p>
                            <div class="input_group">
                                <button type="button"><i class="fa-regular fa-envelope"></i></button>
                                <input type="email" name="email_id" id="email_id" value="s@gmail.com" required />
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
                                <label> Male: <input type="radio" class="me-3" value="Male" checked name="gender" id="gender" required /> </label>
                                <label> Female: <input type="radio" class="me-3" value="Female" name="gender" /> </label>
                                <label> Other's: <input type="radio" value="Others" name="gender" /> </label>
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
            // Apply jquerry validation
            $(document).ready(function(){
                
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

            })
        </script>

    </body>
</html>