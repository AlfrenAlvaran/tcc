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
                <tbody>
                    <tr>
                        <td>S25-0001</td>
                        <td>Alfren</td>
                        <td>Balan</td>
                        <td>Alvaran</td>
                        <td>BSCS</td>
                        <td class="d-flex g-4">
                            <select name="semesterAndYear" id="" class="form-control form-select-sm mr-2">
                                <option value="">1st Sem SY 2023-2024</option>
                                <option value="">2nd Sem SY 2023-2024</option>
                            </select>
                            <button class="btn btn-primary btn-sm">View</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</main>

<?php require_once __DIR__ . "/includes/footer.php" ?>