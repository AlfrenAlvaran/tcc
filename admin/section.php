<?php
session_start();
include("includes/header.php");
include("../config/database.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Sections</title>
</head>

<body id="page-top">

    <div id="wrapper">
        <?php include("includes/left-nav.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include("includes/top-bar.php"); ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sectionModal">
                            Add Section
                        </button>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Sections</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Section</th>
                                            <th>Year</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Section Modal -->
                <div class="modal fade" id="sectionModal" tabindex="-1" role="dialog" aria-labelledby="sectionModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="sectionModalLabel">Add Section</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="sectionForm">
                                    <div class="mb-3">
                                        <label for="section_name" class="form-label">Section</label>
                                        <input type="text" class="form-control" id="section_name" name="section_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="year_level" class="form-label">Year Level</label>
                                        <select class="form-control" id="year_level" name="year_level" required>
                                            <option value="">-- Select Year --</option>
                                            <option value="1">1st Year</option>
                                            <option value="2">2nd Year</option>
                                            <option value="3">3rd Year</option>
                                            <option value="4">4th Year</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                                </form>
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

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            loadSections();

            const form = document.getElementById("sectionForm");

            form.addEventListener("submit", async function (e) {
                e.preventDefault();
                const formData = new FormData(this);

                try {
                    const response = await fetch("ajax/save-section.php", {
                        method: "POST",
                        body: formData
                    });
                    const result = await response.json();

                    if (result.status === "success") {
                        alert(result.message);
                        $('#sectionModal').modal('hide');
                        this.reset();
                        loadSections();
                    } else {
                        alert(result.message);
                    }
                } catch (err) {
                    console.error(err);
                    alert("An unexpected error occurred.");
                }
            });

            async function loadSections() {
                try {
                    const response = await fetch("api/fetch_section.php");
                    const result = await response.json();

                    const tbody = document.querySelector("#dataTable tbody");
                    tbody.innerHTML = "";

                    if (result.status === "success" && result.data.length > 0) {
                        result.data.forEach((row) => {
                            const tr = document.createElement("tr");
                            tr.innerHTML = `
                                <td>${row.section_name}</td>
                                <td>${row.year_level}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger" onclick="deleteSection(${row.id})">Delete</button>
                                </td>
                            `;
                            tbody.appendChild(tr);
                        });
                    } else {
                        tbody.innerHTML = `<tr><td colspan="3" class="text-center">${result.message || "No sections found."}</td></tr>`;
                    }
                } catch (error) {
                    console.error(error);
                }
            }

            window.deleteSection = async (id) => {
                if (!confirm("Are you sure you want to delete this section?")) return;

                try {
                    const response = await fetch(`ajax/delete-section.php?id=${id}`);
                    const result = await response.json();

                    if (result.status === "success") {
                        alert(result.message);
                        loadSections();
                    } else {
                        alert(result.message);
                    }
                } catch (err) {
                    console.error(err);
                }
            };
        });
    </script>

</body>
</html>
