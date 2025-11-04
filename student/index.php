<?php 

require_once "./includes/header.php";




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

        <table class="table mt-5 text-center table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Units</th>
                    <th>With Lab</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const select = document.getElementById("semester");
    const studentID = <?= json_encode($student_id) ?>;
    const tbody = document.querySelector("table tbody");

    select.addEventListener("change", async () => {
        const value = select.value;
        if (!value) return;

        const [year, sem] = value.split("-");
        console.log("Fetching for:", studentID, year, sem);

        const res = await fetch(`api/fetch_subjects.php?student_id=${studentID}&year=${year}&sem=${sem}`);
        const data = await res.json();

        console.log("Subjects:", data);
        tbody.innerHTML = "";

        if (!data.length) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-muted">No subjects found</td></tr>`;
            return;
        }

       
        data.forEach((enrollment, index) => {
            enrollment.subjects.forEach((subject, subIndex) => {
                tbody.insertAdjacentHTML("beforeend", `
                    <tr>
                        <td>${subIndex + 1}</td>
                        <td>${subject.sub_code}</td>
                        <td>${subject.sub_name}</td>
                        <td>${subject.units}</td>
                        <td>${subject.withLab == 1 ? "Yes" : "No"}</td>
                    </tr>
                `);
            });
        });
    });
});
</script>

<?php require_once "./includes/footer.php"; ?>
