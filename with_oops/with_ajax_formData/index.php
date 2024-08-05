<?php
    // Logic to check session is start or not if session is not start then start the session
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    // Logic to check user is login or not if user is login then user regirect on login page
    if(!isset($_SESSION["CRUD_login_user"])){
        header("Location: ./login.php");
    }

    include("./db_crud_oops.inc.php");
    // Logic to create object of CRUD_operation_class class
    $CRUD_class_obj = new CRUD_operation_class();
    $result_arr = $CRUD_class_obj->select("signup_form_data", "*", "LIMIT 4");

    // Logic to create pagination 
    $pagination_result = $CRUD_class_obj->select("signup_form_data");
    $limt = 4;
    $per_page = ceil(count($pagination_result) / $limt);

    // Logic to delete record from database when user click on delete button
    if(isset($_POST["delete_id"])){
        $deiete_id  = $CRUD_class_obj->conn->real_escape_string($_POST["delete_id"]);
        $CRUD_class_obj->delete("signup_form_data", "id = $deiete_id");
        header("location: ./index.php");
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
            <div class="container">
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

        <!-- *********************** Start create table to show data ******************* -->
        <section class="container">
            <table style="margin: 40px auto;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Students Email</th>
                        <th>Studnets Phone</th>
                        <th>Studnets Gender</th>
                        <th>Students image</th>
                        <th>Action Buttons</th>
                    </tr>
                </thead>
                <tbody id="tBody">
                    
                    <?php

                        if($CRUD_class_obj->total_rows != 0){// Logic to check recores is present or not if recores is present then show all record in table formate
                            foreach($result_arr as $records){
                                echo"
                                    <tr>
                                        <td>{$records['id']}</td>
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
                            }
                        }else{
                            echo "<h2>No records</h2>";
                        }
                    
                    ?>

                </tbody>
            </table>
        </section>

        
        
        <nav class="pagination">
            <ul>

                <?php
                    $page_btn = "";
                    if($per_page >= 1){
                        for($i = 1; $i <= $per_page; $i++){
                            $page_btn .= "<li class='pageButton'>{$i}</li>";
                        }
                    }
                    $page_btn .= "<li class='nextBtn'>Next <i class='fa-solid fa-caret-right'></i></li>";
                    echo $page_btn;
                ?>
                
            </ul>
        </nav><!--***End of pagination-->

        <!-- ********************* External file link's ****************************** -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="./js/jquery-3.7.1.min.js"></script>
        <script>
            // Logic to make a ajax request when user click on paenation button
            $(document).ready(function(){
                $(".pageButton").first().addClass("activeBtn");

                $(".pageButton").on("click", function(){
                    var page_no = $(this).text();
                    $.ajax({
                        url: "pagination_request.php",
                        type: "post",
                        data: {"page_no": page_no},
                        success: function(responce){
                            $("#tBody").html(responce);                 ;
                        }
                    });
                    
                    
                    // Logic to apply activeBtn class when user click on pagination button
                    
                    setTimeout(() => {

                        $(".pageButton").removeClass("activeBtn")
                        
                        let limit = 4;
                        let currentPage = Math.ceil($(".current_id").last().html() / limit);

                        if(page_no == currentPage){
                            $(this).addClass("activeBtn");
                        }
                    }, 50);
                    

                });
                
            });
        </script>

        

    
    </body>
</html>