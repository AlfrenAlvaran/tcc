<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    include("includes/header.php");
    // include("../config/database.php");
    require_once __DIR__ . '/../classes/Students.php';
    $info = new Students();
    $student_id = $_GET['id'] ?? null;
    $info  = $info->getPersonalInfo($student_id);

    $student = $info['student'];
    $personal = $info['personal'];
    $uploads = $info['uploads'];

    ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
        include("includes/left-nav.php");
        ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php
                include("includes/top-bar.php");
                ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"></h1>


                    </div>

                    <!-- Content Row -->

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header p-4 d-flex align-items-center">
                            <i class="fas fa-fw fa-pen fa-lg text-primary me-2"></i>
                            <h6 class="m-0 font-weight-bold text-primary text-uppercase">
                                Edit Student Personal Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <form id="studentForm" enctype="multipart/form-data">
                                    <div class="row g-3">
                                        <!-- Basic Info -->
                                        <div class="col-md-4 text-center">
                                            <div class="position-relative">
                                                <?php if (!empty($uploads['profile'])): ?>
                                                    <img src="<?= htmlspecialchars($uploads['profile']) ?>"
                                                        class="rounded-circle border border-3 shadow-sm"
                                                        width="130" height="130"
                                                        alt="Profile Photo">
                                                    <div class="mt-2">
                                                        <label class="btn btn-outline-primary btn-sm">
                                                            <i class="bi bi-pencil-square me-1"></i> Change Photo
                                                            <input type="file" name="profile" class="d-none" accept="image/*">
                                                        </label>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="border rounded-circle d-flex justify-content-center align-items-center mx-auto"
                                                        style="width:130px; height:130px; background:#f8f9fa;">
                                                        <i class="bi bi-person fs-1 text-muted"></i>
                                                    </div>
                                                    <div class="mt-2">
                                                        <label class="btn btn-outline-success btn-sm">
                                                            <i class="bi bi-upload me-1"></i> Upload Photo
                                                            <input type="file" name="profile" class="d-none" accept="image/*">
                                                        </label>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" name="email" class="form-control"
                                                        value="<?= htmlspecialchars($personal['email']) ?>"
                                                        placeholder="Enter email">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Guardian Name</label>
                                                    <input type="text" name="gaurdian_name" class="form-control"
                                                        value="<?= htmlspecialchars($personal['gaurdian_name']) ?>"
                                                        placeholder="Enter guardian name">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Occupation</label>
                                                    <input type="text" name="occupation" class="form-control"
                                                        value="<?= htmlspecialchars($personal['occupation']) ?>"
                                                        placeholder="Enter occupation">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Guardian Contact No.</label>
                                                    <input type="text" name="guardian_no" class="form-control"
                                                        value="<?= htmlspecialchars($personal['guardian_no']) ?>"
                                                        placeholder="Enter contact number">
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="form-label">Guardian Address</label>
                                                    <textarea name="gaurdian_address" class="form-control" rows="2"
                                                        placeholder="Enter guardian address"><?= htmlspecialchars($personal['gaurdian_address']) ?></textarea>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Elementary School</label>
                                                    <input type="text" name="elementary" class="form-control"
                                                        value="<?= htmlspecialchars($personal['elementary']) ?>"
                                                        placeholder="Enter elementary school">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">High School</label>
                                                    <input type="text" name="high_school" class="form-control"
                                                        value="<?= htmlspecialchars($personal['high_school']) ?>"
                                                        placeholder="Enter high school">
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="form-label">Last College Attended</label>
                                                    <input type="text" name="last_college_attend" class="form-control"
                                                        value="<?= htmlspecialchars($personal['last_college_attend']) ?>"
                                                        placeholder="Enter last college attended">
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="form-label">Upload Documents (PDF, DOCX, etc.)</label>
                                                    <?php if (!empty($uploads['documents'])): ?>
                                                        <div class="d-flex align-items-center">
                                                            <a href="../<?= htmlspecialchars($uploads['documents']) ?>"
                                                                target="_blank"
                                                                class="btn btn-outline-secondary btn-sm me-3">
                                                                <i class="bi bi-file-earmark-text me-1"></i> View Current Document
                                                            </a>
                                                            <label class="btn btn-outline-primary btn-sm mb-0">
                                                                <i class="bi bi-upload me-1"></i> Replace File
                                                                <input type="file" name="documents" class="d-none" accept=".pdf,.doc,.docx">
                                                            </label>
                                                        </div>
                                                    <?php else: ?>
                                                        <input type="file" name="documents" class="form-control" accept=".pdf,.doc,.docx">
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 text-end">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-save me-2"></i>Save Changes
                                        </button>
                                    </div>
                                </form>
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
        const form = document.getElementById('studentForm');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            formData.append('student_id', '<?= $student_id ?>');

            const res = await fetch('ajax/save_info.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();
            if (data.status === 'success') {
                alert('✅ Information saved successfully!');
                location.reload();
            } else {
                alert('❌ ' + data.message);
            }
        });
    </script>

</body>

</html>