<?php
    class MyConnection 
    {
        public $conn; 

        function __construct(){
            $host="localhost";
            $username="root";
            $password="";
            $dbname="hub";

            try{
                $this->conn = new PDO("mysql:host=$host;dbname=$dbname",$username,$password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            }
            catch(PDOException $e){
                echo"Database connection failed:".$e->getMessage();
            }
        }

        function get_connection() {
            return $this->conn;
        }

        function execute($query=""){
            return $this->get_connection()->exec($query);

        }

        function select($query=""){
            return $this->get_connection()->query($query)->fetchAll();
        }
    }
    