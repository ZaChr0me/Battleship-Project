<?php
    require __DIR__ . '/vendor/autoload.php';
    ActiveRecord::setDb(new PDO('sqlite:BattleShip.db'));
    
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
                              StorageID INTEGER,
                              Value1 TEXT,
                              Value2 TEXT,
                              Value3 TEXT
                          )");
    //StorageID REFERENCE [0:Field Templates],[1:Player Action],[2:Game End State]
    //Value 1 to 3 represent different values to be populated depending on the StorageID
    
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
    $Player1Template=random_int(1,count($templates["Templates"]));
    $Player2Template=random_int(1,count($templates["Templates"]));
    ActiveRecord::execute("INSERT INTO Game_$GameID VALUES(0,$Player1Template,$Player2Template,\"\");");
    header('Location: game.php/' . $Player1ID);
?>