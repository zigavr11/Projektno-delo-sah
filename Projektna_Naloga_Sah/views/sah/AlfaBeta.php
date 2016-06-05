<?php

static $che = array(
    array("r","n","b","q","k","b","n","r"),
    array("p","p","p","p","p","p","p","p"),
    array(" "," "," "," "," "," "," "," "),
    array(" "," "," "," "," "," "," "," "),
    array(" "," "," "," "," "," "," "," "),
    array(" "," "," "," "," "," "," "," "),
    array("P","P","P","P","P","P","P","P"),
    array("R","N","B","Q","K","B","N","R"));

static $pawnHeuristics = array(
    array( 0,  0,  0,  0,  0,  0,  0,  0),
    array(50, 50, 50, 50, 50, 50, 50, 50),
    array(10, 10, 20, 30, 30, 20, 10, 10),
    array( 5,  5, 10, 25, 25, 10,  5,  5),
    array( 0,  0,  0, 20, 20,  0,  0,  0),
    array( 5, -5,-10,  0,  0,-10, -5,  5),
    array( 5, 10, 10,-20,-20, 10, 10,  5),
    array( 0,  0,  0,  0,  0,  0,  0,  0));
static $rookHeuristics = array(
    array( 0,  0,  0,  0,  0,  0,  0,  0),
    array( 5, 10, 10, 10, 10, 10, 10,  5),
    array(-5,  0,  0,  0,  0,  0,  0, -5),
    array(-5,  0,  0,  0,  0,  0,  0, -5),
    array(-5,  0,  0,  0,  0,  0,  0, -5),
    array(-5,  0,  0,  0,  0,  0,  0, -5),
    array(-5,  0,  0,  0,  0,  0,  0, -5),
    array( 0,  0,  0,  5,  5,  0,  0,  0));
static $knightHeuristics = array(
    array(-50,-40,-30,-30,-30,-30,-40,-50),
    array(-40,-20,  0,  0,  0,  0,-20,-40),
    array(-30,  0, 10, 15, 15, 10,  0,-30),
    array(-30,  5, 15, 20, 20, 15,  5,-30),
    array(-30,  0, 15, 20, 20, 15,  0,-30),
    array(-30,  5, 10, 15, 15, 10,  5,-30),
    array(-40,-20,  0,  5,  5,  0,-20,-40),
    array(-50,-40,-30,-30,-30,-30,-40,-50));
static $bishopHeuristics = array(
    array(-20,-10,-10,-10,-10,-10,-10,-20),
    array(-10,  0,  0,  0,  0,  0,  0,-10),
    array(-10,  0,  5, 10, 10,  5,  0,-10),
    array(-10,  5,  5, 10, 10,  5,  5,-10),
    array(-10,  0, 10, 10, 10, 10,  0,-10),
    array(-10, 10, 10, 10, 10, 10, 10,-10),
    array(-10,  5,  0,  0,  0,  0,  5,-10),
    array(-20,-10,-10,-10,-10,-10,-10,-20));
static $queenHeuristics = array(
    array(-20,-10,-10, -5, -5,-10,-10,-20),
    array(-10,  0,  0,  0,  0,  0,  0,-10),
    array(-10,  0,  5,  5,  5,  5,  0,-10),
    array( -5,  0,  5,  5,  5,  5,  0, -5),
    array(  0,  0,  5,  5,  5,  5,  0, -5),
    array(-10,  5,  5,  5,  5,  5,  0,-10),
    array(-10,  0,  5,  0,  0,  0,  0,-10),
    array(-20,-10,-10, -5, -5,-10,-10,-20));
static $kingHeuristicsMidGame = array(
    array(-30,-40,-40,-50,-50,-40,-40,-30),
    array(-30,-40,-40,-50,-50,-40,-40,-30),
    array(-30,-40,-40,-50,-50,-40,-40,-30),
    array(-30,-40,-40,-50,-50,-40,-40,-30),
    array(-20,-30,-30,-40,-40,-30,-30,-20),
    array(-10,-20,-20,-20,-20,-20,-20,-10),
    array( 20, 20,  0,  0,  0,  0, 20, 20),
    array( 20, 30, 10,  0,  0, 10, 30, 20));
static $kingHeuristicsEndGame = array(
    array(-50,-40,-30,-20,-20,-30,-40,-50),
    array(-30,-20,-10,  0,  0,-10,-20,-30),
    array(-30,-10, 20, 30, 30, 20,-10,-30),
    array(-30,-10, 30, 40, 40, 30,-10,-30),
    array(-30,-10, 30, 40, 40, 30,-10,-30),
    array(-30,-10, 20, 30, 30, 20,-10,-30),
    array(-30,-30,  0,  0,  0,  0,-30,-30),
    array(-50,-30,-30,-30,-30,-30,-30,-50));


