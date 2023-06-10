<?php 

class SudokuSolver {
    public $board;
    private $originalBoard;

    public function __construct() {
        $this->board = array();
        $this->originalBoard = array();
    }

    public function loadBoardFromFile($filename) {
        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $row = array_map('intval', str_split($line));
            $this->board[] = $row;
            $this->originalBoard[] = $row;
        }
    }


    public function resetBoard() {
        $this->board = $this->originalBoard;
    }

    public function clearBoard() {
        $this->board = array();
        for ($i = 0; $i < 9; $i++) {
            $this->board[] = array_fill(0, 9, 0);
        }
    }

    public function makeCustomBoard($customBoard) {
        $this->board = $customBoard;
        $this->originalBoard = $customBoard;
    }

    public function saveBoardToFile($filename) {
        $content = '';
        foreach ($this->board as $row) {
            $content .= implode('', $row) . PHP_EOL;
        }
        file_put_contents($filename, $content);
    }

    public function isFixedCoordinate($row, $col) {
        return $this->originalBoard[$row][$col] !== 0;
    }

    public function solve() {
        $emptyCell = $this->findEmptyCell();
        if (!$emptyCell) {
            return true; // Sudoku solved
        }

        $row = $emptyCell['row'];
        $col = $emptyCell['col'];

        for ($num = 1; $num <= 9; $num++) {
            if ($this->isValidMove($row, $col, $num)) {
                $this->board[$row][$col] = $num;

                if ($this->solve()) {
                    return true;
                }

                $this->board[$row][$col] = 0; // Undo the move
            }
        }

        return false; // No solution found
    }

    private function findEmptyCell() {
        for ($row = 0; $row < 9; $row++) {
            for ($col = 0; $col < 9; $col++) {
                if ($this->board[$row][$col] === 0) {
                    return array('row' => $row, 'col' => $col);
                }
            }
        }
        return null;
    }

    private function isValidMove($row, $col, $num) {
        return $this->isValidRow($row, $num) &&
               $this->isValidColumn($col, $num) &&
               $this->isValidBox($row - $row % 3, $col - $col % 3, $num);
    }

    private function isValidRow($row, $num) {
        for ($col = 0; $col < 9; $col++) {
            if ($this->board[$row][$col] === $num) {
                return false;
            }
        }
        return true;
    }

    private function isValidColumn($col, $num) {
        for ($row = 0; $row < 9; $row++) {
            if ($this->board[$row][$col] === $num) {
                return false;
            }
        }
        return true;
    }

    private function isValidBox($startRow, $startCol, $num) {
        for ($row = 0; $row < 3; $row++) {
            for ($col = 0; $col < 3; $col++) {
                if ($this->board[$row + $startRow][$col + $startCol] === $num) {
                    return false;
                }
            }
        }
        return true;
    }

    public function createBoard() {
        // Create an empty 9x9 Sudoku board
        $board = array_fill(0, 9, array_fill(0, 9, 0));
    
        // Generate a random filled Sudoku board
        $this->fillRandomBoard($board);
    
        // Assign the generated board to $this->board and $this->originalBoard
        $this->board = $board;
        $this->originalBoard = $board;
    }

    private function fillRandomBoard(&$board) {
        // Create an array of numbers 1 to 9
        $numbers = range(1, 9);
    
        // Randomize the order of the numbers
        shuffle($numbers);
    
        // Fill the board using backtracking
        return $this->fillCell($board, 0, 0, $numbers);
    }
    
    private function fillCell(&$board, $row, $col, $numbers) {
        // Base case: if all cells are filled, the board is complete
        if ($row == 9) {
            return true;
        }
    
        // Calculate the next row and column indices
        $nextRow = ($col == 8) ? $row + 1 : $row;
        $nextCol = ($col == 8) ? 0 : $col + 1;
    
        // Shuffle the numbers for each new cell
        shuffle($numbers);
    
        foreach ($numbers as $num) {
            if ($this->isValidMoveNew($board, $row, $col, $num)) {
                $board[$row][$col] = $num;
    
                if ($this->fillCell($board, $nextRow, $nextCol, $numbers)) {
                    return true; // Found a valid solution
                }
    
                $board[$row][$col] = 0;
            }
        }
    
        return false; // No valid solution found
    }
    
    private function isValidMoveNew($board, $row, $col, $num) {
        // Check if the number already exists in the current row
        for ($c = 0; $c < 9; $c++) {
            if ($board[$row][$c] == $num) {
                return false;
            }
        }
    
        // Check if the number already exists in the current column
        for ($r = 0; $r < 9; $r++) {
            if ($board[$r][$col] == $num) {
                return false;
            }
        }
    
        // Check if the number already exists in the current 3x3 sub-grid
        $startRow = floor($row / 3) * 3;
        $startCol = floor($col / 3) * 3;
        for ($r = 0; $r < 3; $r++) {
            for ($c = 0; $c < 3; $c++) {
                if ($board[$startRow + $r][$startCol + $c] == $num) {
                    return false;
                }
            }
        }
    
        return true; // The number is valid in the current position
    }

    public function saveArrayToTxtFile($array, $filename) {

        $content = '';
        foreach ($array as $row) {
            $content .= implode('', $row) . PHP_EOL;
        }
        file_put_contents($filename, $content);

    }



    public function printBoard($board) {
        echo '<table class="sudoku-table">';
        foreach ($board as $row) {
            echo '<tr>';
            foreach ($row as $cell) {
                echo '<td class="sudoku-cell">' . $cell . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }
    
}



?>