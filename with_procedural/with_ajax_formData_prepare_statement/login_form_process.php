<?php

    // Logic to check session is start or not if session is not start then start the session
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    
    // Logic to check user is login or not if user is login then user regirect on login page
    if(isset($_SESSION["CRUD_login_user"])){
        header("Location: ./index.php");
    }

    include("./db_connection.php");

    // Logic to check email is registred or not when user click on login submit button
    if(isset($_POST["email_id"])){
        // Logic to apply senitization on form data because protact from sql injection
        $login_form_data_array = [];
        foreach($_POST as $key => $value){
            if($key != "login_submit_btn"){
                $login_form_data_array[$key] = mysqli_real_escape_string($conn, trim($value));
            }
        }

        // Logic to apply serever side validation when user sumbitlogin form
        foreach($login_form_data_array as $key => $value){

            // Logic to check feilds are not empty
            if($value != ""){

                // Logic to check max-lenth is 200 if cross the max-length then show error
                if(strlen($value) < 200){

                    // Logic to check email is exixt or not if email is not exixt then show error message
                    $result = mysqli_prepare($conn, "SELECT email_id, new_password FROM signup_form_data WHERE email_id = ?");
                    mysqli_stmt_bind_param($result, "s", $login_form_data_array["email_id"]);
                    mysqli_stmt_execute($result);
                    mysqli_stmt_bind_result($result, $email_id, $new_password);
                    mysqli_stmt_store_result($result);
                    mysqli_stmt_fetch($result);

                    if(mysqli_stmt_num_rows($result) == 1){
                        
                        // Logic to check password is matched or not if password is not matched then show reset password link
                        if(password_verify($login_form_data_array["new_password"], $new_password)){

                            $_SESSION["CRUD_login_user"] = $login_form_data_array["email_id"];
                            echo "successfull";
                            break;

                        }else{
                            echo "password_not_match";
                            break;
                        }
                        
                    }else{
                        echo "emaii_not_registred";
                        break; 
                    }
                    
                }else{
                    echo "maxLength_error";
                    break;
                }
                
            }else{
                
                echo "input_blank";
                break;
            }
        }

    }

?>