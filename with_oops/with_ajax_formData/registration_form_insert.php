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

    // include("./db_connection.php");


    // Logic to save form data in database when user submit signUP form
    if(isset($_POST["user_name"])){

        // Logic to implement senitize on form data and save into from_data_array variable
        $form_data_array = [];
        foreach($_POST as $key => $value){
            if($key != "submit_button"){
                $form_data_array[$key] = trim($value);// Logic to remove extera spaces
            }
        }

        // Logic to initlize a greet variable to check all validation is applied or not all validation are applied then greet value is true else false
        $greet = 1;

        // Logic to apply server side validation when user submit signup form if validation is successfull then save data into database
        foreach($form_data_array as $key => $value){
            
            // Logic to check any feild are not blank if any field is blank then show blank error
            if($value != "" && $_FILES["profile_pic"]["name"] != ""){

                // Logic to apply max-length validation
                if(strlen($value) < 200){

                    // Logic to apply phone number validation
                    if(strlen($form_data_array["phone_number"]) == 10){

                        // Logic to check  email id is exit or not if email is already exist then show error
                        $email_exix = $CRUD_class_obj->select("signup_form_data", "email_id", "WHERE email_id = '{$CRUD_class_obj->conn->real_escape_string($form_data_array["email_id"])}'");

                        if($CRUD_class_obj->total_rows == 0){

                            $greet = 1;
                            break;
                            
                        }else{
                            $greet = 0;
                            echo "email_exist";
                            break;
                        }
                        
                    }else{
                        $greet = 0;
                        echo "invlide_phone_no";
                        break;
                    }
                    
                }else{
                    
                    $greet = 0;
                    echo "max_length";
                    break;
                }
                
            }else{

                $greet = 0;
                echo "feild_empti";
                break;
            }
        }

        // Logic to check is $greet = 1; if $greet = 1 then execute update method
        if($greet == 1){
            
            $image_path = "./profile_uploded_img/".$_FILES["profile_pic"]["name"];
            $form_data_array["profile_pic"] = $image_path;
            move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $image_path);
            $form_data_array["profile_pic"] = $image_path;

            $insert_result = $CRUD_class_obj->insert("signup_form_data", $form_data_array);
            
            if($insert_result){
                echo "Successfully";
            }else{
                echo $insert_result;
            }
        }
        
    }


?>