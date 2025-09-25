<?php
session_start();
require "./includes/header.php";
require "../classes/Teacher.php";

$id = $_GET['id'];
$teacher = new Teacher();
$subjects = $teacher->getAvailableSubjects($id);
$rooms = ['CL1', 'CL2', 'CL3', '201', '202', '203', '303', '401', '402', 'Lib'];
?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
        require("includes/left-nav.php");
        ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Top bar -->
                <?php
                require "./includes/top-bar.php";
                ?>
                <!-- End of Top bar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Add Schedule -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Add Schedule</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Day</th>
                                            <th>Time Start</th>
                                            <th>Time End</th>
                                            <th>Room</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <!-- Subjects dropdown -->
                                            <td>
                                                <select id="subjectSelect" class="form-control">
                                                    <option value="">-- Select Subject --</option>
                                                    <?php foreach ($subjects as $sub): ?>
                                                        <option value="<?= $sub['sub_id'] ?>">
                                                            <?= htmlspecialchars($sub['sub_name']) ?> (<?= $sub['sub_code'] ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>

                                            <!-- Day dropdown -->
                                            <td>
                                                <select id="daySelect" class="form-control">
                                                    <option value="">-- Select Day --</option>
                                                    <option>Monday</option>
                                                    <option>Tuesday</option>
                                                    <option>Wednesday</option>
                                                    <option>Thursday</option>
                                                    <option>Friday</option>
                                                    <option>Saturday</option>
                                                </select>
                                            </td>

                                            <!-- Time start -->
                                            <td>
                                                <select id="timeStart" class="form-control"></select>
                                            </td>

                                            <!-- Time end -->
                                            <td>
                                                <select id="timeEnd" class="form-control"></select>
                                            </td>

                                            <!-- Room -->
                                            <td>
                                            <td>
                                                <select id="room" class="form-control">
                                                    <option value="">-- Select Room --</option>
                                                    <?php foreach ($rooms as $r): ?>
                                                        <option value="<?= $r ?>"><?= $r ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            </td>

                                            <!-- Add button -->
                                            <td>
                                                <button class="btn btn-primary btn-sm" id="addSchedule">Add</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                    <!-- DataTales -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Schedule List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Day</th>
                                            <th>Time Start</th>
                                            <th>Time End</th>
                                            <th>Room</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- dynamically appended rows -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->
            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php
    include("includes/logout-modal.php");
    ?>

    <!-- Bootstrap core JavaScript-->
    <?php
    include("includes/js-link.php");
    ?>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const subjectSelect = document.getElementById("subjectSelect");
            const daySelect = document.getElementById("daySelect");
            const timeStart = document.getElementById("timeStart");
            const timeEnd = document.getElementById("timeEnd");
            const room = document.getElementById("room");
            const addBtn = document.getElementById("addSchedule");
            const scheduleList = document.querySelector("#dataTable tbody");

            const teacherId = <?= json_encode((int) $id) ?>;


            const allSlots = [
                ["07:00:00", "08:00:00"],
                ["08:00:00", "09:00:00"],
                ["09:00:00", "10:00:00"],
                ["10:00:00", "11:00:00"],
                ["11:00:00", "12:00:00"],
                ["12:00:00", "13:00:00"],
                ["13:00:00", "14:00:00"],
                ["14:00:00", "15:00:00"],
                ["15:00:00", "16:00:00"],
                ["16:00:00", "17:00:00"],
                ["17:00:00", "18:00:00"],
                ["18:00:00", "19:00:00"]
            ];

            daySelect.addEventListener("change", function() {
                timeStart.innerHTML = "<option value=''>-- Select Start --</option>";
                timeEnd.innerHTML = "<option value=''>-- Select End --</option>";

                if (!daySelect.value) return;

                fetch(`ajax/get_available_slots.php?id=${teacherId}&day=${daySelect.value}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status !== "success") {
                            alert(data.message || "No slots available");
                            return;
                        }

                        timeStart.innerHTML = "<option value=''>-- Select Start --</option>";
                        timeEnd.innerHTML = "<option value=''>-- Select End --</option>";

                        data.slots.forEach(slot => {
                            const optStart = document.createElement("option");
                            optStart.value = slot.start;
                            optStart.textContent = formatTime(slot.start);
                            timeStart.appendChild(optStart);

                            const optEnd = document.createElement("option");
                            optEnd.value = slot.end;
                            optEnd.textContent = formatTime(slot.end);
                            timeEnd.appendChild(optEnd);
                        });
                    });

            });

            function formatTime(time) {
                const [hour, minute] = time.split(":");
                let h = parseInt(hour, 10);
                const suffix = h >= 12 ? "PM" : "AM";
                h = h % 12 || 12;
                return `${h}:${minute}${suffix}`;
            }


            function loadSchedules() {
                fetch(`ajax/get_schedule.php?id=${teacherId}`)
                    .then(res => res.json())
                    .then(data => {
                        scheduleList.innerHTML = "";
                        data.forEach(sch => {
                            const row = `
                    <tr>
                        <td>${sch.sub_name} (${sch.sub_code})</td>
                        <td>${sch.day}</td>
                        <td>${formatTime(sch.time_start)}</td>
                        <td>${formatTime(sch.time_end)}</td>
                        <td>${sch.room}</td>
                    </tr>`;
                            scheduleList.insertAdjacentHTML("beforeend", row);
                        });
                    })
                    .catch(err => {
                        console.error("Fetch error:", err);
                        alert("An error occurred while loading schedules.");
                    });
            }

            loadSchedules()


            addBtn.addEventListener("click", function(e) {
                e.preventDefault();


                if (!subjectSelect.value || !daySelect.value || !timeStart.value || !timeEnd.value || !room.value) {
                    alert("Please complete all fields.");
                    return;
                }


                const subjectText = subjectSelect.options[subjectSelect.selectedIndex].text;
                const dayText = daySelect.value;
                const startText = timeStart.value;
                const endText = timeEnd.value;
                const roomText = room.value;

                const formData = new FormData();
                formData.append("teacher_id", teacherId);
                formData.append("subject_id", subjectSelect.value);
                formData.append("day", daySelect.value);
                formData.append("time_start", timeStart.value);
                formData.append("time_end", timeEnd.value);
                formData.append("room", room.value);

                fetch("ajax/save_schedule.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === "success") {

                            loadSchedules();
                            subjectSelect.value = "";
                            daySelect.value = "";
                            timeStart.innerHTML = "";
                            timeEnd.innerHTML = "";
                            room.value = "";
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(err => {
                        console.error("Fetch error:", err);
                        alert("An error occurred while saving schedule.");
                    });
            });
        });
    </script>
</body>