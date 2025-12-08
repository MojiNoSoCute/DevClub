<?php

    require_once '../configs/connect.php';
    require_once '../configs/requireLogin.php';


    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $years = $_POST['years'];
        $major = $_POST['major'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Invalid email format!";
            header("Location: member.php");
            exit();
        }

        $sql = "UPDATE members SET firstName = :firstname, lastName = :lastname, email = :email, years = :years, major = :major WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':years', $years);
        $stmt->bindParam(':major', $major);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Member updated successfully."; 
        } else {
            $_SESSION['error'] = "Member updated fail!!!";
        }

        header("location: member.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
</invoke></head>

<body>

            <!-- fetch data -->
            <?php 
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $stmt = $conn->prepare("SELECT members.id, 
                               members.firstName, 
                               members.lastName, 
                               members.email, 
                               members.years, 
                               members.major AS major_id,
                               majors.major 
                        FROM members 
                        JOIN majors ON members.major = majors.id
                        WHERE members.id = :id");
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    $data = $stmt->fetch();

                    
                }
            ?>
            

    <!-- index -->
    <div class="container mt-5">
        <h1>Edit Member Id : <?= $data['id'] ?> </h1>
        <hr>
        <form action="editmember.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" value="<?= $data['id'] ?>" required class="form-control" name="id">
                <div class="mb-3">                       
                    <label for="firstname" class="col-form-label">First Name</label>
                    <input type="text" value="<?= $data['firstName'] ?>" required class="form-control" name="firstname">
                </div>
                <div class="mb-3">
                    <label for="lastname" class="col-form-label">Last Name</label>
                    <input type="text" value="<?= $data['lastName'] ?>" required class="form-control" name="lastname">
                </div>
                <div class="mb-3">
                    <label for="email" class="col-form-label">Email</label>
                    <input type="text" value="<?= $data['email'] ?>" required class="form-control" name="email">
                </div>
                <div class="mb-3">
                    <label for="years" class="col-form-label">Years</label>
                    <input type="number" value="<?= $data['years'] ?>" required min="2560" class="form-control" name="years">
                </div>
                <div class="mb-3">
                    <label for="major" class="col-form-label">Majors</label>

                    <?php 
                        $stmt = $conn->prepare("SELECT * FROM majors");
                                $stmt->execute();
                                $majors = $stmt->fetchAll();
                    ?>
                    <select class="form-select" required size="5" aria-label="Size 3 select example" name="major">
                        <?php 
                            if (!$majors) {
                                echo "<option disabled>No majors found.</option>";
                            } else {
                                foreach ($majors as $major) {
                                    $selected = ($major['id'] == $data['major_id']) ? 'selected' : '';
                                    echo "<option value='{$major['id']}' $selected>{$major['major']}</option>";
                                }
                            }
                        ?>
                    </select>

                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary mx-1" href="member.php">Go Back</a>
                    <button type="submit" name="update" class="btn btn-success">Update</button>
                </div>
            </form>
    </div>


    <script  cript src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script> 
</body>
       
</html>
