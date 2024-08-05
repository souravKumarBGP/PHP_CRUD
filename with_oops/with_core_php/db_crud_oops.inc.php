<?php

    // Logic to create a class to perform CRUD operation 
    class CRUD_operation_class{

        // Logic to define a variables becaust create a connection from database
        private $host_name = "localhost";
        private $user_name = "root";
        private $password = "";
        private $db_name = "crud_oop_with_core_php";
        public $conn;
        public $total_rows;

        // Logic to create connection with define deatabae
        public function __construct(){
            $this -> conn = new mysqli($this->host_name, $this->user_name, $this->password, $this->db_name);

            if(!$this -> conn){
                die("DB connection is failed !".$this -> conn -> error); // Logic to check connection is success or failed
            }
        }

        // Logic to create insert method because execute sql insert querry
        public function insert($table_name, $param = []){

            if($this->table_exists($table_name)){ // Logic to check table is exit if table is exit then execute this block

                $param = $this->sanitize_data($param); // Coll the sanitize_data method
                
                $column_name = implode(", ", array_keys($param)); // Logic to create comumns name of table
                $values = implode("' , '", $param); // Logic to create values of comumn 
                
                $insert_querry = "INSERT INTO signup_form_data ({$column_name}) VALUES ('{$values}') ";

                $result = $this->conn->query($insert_querry);

                if($result){ // Logic to check data is success fully inserte then return true else false
                    return true;
                }else{
                    return false;
                }  
            }else{
                echo "This table is not exit !";
            }
        }

        // Logic to create update method because execute sql update querry
        public function update($table_name, $param, $where_clouse = null){
            
            // Logic to check table is exit or not if table is exit then execute the block else retur a error message
            if($this->table_exists($table_name)){
                $param = $this->sanitize_data($param); // Coll the sanitize_data method

                $set_data = "";
                foreach($param as $key => $values){
                    $set_data .= "{$key} = '{$values}', ";
                }
                $set_data = substr(trim($set_data), 0, -1); // logic to remove extera coma and spaces

                if($where_clouse != null){
                    $update_querry = "UPDATE {$table_name} SET {$set_data} WHERE {$where_clouse}"; // Logic to write update querry
                }else{
                    $update_querry = "UPDATE {$table_name} SET {$set_data}"; // Logic to write update querry
                }
                $result = $this->conn->query($update_querry);

                if($result){ // if update querry is successfully execute then return true else false
                    return true;
                }else{
                    return false;
                }

            }else{
                echo "Table is not exit !";
            }
            
        }

        // Logic to create delete method because execute sql update querry
        public function delete($table_name, $where_clouse = null){

            // Logic to check table is exit or not if table is not exist 
            if($this->table_exists($table_name)){

                if($where_clouse != null){
                    $delete_querry = "DELETE FROM {$table_name} WHERE {$where_clouse}";
                }else{
                    $delete_querry = "DELETE FROM {$table_name}";
                }
                
                $result = $this->conn->query($delete_querry);
                if($result){ // if delete querry is successfully execute then return true else false
                    return true;
                }else{
                    return false;
                }
                
            }else{
                echo "Table is not exist !";
            }
            
        }

        // Logic to create select method because execute sql select querry
        public function select($table_name, $column_name = "*", $condition = null){

            // Logic to check table is exit or not if table is not exit then show error
            if($this->table_exists($table_name)){

                if($condition != null){
                    $select_querry = "SELECT {$column_name} FROM {$table_name} {$condition}";
                }else{
                    $select_querry = "SELECT {$column_name} FROM {$table_name}";
                }
                $result = $this->conn->query($select_querry); // Logic to execute select querry

                if($result->num_rows > 0){
                    $result_arr = $result->fetch_all(MYSQLI_ASSOC);
                    $this->total_rows = $result->num_rows;
                    return $result_arr;
                }else{
                    $this->total_rows = $result->num_rows;
                }
                
            }else{
                echo "Table is not exist";
            }
        }

        // Logic to create sanitize_data method because to protact from sql injection
        public function sanitize_data($param){

            // Logic to sanitize array key and values
            $param_sanitize = [];
            foreach($param as $key => $values){
                
                if($key != "new_password"){
                    $param_sanitize[$key] = $this->conn->real_escape_string(trim($values));
                }else{
                    $param_sanitize[$key] = password_hash(trim($values), PASSWORD_DEFAULT);
                }
            }

            return $param_sanitize;
        }

        // Logic to create a method because to check table is exist or not
        public function table_exists($table_name){
            $table_name = $this->conn->real_escape_string($table_name); // sanitize table name

            $sql_querry = "show tables FROM CRUD_WITH_CORE_PHP LIKE '%{$table_name}%'";

            $result = $this->conn->query($sql_querry);
            
            if($result->num_rows > 0){ // Logic to check table is exit or not if table is exit then return true else false
                return true;
            }else{
                return false;
            }
            
        }

    }

?>