$globalDepth = 2;
$beta = 1000000;
$alpha = -1000000;



function moveA($f_move, &$polje){
	$whiteKingPosition = 0;
	$blackKingPosition = 0;
	
	while ("K" != $polje[intval($whiteKingPosition/8)][$whiteKingPosition%8]) {$whiteKingPosition++;}
	while ("k" != $polje[intval($blackKingPosition/8)][$blackKingPosition%8]) {$blackKingPosition++;}

	for($i=0; $i<8; $i++){
		for($j=0; $j<8; $j++)
		{
			if($polje[$i][$j] == "0"){
				$polje[$i][$j] = " ";
			}
		}
	}
	
    global $globalDepth;
	
    moveForward($f_move, $polje, $whiteKingPosition);
    $move = alphaBeta($globalDepth, 1000000, -1000000, "", 0, $polje, $whiteKingPosition);
    moveForward($move, $polje, $whiteKingPosition);

	return $move;
}


function moveForward($move, &$chessBoard, &$whiteKingPosition) {
     if ($move[4] != 'P') { //
        $chessBoard[intval($move[2])][intval($move[3])]=$chessBoard[intval($move[0])][intval($move[1])];
        $chessBoard[intval($move[0])][intval($move[1])]=" ";

        if ("K" == $chessBoard[intval($move[2])][intval($move[3])])
        {
            $whiteKingPosition=8*intval($move[2])+intval($move[3]);
        }
    }
    else {
        $chessBoard[1][intval($move[0])]=" ";
        $chessBoard[0][intval($move[1])]=strval($move[3]);
    }
}
function moveBackwards($move, &$chessBoard, &$whiteKingPosition) {
    if ($move[4]!='P') {
        $chessBoard[intval($move[0])][intval($move[1])]
            =$chessBoard[intval($move[2])][intval($move[3])];
        $chessBoard[intval($move[2])][intval($move[3])] = strval($move[4]);

        if ("K" == $chessBoard[intval($move[0])][intval($move[1])])
        {
            $whiteKingPosition = 8*intval($move[0])+intval($move[1]);
        }
    } else {
        //if pawn promotion
        $chessBoard[1][intval($move[0])]="P";
        $chessBoard[0][intval($move[1])]=strval($move[2]);
    }
}

function alphaBeta($depth, $beta, $alpha, $move, $player, &$chessBoard, $whiteKingPosition) { //
    //returns x1y1x2y2f12345
    //player 0=min, 1=max
    global $globalDepth;
    $listaPotez = posibleMoves($chessBoard, $whiteKingPosition);
        if ($depth==0 || strlen($listaPotez)==0)
        {
            return $move.(oceni(strlen($listaPotez), $depth, $chessBoard, $whiteKingPosition)*($player*2-1));
        }
        $listaPotez=sortMoves($listaPotez, $chessBoard, $whiteKingPosition);
        $player=1-$player;//1 or 0
        for ($i=0; $i<strlen($listaPotez); $i+=5) {
        moveForward(substr($listaPotez, $i,5), $chessBoard, $whiteKingPosition);
        $chessBoard = ChangeBoard($chessBoard);
        $returnString = alphaBeta($depth-1, $beta, $alpha, substr($listaPotez, $i,5), $player, $chessBoard, $whiteKingPosition);
        $value=intval(substr($returnString,5));
        $chessBoard = ChangeBoard($chessBoard);
        moveBackwards(substr($listaPotez, $i, 5), $chessBoard, $whiteKingPosition);
        if ($player==0) {
            if ($value<=$beta) {
                $beta=$value;
                if ($depth==$globalDepth) {
                    $move = substr($returnString,0,5);
                }
            }
        } else {
            if ($value>$alpha) {
                $alpha=$value;
                if ($depth==$globalDepth) {
                    $move = substr($returnString,0,5);
                }
            }
        }
        if ($alpha >= $beta) {
           if ($player==0) {
               return $move.$beta;
           }
           else {
               return $move.$alpha;
           }
        }
    }
        if ($player==0) {
            return $move.$beta;
        } else {
            return $move.$alpha;
        }
    }

