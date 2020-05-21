<?php

/*
    GAMELOGIC FILE + COMMUNICATION WITH THE DATABASE
*/

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

//obtain the games base informations (game ID, what player type (A or B) is currently playing)
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

//obtain the precise state of a board
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
                return json_encode($Game->data['playerBView']);
            }
        }
    });

//registering a move in the database, and changing turns
$app->post('/play/{playerType}/{gameID}/{move}',function(ServerRequestInterface $request, ResponseInterface  $response, array $args){
    //to cast a letter as an integer for the purpose of modifying the boards
    $convertion=array('a'=>1,'b'=>2,'c'=>3,'d'=>4,'e'=>5,'f'=>6,'g'=>7,'h'=>7,'A'=>1,'B'=>2,'C'=>3,'D'=>4,'E'=>5,'F'=>6,'G'=>7,'H'=>7);
    $GameState=new GameState();
    $GameState->setTable($args['gameID']);
    if(!$GameState->find(2)){
        //creation of the row dedicated to the game
        $Game=$GameState->find(1);
        //update of the boards, since it is always player A that begins to play, we are assured of the fact he will be the one to make a move first
        $newview=$Game->data['playerAView'];
        $newview[$args['move'][1]][$convertion[$args['move'][0]]]=2;
        $newboard=$Game->data['playerBBoard'];
        $newboard[$args['move'][1]][$convertion[$args['move'][0]]]=2;
        $GameID=$args['gameID'];
        ActiveRecord::execute("INSERT INTO Game_$GameID VALUES(1,\"$Game->data['playerABoard']\",\"$newboard\",\"$newview\",\"$Game->data['playerBView']\",2);");
    }
    else{
        $GameState->find(2);
        if($args['playerType']=='a'){
            $newview=$Game->data['playerAView'];
            $newview[$args['move'][1]][$convertion[$args['move'][0]]]=2;
            $newboard=$Game->data['playerBBoard'];
            $newboard[$args['move'][1]][$convertion[$args['move'][0]]]=2;

            $GameState->playerTurn=2;
            $GameState->PlayerBBoard=$newboard;
            $GameState->PlayerAView=$newview;
            $GameState->update();
        }
        else{
            $newview=$Game->data['playerBView'];
            $newview[$args['move'][1]][$convertion[$args['move'][0]]]=2;
            $newboard=$Game->data['playerABoard'];
            $newboard[$args['move'][1]][$convertion[$args['move'][0]]]=2;

            $GameState->playerTurn=1;
            $GameState->PlayerABoard=$newboard;
            $GameState->PlayerBView=$newview;
            $GameState->update();
        }
    }

    return "clear";
});

//check whose turn it is or if the game has ended
$app->get('/update/{gameID}',function($request, $response, array $args){
    $GameState=new GameState();
    $GameState->setTable($args['gameID']);
    $Game=$GameState->find(2);
    $turn='';

    if($Game->data['playerTurn']=='1'){
        $turn= 'A';
    }
    else if($Game->data['playerTurn']=='2'){
        $turn= 'B';
    }
    else if($Game->data['playerTurn']=='3'){
        return 'The Victor of this battle is player A!';
    }
    else if($Game->data['playerTurn']=='4'){
        return 'The Victor of this battle is player B!';
    }

    //Checking if there is a victor
    $board= json_decode($Game->data['playerABoard']);
    $gameStatus=false;
    for ($i=0; $i < 8; $i++) { 
        for ($j=0; $j < 8; $j++) { 
            if($board[$i][$j]==1){
                $gameStatus=true;
            break;
            }
        }
    }
    if($gameStatus==false){
        $turn= 'The Victor of this battle is player B!';
        $GameState->playerTurn=4;
        $GameState->update();
    }
    $board= json_decode($Game->data['playerBBoard']);
    $gameStatus=false;
    for ($i=0; $i < 8; $i++) { 
        for ($j=0; $j < 8; $j++) { 
            if($board[$i][$j]==1){
                $gameStatus=true;
            break;
            }
        }
    }
    if($gameStatus==false){
        $turn= 'The Victor of this battle is player A!';
        $GameState->playerTurn=3;
        $GameState->update();
    }



    return $turn;
});

$app->run();
?>