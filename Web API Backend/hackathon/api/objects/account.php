<?php
class Account{
    //id
    //email
    //base_price
    //sale_percent
    //screenshot_urls
    //description
    public function __construct(Array $fields = array(), $safe=true){
        foreach($fields as $key => $value){
            $this->{$key} = $value;
        }
        if($safe){
            unset($this->password);
        }
    }
    public static function byEmail($email){
        include($_SERVER['DOCUMENT_ROOT'] . '/../includes/hackathon/connect.php');
        $stmt = $conn->prepare("SELECT * FROM accounts WHERE email=?");
        $stmt->bindParam(1, $email);
        $stmt->execute();
        if($stmt->rowCount() == 0){
            return -1;
        }
        return new Account($stmt->fetch(PDO::FETCH_ASSOC));
    }
    public static function byId($id){
        include($_SERVER['DOCUMENT_ROOT'] . '/../includes/hackathon/connect.php');
        $stmt = $conn->prepare("SELECT * FROM accounts WHERE id=?");
        $stmt->bindParam(1, $id);
        $stmt->execute();
        if($stmt->rowCount() == 0){
            return -1;
        }
        return new Account($stmt->fetch(PDO::FETCH_ASSOC));
    }
    public static function byApi($api_key){
        include($_SERVER['DOCUMENT_ROOT'] . '/../includes/hackathon/connect.php');
        $stmt = $conn->prepare("SELECT * FROM accounts WHERE api_key=?");
        $stmt->bindParam(1, $api_key);
        $stmt->execute();
        if($stmt->rowCount() == 0){
            return -1;
        }
        return new Account($stmt->fetch(PDO::FETCH_ASSOC));
    }
    public function authenticate($p){
        include($_SERVER['DOCUMENT_ROOT'] . '/../includes/hackathon/connect.php');
        //This account variable must already exist
        if(empty($this->id)){
            echo("EMPTY");
            return false;
        }
        $stmt = $conn->prepare("SELECT * FROM accounts WHERE id=?");
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        if($stmt->rowCount() == 0){
            echo("WOOOH");
            return false;
        }
        $hash = substr($stmt->fetch(PDO::FETCH_ASSOC)['password'], 0, 60);
        return password_verify($p, $hash);
    }
}
?>