function sortMoves($listaPotez, $chessBoard, $whiteKingPosition) {

    $score = array();

    for ($i=0; $i<strlen($listaPotez); $i+=5) {
        moveForward(substr($listaPotez, $i, 5), $chessBoard, $whiteKingPosition);
        $score[intval($i/5)] = -oceni(-1, 0, $chessBoard, $whiteKingPosition);
        moveBackwards(substr($listaPotez, $i, 5), $chessBoard, $whiteKingPosition);
    }
        $newListA="";
        $newListB=$listaPotez;
        for ($i=0; $i<min(6, strlen($listaPotez)/5); $i++) {//first few moves only
            $max=-1000000;
            $maxLocation=0;
            for ($j=0;$j<strlen($listaPotez)/5;$j++) {
            if ($score[$j]>$max) {$max=$score[$j]; $maxLocation=$j;}
        }
            $score[$maxLocation]=-1000000;
            $newListA = $newListA . substr($listaPotez, $maxLocation*5, 5);
            //str_replace("world","Peter","Hello world!");
            $newListB = str_replace(substr($listaPotez, $maxLocation*5, 5), "", $newListB);
        }
        return $newListA.$newListB;
    }

function ChangeBoard(&$chessBoard){
    $k = 0;
    $newarr = array();
    $tmparr = array();
    for($i=0; $i<8; $i++)
    {
        for($j=0; $j<8; $j++)
        {
            $newarr[$k] = $chessBoard[$i][$j];
            $k++;
        }
    }
    for($i=0; $i<32; $i++) {
        $tmparr[$i] = $newarr[$i];
    }
    for($i=0; $i<32; $i++) {
        $newarr[$i] = $newarr[63-$i];
    }
    for($i=0; $i<32; $i++) {
        $newarr[63-$i] = $tmparr[$i];
    }
    $k = 0;
    for($i=0; $i<8; $i++)
    {
        for($j=0; $j<8; $j++)
        {
            $chessBoard[$i][$j] = $newarr[$k];
            $k++;
        }
    }

    for ($i=0; $i<64; $i++) {
        $r = $i / 8;
        $c = $i % 8;

        if(ctype_upper($chessBoard[$r][$c])){
            $chessBoard[$r][$c] = strtolower($chessBoard[$r][$c]);
        }
        else{
            $chessBoard[$r][$c] = strtoupper($chessBoard[$r][$c]);
        }
    }

    return $chessBoard;
}

function posibleMoves($chessBoard, $whiteKingPosition) {
    $list="";

    for ($i=0; $i<64; $i++) {

        switch ($chessBoard[$i/8][$i%8]) {
            case "P":
                $list = $list.posibleP($i, $chessBoard, $whiteKingPosition);
                break;
            case "R":
                $list = $list.posibleR($i, $chessBoard, $whiteKingPosition);
                break;
            case "N":
                $list = $list.posibleK($i, $chessBoard, $whiteKingPosition);
                break;
            case "B":
                $list = $list.posibleB($i, $chessBoard, $whiteKingPosition);
                break;
            case "Q":
                $list = $list.posibleQ($i, $chessBoard, $whiteKingPosition);
                break;
            case "K":
                $list = $list.posibleA($i, $chessBoard, $whiteKingPosition);
                break;
        }
    }
    return $list;//x1,y1,x2,y2,captured piece
}

