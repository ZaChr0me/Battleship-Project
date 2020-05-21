<html>
<head>
<link rel="stylesheet" type="text/css" href="http://fastly.ink.sapo.pt/3.1.10/css/ink.css">
<link href="styles/style.css" rel="stylesheet" type="text/css">
<title>Battleship Game</title>
</head>
<body>

    <header class="align-center">
        <h1>Battleship Game</h1>
    </header>
<main class="bordered center">
    <article class="horizontal-space">
    <h5 class="align-center">Welcome to the sea, admiral!</h5>
        We're glad you've come. We've been stuck in a rut against our opponents, as with every shot of our cannons and every torpedoes launched, they responded in kind.
        Thus, our force are evenly matched, with a carrier, a battleship, a cruiser, a submarine and a destroyer remaining.
    </br>
    </br>
        You'll be given full control of the fleet...but the shallow waters and the reefs we're currently hovering over are too dangerous to both maneuver and fire.
        We'll thus be only firing at their positions. Even worse,  a thick fog has risen and covered the whole battlefield, so we can't get any clear idea of their positions.
    </br>
    </br>
        <h5 class="align-center">May the winds fly in your favour<h5>
    </article>
    <div>
    <form class="align-center" method="POST" action="./src/gameInit.php">
        <label>Create a new game and enter as player 1.</label>
        </br>
        <button class="ink-button " type="submit" name="createGame" value="Submit" class="button-primary align-center">Create a new Game</button></br>
    </form>
        
    <form class="align-center" method="POST" action="./src/redirect.php">
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
        <label>Enter a player ID to join the corresponding game as that player.</label>
        </br>
        <input id="joinField push-center" type="text" name="join" placeholder="Enter your Game ID">
        </br>
        </br>
        <button class="ink-button push-center" type="submit" name="joinGame" value="Submit" class="button-primary align-center">Join the Game</button></br>
    </form>
    </div>
</main>
<footer>
</footer>
</body>

</html>
