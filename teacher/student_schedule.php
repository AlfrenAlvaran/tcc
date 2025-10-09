<?php require_once __DIR__ . "/includes/header.php" ?>

<main class="container my-5">
    <div class="d-flex">
        <?php require_once __DIR__ . "/includes/sidebar.php" ?>
        <div class="flex-grow-1 pl-3">
            <input type="text" class="form-control" name="search" id="search" placeholder="search">
            <table class="table table-striped table-borderless text-center align-middle mt-2">
                <thead class="table-primary">
                    <tr>
                        <th>Student ID</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Course</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
</main>

<?php require_once __DIR__ . "/includes/footer.php" ?>