function posibleP($i, &$chessBoard, &$whiteKingPosition)
{
    $list = "";
    #$oldPiece = " ";
    $r = intval($i / 8);
    $c = intval($i % 8);

    for ($j = -1; $j <= 1; $j += 2) {
        try {//capture
            if(isset($chessBoard[$r - 1][$c + $j])) {
                if (ctype_lower($chessBoard[$r - 1][$c + $j]) && $i >= 16) {
                    $oldPiece = $chessBoard[intval($r - 1)][$c + $j];
                    $chessBoard[intval($r)][$c] = " ";
                    $chessBoard[intval($r - 1)][$c + $j] = "P";
                    if (kingSafe($chessBoard, $whiteKingPosition)) {
                        $list = $list . $r . $c . ($r - 1) . ($c + $j) . $oldPiece;
                    }
                    $chessBoard[intval($r)][$c] = "P";
                    $chessBoard[intval($r - 1)][$c + $j] = $oldPiece;
                }
            }
        } catch (Exception $e) {
        }
        try {//promotion && capture
            if(isset($chessBoard[$r - 1][$c + $j])) {
                if (ctype_lower($chessBoard[$r - 1][$c + $j]) && $i < 16) {
                    $temp = array("Q", "R", "B", "N");# {"Q","R","B","N"};
                    for ($k = 0; $k < 4; $k++) {
                        $oldPiece = $chessBoard[intval($r - 1)][$c + $j];
                        $chessBoard[intval($r)][$c] = " ";
                        $chessBoard[intval($r - 1)][$c + $j] = $temp[$k];
                        if (kingSafe($chessBoard, $whiteKingPosition)) {
                            //column1,column2,captured-piece,new-piece,P
                            $list = $list . $c . ($c + $j) . $oldPiece . $temp[$k] . "P";
                        }
                        $chessBoard[intval($r)][$c] = "P";
                        $chessBoard[intval($r - 1)][$c + $j] = $oldPiece;
                    }
                }
            }
        } catch (Exception $e) {
        }
        try {//move one up
            if(isset($chessBoard[intval($r - 1)][$c])){
                if (" " == $chessBoard[intval($r - 1)][$c] && $i >= 16) {
                    $oldPiece = $chessBoard[intval($r - 1)][$c];
                    $chessBoard[intval($r)][$c] = " ";
                    $chessBoard[intval($r - 1)][$c] = "P";
                    if (kingSafe($chessBoard, $whiteKingPosition)) {
                        $list = $list . $r . $c . ($r - 1) . $c . $oldPiece;
                    }
                    $chessBoard[intval($r)][$c] = "P";
                    $chessBoard[intval($r - 1)][$c] = $oldPiece;
                }
            }
        } catch (Exception $e) {
        }
        try {//promotion && no capture
            if(isset($chessBoard[intval($r - 1)][$c])) {
                if (" " == $chessBoard[intval($r - 1)][$c] && $i < 16) {
                    $temp = array("Q", "R", "B", "N");
                    for ($k = 0; $k < 4; $k++) {
                        $oldPiece = $chessBoard[intval($r - 1)][$c];
                        $chessBoard[intval($r)][$c] = " ";
                        $chessBoard[intval($r - 1)][$c] = $temp[$k];
                        if (kingSafe($chessBoard, $whiteKingPosition)) {
                            //column1,column2,captured-piece,new-piece,P
                            $list = $list . $c . $c . $oldPiece . $temp[$k] . "P";
                        }
                        $chessBoard[intval($r)][$c] = "P";
                        $chessBoard[intval($r - 1)][$c] = $oldPiece;
                    }
                }
            }
        } catch (Exception $e) {
        }
        try {//move two up
            if(isset($chessBoard[intval($r - 1)][$c]) && isset($chessBoard[intval($r - 2)][$c])){
                if (" " == $chessBoard[intval($r - 1)][$c] && " " == $chessBoard[intval($r - 2)][$c] && $i >= 48) {
                    $oldPiece = $chessBoard[intval($r - 2)][$c];
                    $chessBoard[intval($r)][$c] = " ";
                    $chessBoard[intval($r - 2)][$c] = "P";
                    if (kingSafe($chessBoard, $whiteKingPosition)) {
                        $list = $list . $r . $c . ($r - 2) . $c . $oldPiece;
                    }
                    $chessBoard[intval($r)][$c] = "P";
                    $chessBoard[intval($r - 2)][$c] = $oldPiece;
                }
            }
        } catch (Exception $e) {
        }
        return $list;
    }
    return $list;
}

function posibleK($i, &$chessBoard, &$whiteKingPosition)
{
    $list=""; #$oldPiece="";
    $r=intval($i/8); $c=intval($i%8);

    for ($j=-1; $j<=1; $j+=2) {
        for ($k=-1; $k<=1; $k+=2) {
            try {
                if(isset($chessBoard[intval($r + $j)][$c + $k * 2]))
                {
                    if (ctype_lower($chessBoard[intval($r + $j)][$c + $k * 2]) || " " == $chessBoard[intval($r + $j)][$c + $k * 2]) {
                        $oldPiece = $chessBoard[intval($r + $j)][$c + $k * 2];
                        $chessBoard[intval($r)][$c] = " ";
                        if (kingSafe($chessBoard, $whiteKingPosition)) {
                            $list = $list . $r . $c . ($r + $j) . ($c + $k * 2) . $oldPiece;
                        }
                        $chessBoard[intval($r)][$c] = "N";
                        $chessBoard[intval($r + $j)][$c + $k * 2] = $oldPiece;
                    }

                }
            } catch (Exception $e) {}
            try {
                if(isset($chessBoard[intval($r + $j * 2)][$c + $k])) {
                    if (ctype_lower($chessBoard[intval($r + $j * 2)][$c + $k]) || " " == $chessBoard[intval($r + $j * 2)][$c + $k]) {
                        $oldPiece = $chessBoard[intval($r + $j * 2)][$c + $k];
                        $chessBoard[intval($r)][$c] = " ";
                        if (kingSafe($chessBoard, $whiteKingPosition)) {
                            $list = $list . $r . $c . ($r + $j * 2) . ($c + $k) . $oldPiece;
                        }
                        $chessBoard[intval($r)][$c] = "N";
                        $chessBoard[intval($r + $j * 2)][$c + $k] = $oldPiece;
                    }
                }
            } catch (Exception $e) {}
        }
    }
    return $list;
}

