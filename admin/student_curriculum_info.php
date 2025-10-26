<?php
session_start();
include("includes/header.php");
require_once __DIR__ . '/../classes/NotTakenCurriculum.php';

$notTakenCurriculum = new NotTakenCurriculum();
$program_id = (int) $_GET['program'];
$studentID = (string) $_GET['id'];

$student_info = $notTakenCurriculum->getStudentBasicInfo($studentID);
$curriculumId = (int) $student_info['cur_id'];

$notTakenSubjects = $notTakenCurriculum->notTakenSubjects($studentID, $curriculumId);
$taken = $notTakenCurriculum->getCurriculumByStudent($studentID);
?>

<!DOCTYPE html>
<html lang="en">

<head>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include("includes/left-nav.php"); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include("includes/top-bar.php"); ?>

                <div class="container-fluid">
                    <div class="card-body">
                        <?php if ($student_info): ?>
                            <table class="table table-borderless table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <th style="width: 150px;">Student No.</th>
                                        <td><?= $student_info['SY'] ?>-<?= htmlspecialchars($student_info['Student_id']) ?> [Student Info]</td>
                                    </tr>
                                    <tr>
                                        <th>Student Name</th>
                                        <td>
                                            <?= strtoupper($student_info['Student_LName']) . ', ' .
                                                strtoupper($student_info['Student_FName']) . ' ' .
                                                strtoupper($student_info['Student_MName']); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Course</th>
                                        <td><?= htmlspecialchars($student_info['p_code']); ?></td>
                                    </tr>
                                    <tr>
                                        <th style="width: 200px;">School Year/Semester</th>
                                        <td>
                                            <?= $student_info['sy'] ?>
                                            <?= $student_info['sem'] == 1 ? '1st' : ($student_info['sem'] == 2 ? '2nd' : $student_info['sem'] . 'th') ?>
                                            Semester
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php endif; ?>

                        <!-- TAKEN SUBJECTS -->
                        <div class="card shadow mt-3">
                            <?php
                            $grouped = [];

                            if (!empty($taken)) {
                                foreach ($taken as $row) {
                                    $year = (int)$row['level'];
                                    $sem = (int)$row['semester'];
                                    $schoolYear = htmlspecialchars($row['sy']);

                                    $yearLabel = match ($year) {
                                        1 => "1st Year",
                                        2 => "2nd Year",
                                        3 => "3rd Year",
                                        4 => "4th Year",
                                        default => "{$year}th Year"
                                    };

                                    $semLabel = match ($sem) {
                                        1 => "1st Semester",
                                        2 => "2nd Semester",
                                        default => "{$sem}th Semester"
                                    };

                                    $semKey = "{$yearLabel} - {$semLabel} (S.Y. {$schoolYear})";
                                    $grouped[$semKey][] = $row;
                                }
                            }
                            ?>

                            <div class="card-body">
                               
                                <table class="table table-borderless table-sm">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject Name</th>
                                            <th>Units</th>
                                            <th>With Lab</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($grouped)): ?>
                                            <?php foreach ($grouped as $semKey => $rows): ?>
                                                <tr class="table-primary">
                                                    <td colspan="5"><strong><?= $semKey ?></strong></td>
                                                </tr>
                                                <?php foreach ($rows as $r): ?>
                                                    <?php foreach ($r['subjects'] as $sub): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($sub['sub_code']) ?></td>
                                                            <td><?= htmlspecialchars($sub['sub_name']) ?></td>
                                                            <td class="text-center"><?= htmlspecialchars($sub['units']) ?></td>
                                                            <td class="text-center"><?= $sub['withLab'] ? 'Yes' : 'No' ?></td>
                                                            <td class="text-center text-success">✓</td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endforeach; ?>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="5" class="text-center">No subjects taken yet.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- NOT TAKEN SUBJECTS -->
                        <div class="card shadow mt-4">
                            <div class="card-body">
                               
                                <table class="table table-borderless table-sm mb-1">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject Name</th>
                                            <th>Units</th>
                                            <th>With Lab</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($notTakenSubjects)): ?>
                                            <?php foreach ($notTakenSubjects as $subject): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($subject['sub_code']) ?></td>
                                                    <td><?= htmlspecialchars($subject['sub_name']) ?></td>
                                                    <td><?= htmlspecialchars($subject['units']) ?></td>
                                                    <td><?= $subject['withLab'] ? 'Yes' : 'No' ?></td>
                                                    <td class="text-danger">Not Taken</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center">No subjects available to add.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2025</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include("includes/logout-modal.php"); ?>
    <?php include("includes/js-link.php"); ?>
</body>
</html>
