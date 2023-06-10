<?php

function createBoard($solver)
{
    $solver->createBoard();
    $solver->printBoard($solver->board);
}

function makeCustomBoard($solver)
{
    $customBoard = $_POST['custom-board'];
    $customBoardArray = array_map(function ($row) {
        return str_split($row);
    }, str_split($customBoard, 9));
    $solver->makeCustomBoard($customBoardArray);
    $solver->printBoard($solver->board);
}

function solveCustomBoard($solver)
{
    $customBoard = $_POST['custom-board'];
    $customBoard = trim($customBoard);
    $rows = explode("\n", $customBoard);
    $customBoardArray = array_map(function ($row) {
        $row = str_split(trim($row));
        return array_map('intval', $row);
    }, $rows);

    // Check if the custom board is a valid 9x9 array
    if (count($customBoardArray) !== 9 || !isValid9x9Array($customBoardArray)) {
        $errorMessage = 'Invalid input. Please enter a valid 9x9 grid (use 0 for empty cells).';
        // Manipulate the error message here if needed
        echo '<h2>Error:</h2>';
        echo '<p class="text-danger">' . $errorMessage . '</p>';
        return;
    }

    $solver->makeCustomBoard($customBoardArray);
    echo '<h2>Original Sudoku Board:</h2>';
    $solver->printBoard($solver->board);
    echo '<br>';
    //echo '<h2>Solving Sudoku...</h2>';
    if ($solver->solve()) {
        echo '<h2>Sudoku Solved:</h2>';
        $solver->printBoard($solver->board);
    } else {
        echo '<h2>No solution found for the Sudoku puzzle.</h2>';
    }
}

function isValid9x9Array($array)
{
    if (count($array) !== 9) {
        return false;
    }

    foreach ($array as $row) {
        if (count($row) !== 9) {
            return false;
        }
    }

    return true;
}


function clearBoard($solver)
{
    $solver->clearBoard();
    $solver->printBoard($solver->board);
}

function saveBoard($solver)
{
    $serializedBoard = $_POST['board'];
    $board = json_decode($serializedBoard, true);
    $solver->saveArrayToTxtFile($board, 'sudoku-custom.txt');
}

function solveFromFile($solver)
{
    if (isset($_FILES['textFile']) && $_FILES['textFile']['error'] === UPLOAD_ERR_OK ) {
        $file = $_FILES['textFile'];
        $fileType = mime_content_type($file['tmp_name']);
        if ($fileType === 'text/plain') {
            $filePath = $file['tmp_name'];
            $solver->loadBoardFromFile($filePath);
            echo '<h2>Original Sudoku Board:</h2>';
            $solver->printBoard($solver->board);
            echo '<br>';
            //echo '<h2>Solving Sudoku...</h2>';
            if ($solver->solve()) {
                echo '<h2>Sudoku Solved:</h2>';
                $solver->printBoard($solver->board);
            } else {
                $errorMessage = 'No solution found for the Sudoku puzzle.';
                // Manipulate the error message here if needed
                echo '<h2>Error:</h2>';
                echo '<p>' . $errorMessage . '</p>';
            }
        } else {
            $errorMessage = 'Please upload a text file.';
            // Manipulate the error message here if needed
            echo '<h2>Error:</h2>';
            echo '<p class="text-danger">' . $errorMessage . '</p>';
        }
    } else {
        $errorMessage = 'Please upload a text file.';
        // Manipulate the error message here if needed
        echo '<h2>Error:</h2>';
        echo '<p class="text-danger">' . $errorMessage . '</p>';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $solver = new SudokuSolver();

    if (isset($_POST['create-board'])) {
        createBoard($solver);
    } elseif (isset($_POST['make-custom-board'])) {
        makeCustomBoard($solver);
    } elseif (isset($_POST['solve-custom-board'])) {
        solveCustomBoard($solver);
    } elseif (isset($_POST['clear-board'])) {
        clearBoard($solver);
    } elseif (isset($_POST['save-board'])) {
        saveBoard($solver);
    } elseif (isset($_POST['solve'])) {
        solveFromFile($solver);
    }
}

?>