function posibleR($i, &$chessBoard, &$whiteKingPosition)
{
    $list=""; #$oldPiece = "";
    $r=intval($i/8); $c=intval($i%8);


    $temp=1;
    for ($j=-1; $j<=1; $j+=2) {
        try {
            if(isset($chessBoard[intval($r)][$c + $temp * $j])) {
                while (" " == $chessBoard[intval($r)][$c + $temp * $j]) {
                    $oldPiece = $chessBoard[intval($r)][$c + $temp * $j];
                    $chessBoard[intval($r)][$c] = " ";
                    $chessBoard[intval($r)][$c + $temp * $j] = "R";
                    if (kingSafe($chessBoard, $whiteKingPosition)) {
                        $list = $list . $r . $c . $r . ($c + $temp * $j) . $oldPiece;
                    }
                    $chessBoard[intval($r)][$c] = "R";
                    $chessBoard[intval($r)][$c + $temp * $j] = $oldPiece;
                    $temp++;
                    if(!isset($chessBoard[intval($r)][$c + $temp * $j]))
                        break;
                }
                if(isset($chessBoard[intval($r)][$c + $temp * $j])) {
                    if (ctype_lower($chessBoard[intval($r)][$c + $temp * $j])) {
                        $oldPiece = $chessBoard[intval($r)][$c + $temp * $j];
                        $chessBoard[intval($r)][$c] = " ";
                        $chessBoard[intval($r)][$c + $temp * $j] = "R";
                        if (kingSafe($chessBoard, $whiteKingPosition)) {
                            $list = $list . $r . $c . $r . ($c + $temp * $j) . $oldPiece;
                        }
                        $chessBoard[intval($r)][$c] = "R";
                        $chessBoard[intval($r)][$c + $temp * $j] = $oldPiece;
                    }
                }
            }

        } catch (Exception $e) {}
        $temp=1;
        try {
            if(isset($chessBoard[$r + $temp * $j][$c])) {
                while ($chessBoard[$r + $temp * $j][$c] == " ") {
                    $oldPiece = $chessBoard[intval($r + $temp * $j)][$c];
                    $chessBoard[intval($r)][$c] = " ";
                    $chessBoard[intval($r + $temp * $j)][$c] = "R";
                    if (kingSafe($chessBoard, $whiteKingPosition)) {
                        $list = $list . $r . $c . ($r + $temp * $j) . $c . $oldPiece;
                    }
                    $chessBoard[intval($r)][$c] = "R";
                    $chessBoard[intval($r + $temp * $j)][$c] = $oldPiece;
                    $temp++;
                    if(!isset($chessBoard[$r + $temp * $j][$c]))
                        break;
                }
                if(isset($chessBoard[$r + $temp * $j][$c])) {
                    if (ctype_lower($chessBoard[$r + $temp * $j][$c])) {
                        $oldPiece = $chessBoard[intval($r + $temp * $j)][$c];
                        $chessBoard[intval($r)][$c] = " ";
                        $chessBoard[intval($r + $temp * $j)][$c] = "R";
                        if (kingSafe($chessBoard, $whiteKingPosition)) {
                            $list = $list . $r . $c . ($r + $temp * $j) . $c . $oldPiece;
                        }
                        $chessBoard[intval($r)][$c] = "R";
                        $chessBoard[intval($r + $temp * $j)][$c] = $oldPiece;
                    }
                }
            }
        } catch (Exception $e) {}
        $temp=1;
    }
    return $list;
}

function posibleB($i, &$chessBoard, &$whiteKingPosition)
{
    $list="";
    $r=intval($i/8); $c=intval($i%8);
    $temp=1;
    for ($j=-1; $j<=1; $j+=2) {
        for ($k=-1; $k<=1; $k+=2) {
            try {
                if(isset($chessBoard[intval($r + $temp * $j)][$c + $temp * $k])) {
                        while (" " == $chessBoard[intval($r + $temp * $j)][$c + $temp * $k] ) {

                            $oldPiece = $chessBoard[intval($r + $temp * $j)][$c + $temp * $k];
                            $chessBoard[intval($r)][$c] = " ";
                            $chessBoard[intval($r + $temp * $j)][$c + $temp * $k] = "B";
                            if (kingSafe($chessBoard, $whiteKingPosition)) {
                                $list = $list . $r . $c . ($r + $temp * $j) . ($c + $temp * $k) . $oldPiece;
                            }
                            $chessBoard[intval($r)][$c] = "B";
                            $chessBoard[intval($r + $temp * $j)][$c + $temp * $k] = $oldPiece;
                            $temp++;
                            if(!isset($chessBoard[intval($r + $temp * $j)][$c + $temp * $k]))
                                break;
                        }
                    if(isset($chessBoard[intval($r + $temp * $j)][$c + $temp * $k])) {
                        if (ctype_lower($chessBoard[intval($r + $temp * $j)][$c + $temp * $k])) {
                            $oldPiece = $chessBoard[intval($r + $temp * $j)][$c + $temp * $k];
                            $chessBoard[intval($r)][$c] = " ";
                            $chessBoard[intval($r + $temp * $j)][$c + $temp * $k] = "B";
                            if (kingSafe($chessBoard, $whiteKingPosition)) {
                                $list = $list . $r . $c . ($r + $temp * $j) . ($c + $temp * $k) . $oldPiece;
                            }
                            $chessBoard[intval($r)][$c] = "B";
                            $chessBoard[intval($r + $temp * $j)][$c + $temp * $k] = $oldPiece;
                        }
                    }
                }
            } catch (Exception $e) {}
            $temp=1;

        }
    }
    return $list;
}

