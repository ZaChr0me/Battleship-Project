<?php
require __DIR__ . '/vendor/autoload.php';
ActiveRecord::setDb(new PDO('sqlite:BattleShip.db'));

class GameStorage extends ActiveRecord{
    public $table = 'GameStorage';
  public $primaryKey = 'ID';
}

$app = new \Slim\App();
$app->get(
    '/{id}',
    function($request, $response, array $args){
        $PlayerID=$args['id'];

        $GameStorageQuery=new GameStorage();
        $Game=$GameStorageQuery->findAll();
        //since $GameStorageQuery->where('Playera=$PlayerID')->find() nor ->find_by_PlayerA() never worked, the brute method of iterating through the entire game list...
        //it isn't optimal, but works...

        for($i=0; $i<sizeof($Game); $i++){
            echo "searching</br>";
            if($Game[$i]->data['PlayerA']==$PlayerID){
                echo "Found it! " . $Game[$i]->data['ID'];
            break;
            }
            if($Game[$i]->data['PlayerB']==$PlayerID){
                echo "Found it! ".$Game[$i]->data['ID'];
            break;
            }
        }
    }
);
$app->run();
?>