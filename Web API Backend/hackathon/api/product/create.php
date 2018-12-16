<?php
require($_SERVER['DOCUMENT_ROOT'] . '/../includes/hackathon/connect.php');
require_once("../objects/Account.php");
require_once("../config/tools.php");

//Convert all POST params to an array
$params = array();
foreach($_GET as $name => $value){
    $params[$name] = $value;
}
//Validate API key, unless they are logging in
if(!contains($params, ["account"])){
    if(!isAPIValid($params)){
        echo(json_encode("Invalid API key"));
        exit;
    }
}


/*
    CREATE API
*/
if(contains($params, ["account"])){
    $result = new stdClass();
    if(contains($params, ["email", "password"])){
        //Check if email is taken
        $acc = Account::byEmail($params["email"]);
        if(gettype($acc) != "integer"){
            //An account with this email already exists
            $result = new stdClass();
            $result->status = "This email is already taken";
        }else{
            $options = ['cost' => 11];
            $hash = password_hash($params['password'], PASSWORD_BCRYPT, $options);
            $api_key = genApiKey();
            $stmt = $conn->prepare("INSERT INTO accounts (email, password, api_key, wishlist) VALUES (?, ?, ?, '')");
            $stmt->bindParam(1, $params["email"]);
            $stmt->bindParam(2, $hash);
            $stmt->bindParam(3, $api_key);
            $stmt->execute();
            $id = $conn->lastInsertId();
            $result = Account::byId($id);
            $result->status = "success";
        }
    }else{
        $result->status = "Missing fields";
    }
    echo(json_encode($result));
}
