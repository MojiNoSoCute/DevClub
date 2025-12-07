<?php

    require_once 'layout.php';

    session_start();
    require_once 'configs/connect.php';

    if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        $stmt = $conn->prepare("DELETE FROM members WHERE id = :id");
        $stmt->bindParam(':id', $delete_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Member deleted successfully.";
        } else {
            $_SESSION['error'] = "Member deleted fail!!!";
        }
        
        header("refresh:1.5; url=index.php");
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
</head>
<body>

    <!-- add member -->
    <div class="modal fade" id="memberModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add member</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="addmember.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="firstname" class="col-form-label">First Name</label>
                        <input type="text" required class="form-control" name="firstname">
                    </div>
                    <div class="mb-3">
                        <label for="lastname" class="col-form-label">Last Name</label>
                        <input type="text" required class="form-control" name="lastname">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="col-form-label">Email</label>
                        <input type="text" required class="form-control" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="years" class="col-form-label">Years</label>
                        <input type="number" required min="2560" class="form-control" name="years">
                    </div>
                    <div class="mb-3">
                        <label for="major" class="col-form-label">Majors</label>
                        <select class="form-select" required aria-label="Default select example" name="major">
                            <option value="1">software engineering</option>
                            <option value="2">computer</option>
                            <option value="3">teacher</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="submitmember" class="btn btn-success">Add Member</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- add major -->
    <div class="modal fade" id="majorModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Major</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="addmajor.php" method="post">
                    <div class="mb-3">
                        <label for="major" class="col-form-label">Major</label>
                        <input type="text" required class="form-control" name="major">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="submitmajor" class="btn btn-success">Add Major</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- index -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1>Member</h1>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <button type="button" class="btn btn-outline-primary mx-2 bi bi-mortarboard" data-bs-toggle="modal" data-bs-target="#majorModal"> Add major</button>
                <button type="button" class="btn btn-primary bi bi-person-add" data-bs-toggle="modal" data-bs-target="#memberModal"> Add member</button>
            </div>
        </div>
        <hr>
        <?php if (isset($_SESSION['success'])) { ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']);
                ?>
            </div>
        <?php } ?>
        <?php if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger">
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php } ?>

        <!-- Member data -->
         <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Firstname</th>
      <th scope="col">Lastname</th>
      <th scope="col">Email</th>
      <th scope="col">Years</th>
      <th scope="col">Major</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody class="table-group-divider">
    <?php
        $stmt = $conn->query("SELECT members.id, members.firstName, members.lastName, members.email, members.years, majors.major 
                          FROM members 
                          JOIN majors ON members.major = majors.id"); 
        $stmt->execute();
        $members = $stmt->fetchAll();

        if (!$members) {
            echo "<tr><td colspan='6' class='text-center'>No members found.</td></tr>";
        } else {
            foreach ($members as $member) {

            
    ?>
    <tr>
      <th scope="row"><?= $member['id'] ?></th>
      <td><?= $member['firstName'] ?></td>
      <td><?= $member['lastName'] ?></td>
      <td><?= $member['email'] ?></td>
      <td><?= $member['years'] ?></td>
      <td><?= $member['major'] ?></td>
      <td>
        <a href="editmember.php?id=<?= $member['id']; ?>" class="btn btn-warning">Edit</a>
        <a href="?delete=<?= $member['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure???, You want to delete?')">Delte</a>
      </td>
    </tr>
    <?php
             }
        }
        ?>
  </tbody>
</table>
    </div>


    <script  cript src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script> 
</body>
       
</html>