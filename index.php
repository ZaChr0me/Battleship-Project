
<html>
<head>
<title>Battleship Game</title>
<style>
        header {
            background-color: #4C82BF;
            padding: 20px;
            text-align: center;
            font-size: 20px;
            color: #070d13;
            height: 50px;
        }
        </style>
</head>
<body>
    <header>
        <h2>Battleship Game</h2>
    </header>
<div>
    Description
</div>
</br>
<div>
    <form method="POST" action="gameInit.php">
        <button type="submit" name="createGame" value="Submit" class="button-primary align-center">Create a new Game</button></br>
    </form>
    <form method="POST" action="redirect.php">
        
        <?php
            //check if there was an error with the ID during the redirection
            session_start();
            if(isset($_SESSION['idError'])){
                echo "The ID you may have entered is incorrect.";
                echo "</br>";
                unset($_SESSION['idError']);
            }
            session_destroy()
        ?>
        <input type="text" name="join" placeholder="Enter your Game ID"></br>
        <button type="submit" name="joinGame" value="Submit" class="button-primary align-center">Join the Game</button></br>
        
    </form>

<?php
require __DIR__ . '/vendor/autoload.php';

class GameStorage extends ActiveRecord{
    public $table = 'friend';
  public $primaryKey = 'id';
}

/*ActiveRecord::setDb(new PDO('sqlite:friends.db'));
$friendQuery = new Game();
print_r($allFriends);
*/
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

echo "test1</br>".bin2hex(random_bytes(5))."</br>";
echo "test2</br>".bin2hex(random_bytes(5));
/*$app->get(
    '/{id}',
    function (Request $request, Response $response, array $args) use ($db) {
        $sql = "select * from participant";
        $ret = $db->query($sql);
        $friends = [];
        while ($friend = $ret->fetchArray(SQLITE3_ASSOC)) {
            $friends[] = $friend;
        }
        return $response->withJson($friends);
    }
);
*/

?>
</div>
</body>
</html>
