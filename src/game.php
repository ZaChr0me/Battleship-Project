<?php
require '../vendor/autoload.php';
use Slim\Views\Twig;
use \Slim\Views\TwigExtension;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

ActiveRecord::setDb(new PDO('sqlite:../database/BattleShip.db'));
class GameStorage extends ActiveRecord{
    public $table = 'GameStorage';
  public $primaryKey = 'ID';
}

class GameState extends ActiveRecord{
    public $table='Game_';
    public $primaryKey = 'rowid';
    public function setTable($id){
        $this->table=$this->table.$id;
    }
}

$app = new \Slim\App();
//$twig = new \Twig\Environment($loader, ['debug' => true]);
$container = $app->getContainer();
$container['view'] = function ($container) {
    $view = new Twig(__DIR__.'/../templates', [
        'cache' => false
    ]);

    // Instantiate and add Slim specific extension
    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new TwigExtension($router, $uri));
    
    return $view;
};

//First Entry
$app->get(
    '/init/{playerid}',
    function($request, $response, array $args){
        $PlayerID=$args['playerid'];
        //the values to pass to the gameView
        $Attributes=array();
        
        $GameStorageQuery=new GameStorage();
        $AllGames=$GameStorageQuery->findAll();
        //since $GameStorageQuery->where('Playera=$PlayerID')->find() nor ->find_by_PlayerA() never worked, the brute method of iterating through the entire game list...
        //it isn't optimal, but works...
        $found=false;
        $iterator=0;
        while(!$found){
            
            if($AllGames[$iterator]->data['PlayerA']==$PlayerID){
                $Attributes['playerType']='A';
                $Attributes['gameID']=$AllGames[$iterator]->data['ID'];
                $found=true;
            }
            if($AllGames[$iterator]->data['PlayerB']==$PlayerID){
                $Attributes['playerType']='B';
                $Attributes['gameID']=$AllGames[$iterator]->data['ID'];
            $found=true;
            }
            $iterator=$iterator+1;
        }
        //1rst row contains initial state
        /*
        $Attributes['board']=json_decode($Attributes['board']);

        for($i=0;$i<8;$i++){
            for($j=0;$j<8;$j++){
            echo $Attributes['board'][$i][$j]."\n";
            }
            echo "</br>"; 
        }
        */
        return json_encode($Attributes);
    }
);

$app->get('/board/{gameID}/{playerType}/{level}/{board}',
    function($request, $response, array $args){
        $GameState=new GameState();
        
        $GameState->setTable($args['gameID']);
        $Game=$GameState->find($args['level']);
        if($args['playerType']=='A'){
            if($args['board']=='A'){
                return json_encode($Game->data['playerABoard']);
            }
            else{
                return json_encode($Game->data['playerAView']);
            }
        }
        else{
            if($args['board']=='B'){
                return json_encode($Game->data['playerBBoard']);
            }
            else{
                return json_encode($Game->data['playerAView']);
            }
        }
    });

$app->get('/play/{playerType}/{gameID}',function(ServerRequestInterface $request, ResponseInterface  $response, array $args){
    
    return $args['playerType'];
});


$app->run();
?>