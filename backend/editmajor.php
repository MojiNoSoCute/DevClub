<?php

    require_once '../configs/connect.php';
    require_once '../configs/requireLogin.php';


    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $major = $_POST['major'];
    

        $sql = "UPDATE majors SET major = :major WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':major', $major);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Major updated successfully."; 
        } else {
            $_SESSION['error'] = "Major updated fail!!!";
        }

        header("location: major.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Major</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
</head>
<body>

            <!-- fetch data -->
            <?php 
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $stmt = $conn->prepare("SELECT * FROM majors WHERE id = :id");
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    $data = $stmt->fetch();
                }
            ?>

    <!-- index -->
    <div class="container mt-5">
        <h1>Edit Major Id : <?= $data['id'] ?> </h1>
        <hr>
        <form action="editmajor.php" method="post">
                    <input type="hidden" value="<?= $data['id'] ?>" required class="form-control" name="id">
                <div class="mb-3">                       
                    <label for="major" class="col-form-label">Major</label>
                    <input type="text" value="<?= $data['major'] ?>" required class="form-control" name="major">
                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary mx-1" href="major.php">Go Back</a>
                    <button type="submit" name="update" class="btn btn-success">Update</button>
                </div>
            </form>
    </div>


    <script  cript src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script> 
</body>
       
</html>