<?php
require($_SERVER['DOCUMENT_ROOT'] . '/../includes/hackathon/connect.php');
require_once("../objects/Account.php");
require_once("../objects/Game.php");
/*
    Check which variables have been given
*/
function contains($array, $keys, $required = -1){ //Required = -1 to require all parameters to be present
    $count = 0;
    foreach($keys as $key){
        if(!array_key_exists($key, $array)){
            if($required == -1){
                return false;
            }
        }else{
            $count++;
        }
    }
    return ($count >= $required);
}
function isAPI($id, $key){
    include($_SERVER['DOCUMENT_ROOT'] . '/../includes/hackathon/connect.php');
    $stmt = $conn->prepare("SELECT * FROM accounts WHERE api_key=? AND id=?");
    $stmt->bindParam(1, $key);
    $stmt->bindParam(2, $id);
    $stmt->execute();
    return $stmt->rowCount() == 1;
}
function isAPIValid($params){
    if(contains($params, ["api_id", "api_key"])){
        return isAPI($params["api_id"], $params["api_key"]);
    }
    return false;
}
function genApiKey(){
    include($_SERVER['DOCUMENT_ROOT'] . '/../includes/hackathon/connect.php');
    $key;
    while(true){
         $key = md5(uniqid());
         $stmt = $conn->prepare("SELECT 1 FROM accounts WHERE api_key=?");
         $stmt->bindParam(1, $key);
         $stmt->execute();
         if($stmt->rowCount() == 0){
             break;
         }
    }
    return $key;
}
function attemptAccount($params){
    $result = -1;
    if(contains($params, ["id"])){
        $result = Account::byId($params['id']);
    }else if(contains($params, ["email"])){
        $result = Account::byEmail($params['email']);
    }else if(contains($params, ["api_key"])){
        $result = Account::byApi($params["api_key"]);
    }
    return $result;
}
function doesGameExist($id){
    $url = "https://store.steampowered.com/api/appdetails?appids=$id&cc=us&l=en";
    $json = file_get_contents($url);
    $object = json_decode($json)->$id;
    return $object->success;
}
?>
