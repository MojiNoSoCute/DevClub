<?php

    ob_start();

    session_start();
    require_once '../configs/connect.php';

    if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        $stmt = $conn->prepare("DELETE FROM members WHERE id = :id");
        $stmt->bindParam(':id', $delete_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Member deleted successfully.";
        } else {
            $_SESSION['error'] = "Member deleted fail!!!";
        }
        
        header("refresh:1.5; url=member.php");
    }

?>
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
                            <!-- fetch data -->
                            <?php 
                                    $stmt = $conn->prepare("SELECT * FROM majors");
                                    $stmt->execute();
                                    $majors = $stmt->fetchAll();

                                    if (!$majors) {
                                    echo "<option colspan='6' class='text-center'>No majors found.</option>";
                                } else {
                                    foreach ($majors as $major) {
                            ?>
                            <option value="<?= $major['id'] ?>"><?= $major['major'] ?></option>
                            <?php 
                                } 
                            }
                            ?>
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

    <!-- index -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1 class="bi bi-people-fill"> Members</h1>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
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

<?php
$content = ob_get_clean();
include __DIR__ . '/../components/layouts/backlayout.php';
?>