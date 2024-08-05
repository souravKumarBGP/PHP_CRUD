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


    // Logic to save form data in database when user submit signUP form
    if(isset($_POST["user_name"])){
        
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
    
                    // Logic to check email is already registred or not if email is already registred then show error
                    $email_exit_result = mysqli_prepare($conn, "SELECT email_id FROM signup_form_data WHERE email_id = ?");
                    mysqli_stmt_bind_param($email_exit_result, 's', $form_data_array['email_id'] );
                    mysqli_stmt_execute($email_exit_result);
                    mysqli_stmt_bind_result($email_exit_result, $email_id);
                    mysqli_stmt_store_result($email_exit_result);
                    
                    if(mysqli_stmt_num_rows($email_exit_result) == 0){
    
                        // Logic to check check valid phone number or not
                        if(strlen($form_data_array["phone_number"]) == 10){

                            $img_path = "./uploded_img/".$_FILES["profile_pic"]["name"];
                            move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $img_path);
                            
                            // Convert array keys into a comma-separated string of column names
                            $column_names = implode(", ", array_keys($form_data_array));

                            // Lotic to insert form data into database when user submit signup from 
                            $result = mysqli_prepare($conn, "INSERT INTO signup_form_data ({$column_names}, profile_pic) VALUES( ?, ?, ?, ?, ?, ?)" );
                            mysqli_stmt_bind_param($result, 'ssssss', $form_data_array["user_name"], $form_data_array["phone_number"], $form_data_array["email_id"], $form_data_array["new_password"], $form_data_array["gender"], $img_path );
                            mysqli_stmt_execute($result);
                            
                            if($result){
                                echo "Successfully";
                                break;
                            }else{
                                echo $result;
                                break;
                            }

                            mysqli_stmt_close($result);
    
                        }else{
                            echo "invlide_phone_no";
                            break;
                        }
                        
    
                    }else{
                        echo "email_exist";
                        break;
                    }

                    mysqli_stmt_free_result($email_exit_result); // Free up the result set memory
                    mysqli_stmt_close($email_exit_result); // Close the prepared statement
    
                }else{
                    echo "max_length";
                    break;
                }
            }else{
                echo "feild_empti";
                break;  
            }
            
        }
        
    }

?>