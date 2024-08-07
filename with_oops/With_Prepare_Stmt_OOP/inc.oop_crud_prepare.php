<?php 

    // Logic to create a curd class 
    class Crud{
        private $host_name = "localhost";
        private $root_name = "root";
        private $password = "";
        private $db_name = "testing";
        public $conn;

        // Logic to create connection with database
        function __construct($host_name = "localhost", $root_name = "root", $password = "", $db_name = "testing"){

            $this->host_name = $host_name;
            $this->root_name = $root_name;
            $this->password = $password;
            $this->db_name = $db_name;
            
            $this->conn = new mysqli($host_name, $root_name, $password, $db_name);
            // Assuming $this->conn is your mysqli connection object
            if ($this->conn->connect_error) {
                // Connection failed, output the error message and stop execution
                die("Db connection failed: " . $this->conn->connect_error);
            }
        }

        // Logic to create a insert method 
        function insert($table_name, $params_array = []){

            // Logic to check table is exit or not if table is exit then perform insert operation
            if($this->exit_table($table_name)){
                
                // Logic to check parameters is passed or not if parameters is not passed then show error
                if(!empty($params_array)){

                    // Create column names from array keys
                    $columns = implode(", ", array_keys($params_array));

                    // Extract values from array
                    $values_of_array = array_values($params_array);

                    // Create placeholders for the values
                    $placeholder = implode(", ", array_fill(0, count($values_of_array), "?"));

                    // Build the insert query
                    $insert_query = "INSERT INTO {$table_name} ({$columns}) VALUES ({$placeholder})";

                    // Prepare the statement
                    $stmt = $this->conn->prepare($insert_query);

                    // Determine the types for bind_param
                    $type_string = str_repeat('s', count($values_of_array));

                    // Bind parameters dynamically
                    $stmt->bind_param($type_string, ...$values_of_array);

                    // Execute the query
                    $result = $stmt->execute();

                    // Check the result
                    if ($result) {
                        return "Data inserted successfully";
                    } else {
                        return "Error: " . $this->conn->error;
                    }

                    // Logic to close the statement
                    $stmt->close();
                    
                }else{
                    echo "Enter your columns and values in assocative array example:- ['user_name'=>'sourav Kumar']";
                }

            }else{
                echo "Table is not exit in current database !";
            }
        }

        // Logic to create a update methods
        function update($table_name, $params_array = [], $condition = null){

            // Logic to check table is exixt or not if table is not exixt then return a error message
            if($this->exit_table($table_name)){

                // Logic to check parametors is empty or not if parametors is empty then show error message
                if(!empty($params_array)){
                    
                    $set_values = "";
                    foreach($params_array as $key => $value){
                        $set_values .= "$key = ?, ";
                    }
                    $set_values = substr($set_values, 0, -2); // Logic to remove extera comma

                    // Extract values from array
                    $values_of_array = array_values($params_array);

                    // Logic to determine the type for bind_param
                    $type_string = str_repeat("s", count($values_of_array));

                    // Logic to check condition are hase or not if condition is not has then run else part
                    if($condition != null){
                        // Logic to buld update querry
                        $update_querry = "UPDATE {$table_name} SET {$set_values} $condition";

                        // Logic to build statement
                        $stmt = $this->conn->prepare($update_querry);

                        // Logic to bind parameters dynamically
                        $stmt->bind_param($type_string, ...$values_of_array);

                        // Logic to execute the querry
                        $result = $stmt->execute();

                        if($result && $stmt->affected_rows > 0){
                            return "Data updated successfully";
                        }else{
                            return false;
                        }

                        // Logic to close the statement
                        $stmt->close();
                    
                    }else{
                        // Logic to create update querry
                        $update_querry = "UPDATE {$table_name} SET {$set_values}";

                        // Logic to build statements
                        $stmt = $this->conn->prepare($update_querry);

                        // Logic to bind parameters dynamically
                        $stmt->bind_param($type_string, ...$values_of_array);

                        // Logic to execute statement
                        $result = $stmt->execute();

                        if($result && $stmt->affected_rows>0){
                            return "Data updated successfully";
                        }else{
                            return false;
                        }

                        // Logic to close the statement
                        $stmt->close();
                    
                    }

                    
                }else{
                    echo "Enter your columns and values in assocative array example:- ['user_name'=>'sourav Kumar']";
                }
                
                
            }else{
                echo "This table is not exixt !";
            }
        }

        // Logic to create delete method
        function delete($table_name, $column_name = "", $value = ""){

            // Logic to check table is exixt or not if table is not exixt then return a error message
            if($this->exit_table($table_name)){

                // Logic to check condition are hase or not if condition is not has then run else part
                if($column_name != "" && $value != ""){
                    // Logic to create UPDATE querry
                    $delete_querry = "DELETE FROM {$table_name} WHERE {$column_name} = ?";

                    // Logic to build prepare statement
                    $stmt = $this->conn->prepare($delete_querry);
                    
                    // Logic to bind parameters 
                    $stmt->bind_param("i", $value);

                    // Logic to execute statement
                    $result = $stmt->execute();

                    if($result && $stmt->affected_rows > 0){
                        return "Data Deleted successfully";
                    }else{
                        return false;
                    }

                    // Logic to close the statement
                    $stmt->close();
                    
                }else{

                    // Logic to create UPDATE querry
                    $delete_querry = "DELETE FROM {$table_name}";
                    
                    // Logic to execute update querry
                    $stmt = $this->conn->prepare($delete_querry);
                    
                    // Logic to execute statement
                    $result = $stmt->execute();

                    if($result && $stmt->affected_rows > 0){
                        return "Data Deleted successfully";
                    }else{
                        return false;
                    }

                    // Logic to close the statement
                    $stmt->close();
                }


            }else{
                echo "Table is not exit in current database !";
            }
        }

        // Logic to create select method
        function select($table_name, $condition = null){
            // Logic to check table is exit or not if table is exit then perform insert operation
            if($this->exit_table($table_name)){
                            
                // Logic to check condition has or not if condition is not has then execute false block
                if($condition != null){
                    $select_querry = "SELECT * FROM {$table_name} {$condition}";
                    $result = $this->conn->query($select_querry);
                    //Logic to check querry is execute or not if querry is not execute then return error
                    if($result){
                        if($result->num_rows > 0){
                            return $result->fetch_all(MYSQLI_ASSOC);
                        }else{
                            echo "No records !";
                            return false;
                        }
                    }else{
                        return false;
                    }
                }else{
                    $select_querry = "SELECT * FROM {$table_name}";
                    $result = $this->conn->query($select_querry);
                    //Logic to check querry is execute or not if querry is not execute then return error
                    if($result){
                        if($result->num_rows > 0){
                            return $result->fetch_all(MYSQLI_ASSOC);
                        }else{
                            echo "No records !";
                            return false;
                        }
                    }else{
                        return false;
                    }
                }


            }else{
                echo "Table is not exit in current database !";
            }
        }

        // Logic to check table is exit or not if table is exit then return true
        function exit_table($table_name){
            $show_table_querry = "SHOW TABLES FROM {$this->db_name} LIKE '{$table_name}'";
            if($this->conn->query($show_table_querry)->num_rows == 1){
                return true;
            }else{
                return false;
            }
        }

        
        // Logic to close database connection
        function __destruct(){
            $this->conn->close();
        }
        
    }

    // Logic to create object
    $obj = new Crud();


?>