function posibleQ($i, &$chessBoard, &$whiteKingPosition)
{
    $list=""; #$oldPiece = "";
    $r=intval($i/8); $c=intval($i%8);

    $temp=1;
    for ($j=-1; $j<=1; $j++) {
        for ($k=-1; $k<=1; $k++) {
            if ($j!=0 || $k!=0) {
                try {
                    if(isset($chessBoard[intval($r + $temp * $j)][$c + $temp * $k])) {
                        while (" " == $chessBoard[intval($r + $temp * $j)][$c + $temp * $k]) {
                            $oldPiece = $chessBoard[intval($r + $temp * $j)][$c + $temp * $k];
                            $chessBoard[intval($r)][$c] = " ";
                            $chessBoard[intval($r + $temp * $j)][$c + $temp * $k] = "Q";
                            if (kingSafe($chessBoard, $whiteKingPosition)) {
                                $list = $list . $r . $c . ($r + $temp * $j) . ($c + $temp * $k) . $oldPiece;
                            }
                            $chessBoard[intval($r)][$c] = "Q";
                            $chessBoard[intval($r + $temp * $j)][$c + $temp * $k] = $oldPiece;
                            $temp++;
                            if(!isset($chessBoard[intval($r + $temp * $j)][$c + $temp * $k]))
                                break;
                        }
                        if(isset($chessBoard[intval($r + $temp * $j)][$c + $temp * $k])) {
                            if (ctype_lower($chessBoard[intval($r + $temp * $j)][$c + $temp * $k])) {
                                $oldPiece = $chessBoard[intval($r + $temp * $j)][$c + $temp * $k];
                                $chessBoard[intval($r)][$c] = " ";
                                $chessBoard[intval($r + $temp * $j)][$c + $temp * $k] = "Q";
                                if (kingSafe($chessBoard, $whiteKingPosition)) {
                                    $list = $list . $r . $c . ($r + $temp * $j) . ($c + $temp * $k) . $oldPiece;
                                }
                                $chessBoard[intval($r)][$c] = "Q";
                                $chessBoard[intval($r + $temp * $j)][$c + $temp * $k] = $oldPiece;
                            }
                        }
                    }
                } catch (Exception $e) {}
                $temp=1;
            }
        }
    }
    return $list;
}

function posibleA($i, &$chessBoard, &$whiteKingPosition) {
    $list=""; #$oldPiece="";
    $r = intval($i/8);
    $c = intval($i%8);

    for ($j=0; $j<9; $j++) {
        if ($j!=4) {
            try {
                if(isset($chessBoard[intval($r - 1 + $j / 3)][intval($c - 1 + $j % 3)])) {
                    if (ctype_lower($chessBoard[intval($r - 1 + $j / 3)][intval($c - 1 + $j % 3)]) || " " == $chessBoard[intval($r - 1 + $j / 3)][intval($c - 1 + $j % 3)]) {
                        $oldPiece = $chessBoard[intval($r - 1 + $j / 3)][intval($c - 1 + $j % 3)];
                        $chessBoard[intval($r)][$c] = " ";
                        $chessBoard[intval($r - 1 + $j / 3)][$c - 1 + $j % 3] = "K";
                        $kingTemp = $whiteKingPosition;
                        $whiteKingPosition = $i + ($j / 3) * 8 + $j % 3 - 9;
                        if (kingSafe($chessBoard, $whiteKingPosition)) {
                            $list = $list . $r . $c . intval(($r - 1 + $j / 3)) . intval(($c - 1 + $j % 3)) . $oldPiece;
                        }
                        $chessBoard[intval($r)][$c] = "K";
                        $chessBoard[intval($r - 1 + $j / 3)][$c - 1 + $j % 3] = $oldPiece;
                        $whiteKingPosition = $kingTemp;
                    }
                }

            } catch (Exception $e) {}
        }
    }
    return $list;
}

