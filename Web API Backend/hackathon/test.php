<?php
include("api/objects/G2A.php");
/*
//730 CSGO
//460930 Ghost Recon Wildlands
$id = 730;
$url = "https://store.steampowered.com/api/appdetails?appids=$id&cc=us&l=en";
$json = file_get_contents($url);
$object = json_decode($json);
var_dump($object->$id->data->pc_requirements);
echo($object->$id->data->name);
echo($object->$id->data->about_the_game);
$screenshots = $object->$id->data->screenshots;
foreach($screenshots as $s){
    $url = $s->path_full;
    echo("<img src='$url'>");
}
echo("<br/><br/><br/><br/>");
//var_dump(json_encode($object, JSON_PRETTY_PRINT));
echo(htmlspecialchars($json));
$array = array();
$array[] = "730";
$array[] = "100";
echo(json_encode($array));
*/
/*
curl -i -XGET "https://sandboxapi.g2a.com/v1/products?page=1&minQty=5" \
>  -H "Authorization: qdaiciDiyMaTjxMt, 74026b3dc2c6db6a30a73e71cdb138b1e1b5eb7a97ced46689e2d28db1050875"
*/
/*
$ch = curl_init();
$url="https://www.g2a.com/en-us/tom-clancys-rainbow-six-siege-year-4-pass-steam-gift-global-i10000178615002";
$server_output = file_get_contents($url);
$re = "/<span class=\"price\">((.|\n)+?)<\/span>/";
preg_match_all($re, $server_output, $output_array);
var_dump($output_array);
echo "Price: ". (double)$output_array[1][0];
*/
$game = G2A::byName("Sniper Elite 4");
var_dump($game);
?>
