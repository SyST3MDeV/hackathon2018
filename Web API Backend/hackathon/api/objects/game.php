<?php
class Game{
    public function __construct(Array $fields = array()){
        foreach($fields as $key => $value){
            $this->{$key} = $value;
        }
    }
    public static function byId($id){
        include($_SERVER['DOCUMENT_ROOT'] . '/../includes/hackathon/connect.php');
        include_once('../config/tools.php');
        if(!doesGameExist($id)){
            return -1;
        }
        $stmt = $conn->prepare("SELECT * FROM games WHERE game_id=?");
        $stmt->bindParam(1, $id);
        $stmt->execute();
        if($stmt->rowCount() == 0){
            Game::createGame($id);
            return Game::byId($id);
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $datetime = new DateTime(date("Y-m-d H:i:s"));
        $odt = date_create_from_format("Y-m-d H:i:s", $result['last_update']);

        if($odt->diff($datetime)->i >= 15){
            Game::createGame($id, true);
            return Game::byId($id);
        }
        return new Game($result);
    }
    private static function createGame($id, $del=true){
        if(!doesGameExist($id)){
            //Game DNE
            return -1;
        }
        include($_SERVER['DOCUMENT_ROOT'] . '/../includes/hackathon/connect.php');
        //Delete old entry if it exists
        if($del){
            $stmt = $conn->prepare("DELETE FROM games WHERE game_id=?");
            $stmt->bindParam(1, $id);
            $stmt->execute();
        }
        $url = "https://store.steampowered.com/api/appdetails?appids=$id&cc=us&l=en";
        $json = file_get_contents($url);
        $object = json_decode($json)->$id->data;
        $name = $object->name;
        $description = $object->short_description;
        $header = $object->header_image;
        $screenshots = json_encode($object->screenshots);
        $requirements = json_encode($object->pc_requirements);
        $price = -1;
        $discount_percent = -1;
        if(isset($object->price_overview)){
            $price = $object->price_overview->initial;
            $discount_percent = $object->price_overview->discount_percent;
        }else{
            if($object->is_free == 'true'){
                $price = "Free";
            }
            $price = $object->is_free;
        }
        $dtime = date("Y-m-d H:i:s");
        $stmt = $conn->prepare("INSERT INTO games (game_id, name, price, discount_percent, header_image, screenshots, description, requirements, last_update) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $id);
        $stmt->bindParam(2, $name);
        $stmt->bindParam(3, $price);
        $stmt->bindParam(4, $discount_percent);
        $stmt->bindParam(5, $header);
        $stmt->bindParam(6, $screenshots);
        $stmt->bindParam(7, $description);
        $stmt->bindParam(8, $requirements);
        $stmt->bindParam(9, $dtime);
        $stmt->execute();
    }
    public static function byName($name){
        $url = "https://store.steampowered.com/search/?term=" . str_replace(" ", "+", $name);
        $page = new DomDocument;
        $page->validateOnParse = true;
        @$page->loadHtml(file_get_contents($url));
        $finder = new DomXPath($page);
        $classname = "search_result_row";
        $results = $finder->query("//a[contains(@class, '$classname')]/@data-ds-appid");
        $values = array();
        $i = 0;
        foreach($results as $result){
            if(++$i > 4) break;
            $id = $result->value;
            $game = Game::byId($id);
            $values[] = $game;
        }
        $f = new stdClass();
        $f->results = $values;
        return $f;
    }
}
