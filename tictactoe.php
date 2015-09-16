<?php

$players = ["X", "O"];
$winner = false;
$turnCount = 0; //keep track of how many turns have been taken

$current_player_idx = getPlayerIdx();
$player = $players[$current_player_idx];
$next_player_idx = getNextPlayerIdx($current_player_idx);

// set board to all nulls
$board = [
    [null, null, null],
    [null, null, null],
    [null, null, null]
];

// main()
if(isset($_POST['select']))
{
    $parts = explode(',', $_POST['select']); // $parts contains the row, col of selected cell

    $board[$parts[0]][$parts[1]] = $player; // sets piece to X or O
    // refreshes board
    if(isset($_POST['board']))
    {
        forEach ($_POST['board'] as $rowidx => $row)
        {
            forEach ($row as $colidx => $col)
            {
                $board[$rowidx][$colidx] = $col;
            }
        }
    }

    isWinner($player);
}


function getCell($row, $col)
{
    global $board;
    global $winner;

    $val = $board[$row][$col];

    if(is_null($val) && $winner == true)
    {
        return "<input type='hidden' value='$row,$col' name='select' />";
    }
    elseif(is_null($val))
    {
        return "<input type='submit' value='$row,$col' name='select' />";
    }
    else
    { // save X or O in virtual table
        return "<h2>$val</h2><input type='hidden' name='board[$row][$col]' value='$val' />";
    }

}

function getPlayerIdx()
{
    $val = 1;

    if(isset($_POST['player']))
    {
        $val = intval($_POST['player']);
    }

    return $val;
}

function getNextPlayerIdx($idx)
{
    global $players;

    $val = $idx;
    $val++;
    if($val >= count($players)) $val = 0;
    return $val;

}

function debug($val)
{
    $output = print_r($val, true);
    echo "<pre>" . $output . "</pre>";
}

// After each move check 2 things
// 1) if all cells are filled and there is no winner
// 2) if there are any rows, cols, diagonals filled with same player
function isWinner($player)
{
//    echo "isWinner just ran <br />";
    global $board;
    global $winner;
    // initialize variables
    $boardRows = count($board);
    $boardCols = count($board[0]);
    $boardNulls = 0;

    //count nulls
    for($i=0; $i < $boardRows; $i++)
    {
        for($j=0; $j < $boardCols; $j++)
        {
            if(is_null($board[$i][$j]))
            {
                $boardNulls++;
            }
        }
    }

    if (  // test for winner
        //upper left to lower right diag
        ($board[0][0]== $player && $board[1][1]==$player && $board[2][2]==$player) ||
        //lower left to upper right diag
        ($board[2][0]==$player && $board[1][1]==$player && $board[0][2]==$player) ||
        //first row
        ($board[0][0]==$player && $board[0][1]==$player && $board[0][2]==$player) ||
        // second row
        ($board[1][0]==$player && $board[1][1]==$player && $board[1][2]==$player) ||
        // third row
        ($board[2][0]==$player && $board[2][1]==$player && $board[2][2]==$player) ||
        // first column
        ($board[0][0]==$player && $board[1][0]==$player && $board[2][0]==$player) ||
        // second column
        ($board[0][1]==$player && $board[1][1]==$player && $board[2][1]==$player) ||
        // third column
        ($board[0][2]==$player && $board[1][2]==$player && $board[2][2]==$player) )
    {
        $winner = true;
        print "<h3>Winner Winner Chicken Dinner! $player wins!</h3>";

    }
    elseif ($boardNulls == 0) // test for board full
    {
        print "<h3>GAME OVER No Winner! :-( Try again!</h3>";
    }
}

?>

<html>
<head>
    <title>Tic Tac Toe</title>
</head>
<body>

<form method="POST">
    <input type="hidden" value="<?= $next_player_idx; ?>"  name="player" />
    <table border="1", cellspacing="0" cellpadding="25">
        <tr>
            <td><?= getCell(0,0); ?></td>
            <td><?= getCell(0,1); ?></td>
            <td><?= getCell(0,2); ?></td>
        </tr>
        <tr>
            <td><?= getCell(1,0); ?></td>
            <td><?= getCell(1,1); ?></td>
            <td><?= getCell(1,2); ?></td>
        </tr>
        <tr>
            <td><?= getCell(2,0); ?></td>
            <td><?= getCell(2,1); ?></td>
            <td><?= getCell(2,2); ?></td>
        </tr>
    </table>
</form>
<a href="<?= $_SERVER['PHP_SELF']; ?>">Reset</a>
<?= debug($board); ?>
</body>
</html>