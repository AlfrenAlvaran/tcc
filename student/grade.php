<?php
require_once "./includes/header.php";
require_once __DIR__ . "/../classes/Students.php";

$student = new Students();
$semesters = $student->getSemestersEnrolled($student_id);



?>

<div class="container my-4">
    <div class="d-flex justify-content-center mt-4 flex-column align-items-center">
        <select name="semester" id="semester" class="form-control w-50">
            <option value="">- Select Semester -</option>
            <?php foreach ($semesters as $semester): ?>
                <option value="<?= $semester['yr_level'] ?>-<?= $semester['sem'] ?>">
                    <?= $semester['sy'] ?> — 
                    <?= $semester['sem'] == 1 ? '1st Semester' : ($semester['sem'] == 2 ? '2nd Semester' : $semester['sem']) ?>
                    (Year <?= $semester['yr_level'] ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <div class="w-100 mt-4">
            <table class="table table-striped table-bordered text-center align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Program</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody id="grade-list">
                    <tr>
                        <td colspan="5" class="text-muted">Select a semester to view grades</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const select = document.getElementById("semester");
    const studentID = <?= json_encode($student_id) ?>;
    const gradeList = document.getElementById("grade-list");

    select.addEventListener("change", async () => {
        const value = select.value;
        gradeList.innerHTML = ""; // clear previous results

        if (!value) return;

        const [year, sem] = value.split("-");
        const res = await fetch(`api/fetch_grades.php?studentID=${studentID}&year=${year}&sem=${sem}`);
        const data = await res.json();

        if (!data.length) {
            gradeList.innerHTML = `<tr><td colspan="5" class="text-muted">No grades found</td></tr>`;
            return;
        }

        data.forEach((enrollment) => {
            enrollment.subjects.forEach((subject) => {
                gradeList.insertAdjacentHTML("beforeend", `
                    <tr>
                        <td>${subject.sub_code}</td>
                        <td>${subject.sub_name}</td>
                        <td>${enrollment.p_code}</td>
                        <td>${subject.grade ?? 'Not Encoded'}</td>
                    </tr>
                `);
            });
        });
    });
});
</script>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        const select = document.getElementById("semester");
        const studentID = <?= json_encode($student_id) ?>;
        const subjectList = document.getElementById("subject-list");

        select.addEventListener("change", async () => {
            const value = select.value;
            subjectList.innerHTML = ""; // clear previous results

            if (!value) return;

            const [year, sem] = value.split("-");
            console.log("Fetching for:", studentID, year, sem);

            const res = await fetch(`api/fetch_schedules.php?studentID=${studentID}&year=${year}&sem=${sem}`);
            const data = await res.json();

            console.log("Subjects:", data);

            if (!data.length) {
                subjectList.innerHTML = `<tr><td colspan="6" class="text-muted">No subjects found</td></tr>`;
                return;
            }

            data.forEach((enrollment) => {
                enrollment.subjects.forEach((subject) => {
                  
                    if (!subject.schedules || subject.schedules.length === 0) {
                        subjectList.insertAdjacentHTML("beforeend", `
                            <tr>
                                <td>${subject.sub_code}</td>
                                <td>${subject.sub_name}</td>
                                <td>${enrollment.p_code}</td>
                                <td colspan="3" class="text-muted">TBA</td>
                                
                            </tr>
                        `);
                        return;
                    }

                   
                    subject.schedules.forEach((schedule) => {
                        const start = schedule.time_start ? formatTime(schedule.time_start) : 'TBA';
                        const end = schedule.time_end ? formatTime(schedule.time_end) : 'TBA';
                        const time = (start !== 'TBA' && end !== 'TBA') ? `${start} - ${end}` : 'TBA';

                        subjectList.insertAdjacentHTML("beforeend", `
                            <tr>
                                <td>${subject.sub_code}</td>
                                <td>${subject.sub_name}</td>
                                <td>${enrollment.p_code}</td>
                                <td>${schedule.day || 'TBA'}</td>
                                <td>${time}</td>
                                <td>${schedule.room || 'TBA'}</td>
                                <td>${schedule.teacher_name || 'TBA'}</td>
                            </tr>
                        `);
                    });
                });
            });
        });
    });

  
    function formatTime(timeStr) {
        if (!timeStr) return "TBA";
        const [hour, minute] = timeStr.split(":");
        let h = parseInt(hour, 10);
        const ampm = h >= 12 ? "PM" : "AM";
        h = h % 12 || 12;
        return `${h}:${minute} ${ampm}`;
    }
</script>

<?php require_once "./includes/footer.php"; ?>
