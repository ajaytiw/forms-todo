<?php 

class DB {

    public $host = 'localhost';
    public $user = 'root';
    public $pass = '';
    public $dbname = 'forms-todo';

    public $conn;
    public $last_insert_id;
   

    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        
        if($this->conn->connect_error)
        {
            die('Connection Error:' . $this->conn->connect_error);
        }
    }

    public function santize($var)
    {
        return $this->conn->real_escape_string($var);
    }

    public function query($sql)
    {
        $result = $this->conn->query($sql);
        return $result;
    }

    public function validate_data($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = $this->santize($data);

        return $data;
    }

    public function insert($sql){
        $result = $this->conn->query($sql);
        if($result){
            $last_insert_id = $this->conn->insert_id;
            $this->last_insert_id = $last_insert_id;
            return $last_insert_id;
        }else{          
            return false;
        }
    }

    public function select($sql)
    {
        $result =  $this->conn->query($sql);

        if(mysqli_num_rows($result)>0)
        {
            return $result;
        }else{
            return false;
        }
    }

    public function update($sql)
    {
        $result = $this->conn->query($sql);
        if($result){
            return true;
        }else{          
            return false;
        }
    }

    public function delete($sql)
    {
        $result = $this->conn->query($sql);
        if($result){
            return true;
        }else{          
            return false;
        }
    }

}
