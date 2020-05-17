<?php
    require __DIR__ . '/vendor/autoload.php';
    ActiveRecord::setDb(new PDO('sqlite:BattleShip.db'));
    
    class GameStorage extends ActiveRecord{
        public $table = 'GameStorage';
      public $primaryKey = 'ID';
    }
    $Player1ID=random_bytes(5);
    $Player2ID=random_bytes(5);

    $NewGame_Query=new GameStorage();
    $NewGame_Query->Player1=$Player1ID;
    $NewGame_Query->Player2=$Player2ID;
    $NewGame_Query->insert();
    
    header('Location: game.php/' . $Player1ID);
?>