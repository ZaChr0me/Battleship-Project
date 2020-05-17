
<?php
    require __DIR__ . '/vendor/autoload.php';
    ActiveRecord::setDb(new PDO('sqlite:BattleShip.db'));

    $id=$_POST['join'];
    //check database
    if (!empty($id)){
        //check database
        $id=0;
        header('Location: game.php/$id');
    }
    else{
        session_start();
        $_SESSION["idError"]=true;
        header('Location: index.php');
    }
?>