function kingSafe(&$chessBoard, &$whiteKingPosition)
{
    //bishop/queen
    $temp=1;
    for ($i=-1; $i<=1; $i+=2) {
        for ($j=-1; $j<=1; $j+=2) {
            try {
                if(isset($chessBoard[intval($whiteKingPosition/8+$temp*$i)][intval($whiteKingPosition%8+$temp*$j)])){
                    while(" " == $chessBoard[intval($whiteKingPosition/8+$temp*$i)][intval($whiteKingPosition%8+$temp*$j)]
                        && isset($chessBoard[intval($whiteKingPosition/8+($temp+1)*$i)][intval($whiteKingPosition%8+($temp+1)*$j)])) {
                        $temp++;
                    }
                }
                if(isset($chessBoard[intval($whiteKingPosition/8+$temp*$i)][intval($whiteKingPosition%8+$temp*$j)])){
                    if ("b" == $chessBoard[intval($whiteKingPosition/8+$temp*$i)][intval($whiteKingPosition%8+$temp*$j)] ||
                        "q" == $chessBoard[intval($whiteKingPosition/8+$temp*$i)][intval($whiteKingPosition%8 + $temp*$j)]) {
                        return false;
                    }
                }
            } catch (Exception $e) {}
            $temp=1;
        }
    }
    //rook/queen
    for ($i=-1; $i<=1; $i+=2) {
        try {
            if (isset($chessBoard[intval($whiteKingPosition/8)][$whiteKingPosition%8+$temp*$i])){
                while(" " == $chessBoard[intval($whiteKingPosition/8)][$whiteKingPosition%8+$temp*$i]
                    && isset($chessBoard[intval($whiteKingPosition/8)][$whiteKingPosition%8+($temp+1)*$i]))
                {$temp++;}
                if ("r" == $chessBoard[intval($whiteKingPosition/8)][$whiteKingPosition%8+$temp*$i] ||
                    "q" == $chessBoard[intval($whiteKingPosition/8)][$whiteKingPosition%8+$temp*$i]) {
                    return false;
                }
            }
        } catch (Exception $e) {}
        $temp=1;
        try {
            if(isset($chessBoard[intval($whiteKingPosition/8+$temp*$i)][$whiteKingPosition%8])){
                while(" " == $chessBoard[intval($whiteKingPosition/8+$temp*$i)][$whiteKingPosition%8]
                    && isset($chessBoard[intval($whiteKingPosition/8+($temp+1)*$i)][$whiteKingPosition%8])) {$temp++;}
                if ("r" == $chessBoard[intval($whiteKingPosition/8+$temp*$i)][$whiteKingPosition%8] ||
                    "q" == $chessBoard[intval($whiteKingPosition/8+$temp*$i)][$whiteKingPosition%8]) {
                    return false;
                }
            }
        } catch (Exception $e) {}
        $temp=1;
    }
    //knight
    for ($i=-1; $i<=1; $i+=2) {
        for ($j=-1; $j<=1; $j+=2) {
            try {
                if(isset($chessBoard[intval($whiteKingPosition/8+$i)][$whiteKingPosition%8+$j*2])){
                    if ("n" == $chessBoard[intval($whiteKingPosition/8+$i)][$whiteKingPosition%8+$j*2]) {
                        return false;
                    }
                }
            } catch (Exception $e) {}
            try {
                if (isset($chessBoard[intval($whiteKingPosition / 8 + $i * 2)][$whiteKingPosition % 8 + $j])){
                    if ("n" == $chessBoard[intval($whiteKingPosition / 8 + $i * 2)][$whiteKingPosition % 8 + $j]) {
                        return false;
                    }
                }
            } catch (Exception $e) {}
        }
    }
    //pawn
    if ($whiteKingPosition>=16) {
        try {
            if(isset($chessBoard[intval($whiteKingPosition/8-1)][$whiteKingPosition%8-1])){
                if ("p" == $chessBoard[intval($whiteKingPosition/8-1)][$whiteKingPosition%8-1]) {
                    return false;
                }
            }
        } catch (Exception $e) {}
        try {
            if(isset($chessBoard[intval($whiteKingPosition / 8 - 1)][$whiteKingPosition % 8 + 1])) {
                if ("p" == $chessBoard[intval($whiteKingPosition / 8 - 1)][$whiteKingPosition % 8 + 1]) {
                    return false;
                }
            }
        } catch (Exception $e) {}
        //king
        for ($i=-1; $i<=1; $i++) {
            for ($j=-1; $j<=1; $j++) {
                if ($i!=0 || $j!=0) {
                    try {
                        if(isset($chessBoard[intval($whiteKingPosition/8+$i)][$whiteKingPosition%8+$j])) {
                            if ("k" == $chessBoard[intval($whiteKingPosition / 8 + $i)][$whiteKingPosition % 8 + $j]) {
                                return false;
                            }
                        }
                    } catch (Exception $e) {}
                }
            }
        }
    }
    return true;
}

