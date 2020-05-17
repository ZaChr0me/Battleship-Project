<?php
require __DIR__ . '/vendor/autoload.php';
ActiveRecord::setDb(new PDO('sqlite:BattleShip.db'));

class GameStorage extends ActiveRecord{
    public $table = 'GameStorage';
  public $primaryKey = 'ID';
}
$GameStorageQuery=new GameStorage();
$app = new \Slim\App();
$app->get(
    '/{id}',
    function($request, $response, array $args){
        //$PlayerID=bin2hex($args['id']);
        //echo $PlayerID;
        echo "</br>";
        $PlayerID=GameStorage::find_by_player1($args['id']);
        echo $PlayerID->ID;
        echo $PlayerID->player1;
        echo $PlayerID->player2;
        //header('Location: http://stackoverflow.com');
    }
);
$app->run();
//if (isset($_POST['join'])||)



?>