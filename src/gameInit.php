<?php
    require '../vendor/autoload.php';
    ActiveRecord::setDb(new PDO('sqlite:../database/BattleShip.db'));
    
    class GameStorage extends ActiveRecord{
      public $table = 'GameStorage';
      public $primaryKey = 'ID';
    }
    
    //This generates 2 unique IDs for players to join their respective game page.
    //While the following may seem overkill, since random_bytes() has a low chance of duplicate, the chance still exist
    //thus, the loop is expected to run no more than twice or thrice even with high numbers of recorded games
    $GameStorageQuery=new GameStorage();
    $Game=$GameStorageQuery->findAll();
    
    $Unique=false;
    do{
      $Unique=true;
      $Player1ID=bin2hex(random_bytes(5));
      $Player2ID=bin2hex(random_bytes(5));
      for($i=0; $i<sizeof($Game); $i++){
        if($Game[$i]->data['PlayerA']==$Player1ID 
        || $Game[$i]->data['PlayerB']==$Player1ID
        || $Game[$i]->data['PlayerA']==$Player2ID 
        || $Game[$i]->data['PlayerB']==$Player2ID){
            $Unique=false;
        break;
        }      
      }
    }while(!$Unique);

    $GameID=count($Game);
    //Adding the game to the Game Records
    echo $Player1ID." ".$Player2ID;
    $NewGame_Query=new GameStorage();
    $NewGame_Query->ID=$GameID;
    $NewGame_Query->PlayerA=$Player1ID;
    $NewGame_Query->PlayerB=$Player2ID;
    $NewGame_Query->insert();
    
    //Creating the game itself
    
    ActiveRecord::execute("CREATE TABLE IF NOT EXISTS Game_$GameID (
                              storageID INTEGER,
                              playerABoard TEXT,
                              playerBBoard TEXT,
                              playerAView TEXT,
                              playerBView TEXT,
                              playerTurn INTEGER
                          )");
    //storageID REFERENCE [0:Field Templates],[1:Player Action],[2:Game End State]
    //playerABoard and playerBBoard are the players board
    //playerAView and playerBView are the vision of the board of their opponents by the player
    //playerTurn is the id of the player whose turn it is.
    //
    
    //Loading the Game Templates
    /*Game Templates Properties : 
      5 types of ships :
        Type : Tiles
        Carrier : 5
        Battleship : 4
        Cruiser : 3
        Submarine : 3
        Destroyer : 2
      8x8 tiles stored to 2D array in json file
      each Template has its own ID

      -1 template is meant to ease the process of adding new templates
    */


    $json = file_get_contents('templates.json');
    $templates=json_decode($json,true);
    /*echo "</br>";
    echo count($templates["Templates"])." ".$templates["Templates"][1]["id"]."</br>";
    echo print_r($templates["Templates"][1]["tiles"]);
    */
    $Player1Template=json_encode($templates['Templates'][random_int(1,count($templates["Templates"])-1)]['tiles']);
    echo $Player1Template;
    $Player2Template=json_encode($templates['Templates'][random_int(1,count($templates["Templates"])-1)]['tiles']);
    //empty view for the players to stare at while they prepare their first moves
    $Player1View=json_encode(array([0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0]));
    $Player2View=json_encode(array([0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0]));
    echo "INSERT INTO Game_$GameID VALUES(0,$Player1Template,$Player2Template,\"\");";
    ActiveRecord::execute("INSERT INTO Game_$GameID VALUES(0,\"$Player1Template\",\"$Player2Template\",\"$Player1View\",\"$Player2View\",0);");
    header('Location: ../templates/gameView.php/?playerid=' . $Player1ID);
?>