
<?php
require __DIR__ . '/vendor/autoload.php';
ActiveRecord::setDb(new PDO('sqlite:BattleShip.db'));

class GameStorage extends ActiveRecord{
    public $table = 'GameStorage';
  public $primaryKey = 'ID';
}

    $id=$_POST['join'];        

    if (!empty($id)){
        $GameStorageQuery=new GameStorage();
        $Game=$GameStorageQuery->findAll();//
        //since $GameStorageQuery->where('Playera=$PlayerID')->find() nor ->find_by_PlayerA() never worked, the brute method of iterating through the entire game list...
        //it isn't optimal, but works...
        //check database if Player ID exists
        $foundId=false;
        for($i=0; $i<sizeof($Game); $i++){
            echo "searching</br>";
            if($Game[$i]->data['PlayerA']==$id || $Game[$i]->data['PlayerB']==$id){
                //echo "Found it!";
                $foundId=true;
            break;
            }
        }
        if($foundId){
            header('Location: game.php/'.$id);
        }
        else{
            session_start();
            $_SESSION["idError"]="The ID you have entered doesn't correspond to a running game.";
            header('Location: index.php');
            session_destroy();
        }
    }
    else{
        session_start();
        $_SESSION["idError"]="Please enter an ID to search for a game.";
        header('Location: index.php');
        //session_destroy();
    }
?>