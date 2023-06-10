<?php

include("src/SudokuSolver.php");

$solver = new SudokuSolver();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sudoku Solver</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">

        <h1>Sudoku Solver</h1>

        <?php

            include("actions/process.php");

        ?>

        <div class="form-container">
            <h2>Create New Board</h2>
            <form method="POST">
                <div class="btn-container">
                    <button type="submit" class="btn btn-primary" name="create-board">Create New Board</button>
                    <button type="submit" class="btn btn-danger" name="clear-board">Clear Board</button>
                </div>
            </form>
        </div>

        <div class="form-container">
            <h2>Make Custom Board</h2>
            <form method="POST">
                <div class="mb-3">
                    <label for="custom-board" class="form-label">Enter a 9x9 grid (use 0 for empty cells)</label>
                    <textarea name="custom-board" id="custom-board" rows="9" cols="9" class="form-control"></textarea>
                </div>
                <div class="btn-container">
                    <button type="submit" class="btn btn-primary" name="solve-custom-board">Solve Custom Board</button>
                    <input type="hidden" name="board" value="<?php echo htmlspecialchars(json_encode($solver->board)); ?>">
                    <button type="submit" class="btn btn-danger" name="save-board">Save Custom Board</button>
                </div>
            </form>
        </div>

        <div class="form-container">
            <h2>Default</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="textFile" class="form-label">Upload a text file</label>
                    <input type="file" name="textFile" id="textFile" class="form-control" accept="text/plain">
                </div>
                <div class="btn-container">
                    <button type="submit" class="btn btn-primary" name="solve">Solve From Text</button>
                </div>
            </form>
        </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
