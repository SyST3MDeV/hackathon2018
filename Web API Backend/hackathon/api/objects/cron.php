<?php
require($_SERVER['DOCUMENT_ROOT'] . '/../includes/hackathon/connect.php');
include_once("Account.php");
require_once('Game.php');
$stmt = $conn->prepare("SELECT * FROM games");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $row){
    continue;
    $id = $row['game_id'];
    $a = Game::createGame($id);
}

//Update featured games
$url = "http://store.steampowered.com/api/featured/";
$json = file_get_contents($url);
$games = json_decode($json)->featured_win;
$output = "";
for($i = 0; $i < count($games); $i++){
    $output .= $games[$i]->id .  ";";
}
$output = substr($output, 0, -1);
file_put_contents("../../featured.html", $output);

?>
