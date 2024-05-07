<?php
include_once("connect.php");
class Menu {
    protected $text;
    protected $sessionID;

    function __construct($text, $sessionId) {
        $this->text = $text;
        $this->sessionID = $sessionId;
    }

    public function mainMenuUnregistered() {
        $response = "CON Welcome to fertilizerhub\n";
        $response .= "1. Register\n";
        echo $response;
    }

    public function menuRegistered($textArray) {
        $level = count($textArray);
        if ($level == 1) {
            echo "CON Set your full name\n";
        } elseif ($level == 2 || $level == 3) {
            echo "CON Set your password\n";
        } elseif ($level == 4) {
            $name = $textArray[1];
            $pin = $textArray[2];
            $confirm_pin = $textArray[3];

            if ($pin != $confirm_pin) {
                echo "END password do not match, please retry\n";
            } else {
                $c=new MyConnection();
                $conn=$c->get_connection();
                $fullname=$textArray[1];
                $pin=$textArray[1];


                $stmt = $conn->prepare("INSERT INTO  farmer (fullname, pin) VALUES (:fullname, :pin)");

                $stmt->bindParam(':fullname', $fullname);
                $stmt->bindParam(':pin', $pin);
              
                $stmt->execute();
               
                echo "END $name, you have successfully registered\n";
            }
        }
    }

    public function mainMenuRegistered() {
        $response = "CON Welcome back to fertilizerhub,\n";
        $response .= "1. request fertilizers\n";
        $response .= "2. account status\n";
        $response .= "3. Change password\n";
        echo $response;
    }

    public function request($textArray) {
        $level = count($textArray);
        if ($level == 1) {
            echo "CON Enter recipient full name\n";
        } elseif ($level == 2) {
            echo "CON Enter phone number\n";
        } elseif ($level == 3) {
            echo "CON Enter amount in kg\n";
        } else {

            $c=new MyConnection();
                $conn=$c->get_connection();
                $fullname=$textArray[1];
                $telephone=$textArray[2];
                $KG=$textArray[3];


            $ins = $conn->prepare("INSERT INTO  farmers (fullname, telephone, KG) VALUES (:fullname, :telephone, :KG)");

                $ins->bindParam(':fullname', $fullname);
                $ins->bindParam(':telephone', $telephone);
                $ins->bindParam(':KG', $KG);
                $ins->execute();

            echo "END {$textArray[1]} sent to {$textArray[2]} successfully\n";
        }
    }

    public function accountstatus($textArray) {
        $level = count($textArray);
        if ($level == 1) {
            echo "CON Enter password\n";
            $c=new MyConnection();
            $conn=$c->get_connection();
            $stmt=$conn->prepare("SELECT * from farmers");
            $stmt->execute();
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($result as $value){
                echo $value['fullname']." ";
                echo $value['telephone']." ";
                echo $value['KG']."\n";
            }

        }
    }

    public function changepassword($textArray) {
        $level = count($textArray);
        if ($level == 1) {
            echo "CON Enter your Current PIN\n";
        }
        elseif ($level == 2) {
            echo "CON Enter new pin\n";
        }
        elseif ($level == 3) {
            echo "CON Confirm new pin\n";
        }
        elseif ($level == 4) {
            $pin = $textArray[2];
            $confirm_pin = $textArray[3];

            if ($pin != $confirm_pin) {
                echo "END pin do not match, please retry\n";
            }
        else {
            echo "END PIN is successfully changed\n";
        }
    }
}
}
?>
