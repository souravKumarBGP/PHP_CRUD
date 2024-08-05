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

    // Logic to check email is registred or not when user click on login submit button
    if(isset($_POST["email_id"])){
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
                            echo "password_not_match";
                            break;
                        }
                        
                    }else{
                        $greet = 0;
                        echo "email_not_registred";
                        break;
                    }
                        
                }else{
                    $greet = 0;
                    echo "maxLength_error";
                    break;
                }
                
            }else{

                $greet = 0;
                echo "input_blank";
                break;
            }
        }



        // Logic to check is $greet = 1; if $greet = 1 then execute update method
        if($greet == 1){

            $_SESSION["CRUD_login_user"] = $login_form_data_array["email_id"];
            echo "successfull";
        }

    }
    
?>