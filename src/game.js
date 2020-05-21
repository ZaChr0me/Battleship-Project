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
    $board=boardGet(level,'A');
    $('#playerA').text(boardGet(level,'A'));
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

function update(){
    if($('#turn').val()==$('#playerType').val()){
        return;
    }
    else{
        $.ajax({
            url: $gamePath+'/update/'+$('#gameID').val(),
            type: 'GET',
            dataType:'text',
            success:function(code_text,status){
                if(code_text=='A'||code_text=='B'){
                    if($('#turn').val()!=code_text){
                        $('#turn').val()=code_text;
                        drawBoard(2);
                        $('#move').show();
                        $('#play').show();
                    }
                    else{
                        $('#play').hide();
                    }
                }
                else{
                    $('#move').hide();
                    $('#play').hide();
                    $("#LabelID").html(code_text);
                }
            }
        });
    }
}

$testArrayAlpha=array('a','b','c','d','e','f','g','h','A','B','C','D','E','F','G','H');
$testArrayNum=array(1,2,3,4,5,6,7,8);
$(document).ready(function(){
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    gameInit(urlParams);

    $("#play").click(function(){
        event.preventDefault()
        if( jQuery.inarray($('#move').val()[0],$testArrayAlpha)&&jQuery.inarray($('#move').val()[1],$testArrayNum) ){
            $.ajax({
                url: $gamePath+'/play/'+$('#playerType').val()+'/'+$('#gameID').val()+'/'+$('#move').val(),
                type: 'GET',
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
        }
        else{
            $('#move').val("invalid move.")
        }
    });
    setInterval(update,1500);
   });