//RATING///////////////////////////////////////////////////////////////////////////////////


function oceni($list, $depth, $chessBoard, $whiteKingPosition) {
    $count = 0;
    $figures = rateFigures($chessBoard);

    $count += rateAttack($chessBoard, $whiteKingPosition);
    $count += $figures;
    $count += rateMovablitly($list, $depth);
    $count += ratePositions($figures,$chessBoard,$whiteKingPosition);
    $chessBoard = ChangeBoard($chessBoard);
    $figures = rateFigures($chessBoard);
    $count -= rateAttack($chessBoard, $whiteKingPosition);
    $count -= $figures;
    $count -= rateMovablitly($list, $depth);
    $count -= ratePositions($figures,$chessBoard,$whiteKingPosition);
    #$chessBoard = ChangeBoard($chessBoard);
    return -($count + $depth * 50);
}

function rateAttack($chessBoard, $whiteKingPosition) {
$counter=0;
        $tempPositionC = $whiteKingPosition;

        for ($i = 0; $i < 64; $i++) {
            switch ($chessBoard[$i/8][$i%8]) {
                case "P":{
                    $whiteKingPosition=$i;
                    if (!kingSafe($chessBoard, $whiteKingPosition)){
                        $counter -= 64;
                    }
                }
                    break;
                case "R":{
                    $whiteKingPosition = $i;
                    if (!kingSafe($chessBoard, $whiteKingPosition)){
                        $counter -= 500;
                    }
                }
                    break;
                case "N": {
                    $whiteKingPosition = $i;
                    if (!kingSafe($chessBoard, $whiteKingPosition)) {
                        $counter -= 300;
                    }
                }
                    break;
                case "B": {
                    $whiteKingPosition = $i;
                    if (!kingSafe($chessBoard, $whiteKingPosition)) {
                        $counter -= 300;
                    }
                }
                    break;
                case "Q": {
                    $whiteKingPosition = $i;
                    if (!kingSafe($chessBoard, $whiteKingPosition)) {
                        $counter -= 900;
                    }
                }
                    break;
            }
        }
    $whiteKingPosition=$tempPositionC;
        if (!kingSafe($chessBoard, $whiteKingPosition)) {$counter -= 200000;}
        return $counter/2;
    }

function rateFigures($chessBoard) {
    $counter=0;
        for ($i=0;$i<64;$i++) {
            switch ($chessBoard[$i/8][$i%8]) {
                case "P":
                    $counter += 100;
                    break;
                case "R":
                    $counter += 500;
                    break;
                case "N":
                    $counter += 300;
                    break;
                case "B":
                    $counter += 300;
                    break;
                case "Q":
                    $counter += 900;
                    break;
            }
        }
    return $counter;
}

function rateMovablitly($listLength, $depth) {
    $counter=0;
        $counter += $listLength;//5 pointer per valid move
        if ($listLength == 0) {//current side is in checkmate or stalemate
            if (kingSafe($chessBoard, $whiteKingPosition) == false) {//if checkmate
                $counter += -200000*$depth;
            } else {//if stalemate
                $counter += -150000*$depth;
            }
        }
        return $counter;
    }

function ratePositions($material, $chessBoard, $whiteKingPosition) {
    $counter=0;
    global $pawnHeuristics;
    global $rookHeuristics;
    global $knightHeuristics;
    global $bishopHeuristics;
    global $queenHeuristics;
    global $kingHeuristicsMidGame;
    global $kingHeuristicsEndGame;
        for ($i=0; $i<64; $i++) {
        switch ($chessBoard[intval($i/8)][$i%8]) {
            case "P":
                $counter += $pawnHeuristics[intval($i/8)][$i%8];
                break;
            case "R":
                $counter += $rookHeuristics[intval($i/8)][$i%8];
                break;
            case "N":
                $counter += $knightHeuristics[intval($i/8)][$i%8];
                break;
            case "B":
                $counter += $bishopHeuristics[intval($i/8)][$i%8];
                break;
            case "Q":
                $counter += $queenHeuristics[intval($i/8)][$i%8];
                break;
            case "K":
                if ($material >= 1750) {
                    $counter+=$kingHeuristicsMidGame[intval($i/8)][$i%8];
                    $counter+= strlen(posibleA($whiteKingPosition, $chessBoard, $whiteKingPosition))*10;}
                else{
                    $counter+=$kingHeuristicsEndGame[intval($i/8)][$i%8];
                    $counter += strlen(posibleA($whiteKingPosition, $chessBoard, $whiteKingPosition))*30;}
                break;
        }
    }
        return $counter;
}
