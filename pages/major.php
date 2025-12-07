<?php

    ob_start();

    session_start();
    require_once '../configs/connect.php';

    if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        $stmt = $conn->prepare("DELETE FROM mojors WHERE id = :id");
        $stmt->bindParam(':id', $delete_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Major deleted successfully.";
        } else {
            $_SESSION['error'] = "Major deleted fail!!!";
        }
        
        header("refresh:1.5; url=major.php");
    }

?>

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
                <h1>Majors</h1>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <button type="button" class="btn btn-primary mx-2 bi bi-mortarboard" data-bs-toggle="modal" data-bs-target="#majorModal"> Add major</button>
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

        <!-- Majors data -->
         <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Major</th>
    <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody class="table-group-divider">
    <?php
        $stmt = $conn->query("SELECT * FROM majors"); 
        $stmt->execute();
        $majors = $stmt->fetchAll();

        if (!$majors) {
            echo "<tr><td colspan='6' class='text-center'>No majors found.</td></tr>";
        } else {
            foreach ($majors as $major) {

            
    ?>
    <tr>
      <th scope="row"><?= $major['id'] ?></th>
      <td><?= $major['major'] ?></td>
      <td>
        <a href="editmajor.php?id=<?= $major['id']; ?>" class="btn btn-warning">Edit</a>
        <a href="?delete=<?= $major['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure???, You want to delete?')">Delte</a>
      </td>
    </tr>
    <?php
             }
        }
        ?>
  </tbody>
</table>
    </div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../components/layouts/backlayout.php';
?>