<html>
<head>
<link rel="stylesheet" type="text/css" href="http://fastly.ink.sapo.pt/3.1.10/css/ink.css">
<link href="../../styles/style.css" rel="stylesheet" type="text/css">
<title>Battleship Game</title>

<body>
    <header class="align-center">
        <h1>Battleship Game</h1>
    </header>
    <main>
    <form class="align-center">
             <label>Enter your move</label>
             <input id="test1" type="text" value="test">
             <button id="play">send move</button>

        </form>
    <?php
        //TEMP
        $playertype='A';
        //TEMP
        echo "<div class=\"divided\">";
            
            echo "<div id=\"playerA\" class=\"playerA bordered\">";

            echo "</div>";
            echo "<div id=\"playerB\" class=\"playerB bordered\">";

            echo "</div>";
        
        echo "</div>";
        ?>
        
        
        
    </main>
    <footer></footer>
    <input type="hidden" id="playerType">
    <input type="hidden" id="gameID">
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="../../src/game.js" type="text/javascript"></script>
</body>
</html>