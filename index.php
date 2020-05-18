
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
                $Error=$_SESSION['idError'];
                echo $Error."</br>";
                unset($_SESSION['idError']);
            }
            session_destroy();
        ?>
        <input type="text" name="join" placeholder="Enter your Game ID"></br>
        <button type="submit" name="joinGame" value="Submit" class="button-primary align-center">Join the Game</button></br>
        
    </form>
</div>
</body>
</html>
