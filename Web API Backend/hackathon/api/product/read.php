<?php
require($_SERVER['DOCUMENT_ROOT'] . '/../includes/hackathon/connect.php');
require_once("../objects/Account.php");
require_once("../config/tools.php");
require_once("../objects/Game.php");

//Convert all POST params to an array
$params = array();
foreach($_GET as $name => $value){
    $params[$name] = $value;
}
//Validate API key, unless they are logging in
if(!contains($params, ["authenticate"])){
    if(!isAPIValid($params)){
        echo(json_encode("Invalid API key"));
        exit;
    }
}


/*
    READ API
*/
if(contains($params, ["authenticate"])){
    $result = new stdClass();
    if(contains($params, ["id", "email", "api_key", "password"], 2)){
        $result = attemptAccount($params);
        if(gettype($result) == "integer" || !$result->authenticate($params["password"])){
            $result = new stdClass();
            $result->status = 'Invalid email or password';
        }else{
            $result->status = "success";
        }
    }else{
        $result->status = "Missing fields";
    }
    echo(json_encode($result));
}else if(contains($params, ["account"])){
    $result = "";
    if(contains($params, ["api_id", "api_key"])){
        $account = attemptAccount($params);
        if(gettype($account) == "integer"){
            $result = new stdClass();
            $result->status = "Account not found";
        }else{
            $result = $account;
            $result->status = "success";
        }
    }else{
        $result = new stdClass();
        $result->status = "Missing fields";
    }
    echo(json_encode($result));
}else if(contains($params, ["game"])){
    if(contains($params, ["id"])){
        $result = Game::byId($params["id"]);
        unset($result->last_update);
        if($result == "-1"){
            $result = new stdClass();
            $result->status = "error";
        }

        //TEST
        $t = $result;

        echo((json_encode($t)));
    }
}else if(contains($params, ["search"])){
    if(contains($params, ["query"])){
        $result = Game::byName($params["query"]);
        if(gettype($result) != "integer"){
            $result->status = "success";
        }else{
            $result = new stdClass();
            $result->status = "No game not found";
        }
    }else{
        $result = new stdClass();
        $result->status = "Missing field";
    }
    echo(json_encode($result));
}
?>
