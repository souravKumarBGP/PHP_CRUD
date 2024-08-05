<?php
    if(!isset($_POST["page_no"])){
        header("Location: ./index.php");
    }
    
    if(isset($_POST["page_no"])){
        include("./db_crud_oops.inc.php");
        // Logic to create object of CRUD_operation_class class
        $CRUD_class_obj = new CRUD_operation_class();

        $limit = 4;
        $offset = ($_POST["page_no"] - 1) * $limit;

        $result_arr = $CRUD_class_obj->select("signup_form_data", "*", "LIMIT {$offset}, {$limit}");

        $contents = "";
        foreach($result_arr as $records){
            $contents.="<tr>
                        <td class='current_id' >{$records['id']}</td>
                        <td>{$records['user_name']}</td>
                        <td>{$records['phone_number']}</td>
                        <td>{$records['email_id']}</td>
                        <td>{$records['gender']}</td>
                        <td><img src='{$records['profile_pic']}' width='80px' height='70px' /></td>
                        <td>
                            
                            <form method='post' action='./edit_registration_info.php' class='me-2 d-inline-block'>
                                <input type='hidden' value='{$records['id']}' name='edit_id' />
                                <button type='submit' class='py-1 px-3 bg-dark'>Edit</button>
                            </form>

                            <form method='post' class='me-2 d-inline-block'>
                                <input type='hidden' value='{$records['id']}' name='delete_id' />
                                <button type='submit' class='py-1 px-3 bg-danger'>Delete</button>
                            </form>
                        
                        </td>
                    </tr>
            ";

            $a = $records['id'];
        }
        
        print_r($contents);

    }



?>