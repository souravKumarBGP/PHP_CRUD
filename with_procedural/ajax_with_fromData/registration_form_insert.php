<?php

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
                    $email_exixt = "SELECT email_id FROM signup_form_data WHERE email_id = '{$form_data_array['email_id']}'";
                    $email_exixt_result = mysqli_query($conn, $email_exixt);
                    if(mysqli_num_rows($email_exixt_result) == 0){
    
                        // Logic to check check valid phone number or not
                        if(strlen($form_data_array["phone_number"]) == 10){
    
                            $img_path = "./uploded_img/".$_FILES["profile_pic"]["name"];
                            move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $img_path);
                            
                            $column_names = implode(", ", array_keys($form_data_array));
                            $values = implode("', '", $form_data_array);
                            // Lotic to insert form data into database when user submit signup from 
                            $insert_querry = "INSERT INTO signup_form_data ({$column_names}, profile_pic) VALUES('{$values}', '{$img_path}')";
                            $result = mysqli_query($conn, $insert_querry);
    
                            if($result){
                                echo "Successfully";
                                break;
                            }else{
                                echo $result;
                                break;
                            }
    
                        }else{
                            echo "invlide_phone_no";
                            break;
                        }
                        
    
                    }else{
                        echo "email_exist";
                        break;
                    }
    
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