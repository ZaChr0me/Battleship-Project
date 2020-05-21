//application/json; charset=utf-8
$gamePath='../../src/game.php';

function gameInit(URLSearchParams){
    $.ajax({
        url:$gamePath+'/init/'+URLSearchParams.get('playerid'),
        type:'GET',
        dataType:'json',
        success:function(code_json,status){
            $('#playerA').text(code_json.playerType+" "+code_json.playerType);
            $("#playerType").val(code_json.playerType);
            $("#gameID").val(code_json.gameID);
            drawBoard(1);
        }
    });
}

function boardGet(level, board){
    $.ajax({
        url:$gamePath+'/board/'+$("#gameID").val()+'/'+$("#playerType").val()+'/'+level+'/'+board,
        type:'GET',
        dataType:'json',
        success:function(code_json,status){
            return code_json;
        }
    })
}


function drawBoard(level) {
    $board=JSON.parse( boardGet(level,'A'));
    $playerABoard="  A B C D E F G H";
    for (let indexN = 0; indexN < 8; indexN++) {
        $playerABoard+=(indexN+1)+" ";
        for (let indexL = 0; indexL < 8; indexL++) {
            $playerABoard+=$board[indexN][indexL]+" ";
        }
        $playerABoard+="</br>";
    }
    $('#playerA').text($playerABoard);
    $board=boardGet(level,'B');
    $playerBBoard="  A B C D E F G H";
    for (let indexN = 0; indexN < 8; indexN++) {
        $playerBBoard+=(indexN+1)+" ";
        for (let indexL = 0; indexL < 8; indexL++) {
            $playerBBoard+=$board[indexN][indexL]+" ";
        }
        $playerBBoard+="</br>";
    }
    $('#playerB').text($playerBBoard);
}

   
   $(document).ready(function(){
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    gameInit(urlParams);
    
    

    $("#play").click(function(){
        event.preventDefault()
        
    $.ajax({
        url: $gamePath+'/play/'+'B'+'/'+'1',
        type: 'GET',
        //data:{'playerType':'A','gameID':'1'},
        contentype:'json',
        dataType:'text',
        success:function(code_json,status){
            $("#test").text(code_json);
            $("#test1").val(code_json);
        },
        error:function(a,b,c){
            $("#test").text(a+" "+b+" "+c);
        }
    });
    });
    //setInterval(fetchdata,1000);
   });