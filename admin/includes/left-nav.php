<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Nav Item - Curriculum -->
    <li class="nav-item">
        <a class="nav-link" href="curriculum.php">
            <i class="fas fa-fw fa-book"></i>
            <span>Curriculum</span>
        </a>
    </li>


    <!-- Nav Item - Curriculum -->
    <li class="nav-item">
        <a class="nav-link" href="advising.php">
            <i class="fas fa-fw fa-book"></i>
            <span>Advising</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Students -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStudents"
            aria-expanded="false" aria-controls="collapseStudents">
            <i class="fas fa-fw fa-user-graduate"></i>
            <span>Students</span>
        </a>
        <div id="collapseStudents" class="collapse" aria-labelledby="headingStudents" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="students.php">Add Students</a>
                <a class="collapse-item" href="enrolled_students.php">Enroll Students</a>
                <a class="collapse-item" href="student_information.php">Students Information</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Settings -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSettings"
            aria-expanded="false" aria-controls="collapseSettings">
            <i class="fas fa-fw fa-cogs"></i>
            <span>Settings</span>
        </a>
        <div id="collapseSettings" class="collapse" aria-labelledby="headingSettings" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="#">Departments</a>
                <a class="collapse-item" href="courses.php">Courses</a>
                <a class="collapse-item" href="subjects.php">Subjects</a>
            </div>
        </div>
    </li>
    <!-- Nav Items - Teachers -->
    <li class="nav-item">

        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTeachers"
            aria-expanded="false" aria-controls="collapseTeachers">
            <i class="fas fa-fw fa-chalkboard-teacher"></i>
            <span>Teachers</span>
        </a>
        <div id="collapseTeachers" class="collapse" aria-labelledby="headingTeachers" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="teachers.php">Teachers</a>
                <a class="collapse-item" href="assigned_subjects.php">Assign Subjects</a>
            </div>
        </div>

    </li>

    <!-- Nav Item - Users -->
    <li class="nav-item">
        <a class="nav-link" href="users.php">
            <i class="fas fa-fw fa-users"></i>
            <span>Users</span>
        </a>
    </li>

    <!-- Nav Item - Logs -->
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-history"></i>
            <span>Logs</span>
        </a>
    </li>

</ul>