<?php require_once "./includes/header.php"; ?>
<?php
require_once __DIR__ . "/../classes/Curriculum.php";
$curriculum = new Curriculum();
$announcements = $curriculum->getAnnouncements();

?>
<style>
    body {
        background-color: #f8f9fa;
    }

    .announcement-card {
        border-left: 4px solid #0d6efd;
        transition: all 0.2s ease-in-out;
    }

    .announcement-card:hover {
        background-color: #e9f3ff;
        transform: translateY(-3px);
    }

    .announcement-title {
        font-weight: 600;
        color: #0d6efd;
        margin-bottom: 4px;
    }

    .announcement-date {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 2px;
    }

    .announcement-author {
        font-size: 0.9rem;
        color: #495057;
    }
</style>
<div class="container my-4">

    <div class="container py-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold">📢 Announcements</h2>
        </div>

        <div class="row g-3">
            <!-- Announcement Item -->
            <?php foreach ($announcements as $announce): ?>

                <div class="col-md-6 col-lg-4">
                    <div class="card announcement-card p-3 shadow-sm">
                        <div class="announcement-title"><?= $announce['title'] ?></div>
                        <div class="announcement-date"><?= $announce['date'] ?></div>
                        <div class="announcement-author">Created by: <?= $announce['create_at'] ?></div>
                    </div>
                </div>

            <?php endforeach; ?>



        </div>
    </div>
</div>



<?php require_once "./includes/footer.php"; ?>