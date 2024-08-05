<?php
    // Logic to check session is start or not if session is not start then start the session
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    // Logic to check user is login or not if user is login then user regirect on login page
    if(!isset($_SESSION["CRUD_login_user"])){
        header("Location: ./login.php");
    }

    include("./db_connection.php");

    // Logic to fetch records from database 
    $select_querry = "SELECT * FROM signup_form_data";
    $result = mysqli_query($conn, $select_querry);
    $result_arr = [];
    while($records = mysqli_fetch_assoc($result)){
        $result_arr[] = $records;
    }

    // Logic to delete record from database when user click on delete button
    if(isset($_POST["delete_id"])){
        mysqli_query($conn, "DELETE FROM signup_form_data WHERE id = {$_POST["delete_id"]}");
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
                <tbody>
                    
                    <?php
                       
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
                    
                    
                    ?>

                </tbody>
            </table>
        </section>

        <!-- ********************* External file link's ****************************** -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    </body>
</html>