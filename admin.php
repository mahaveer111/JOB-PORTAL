<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';
include 'header.php';

$msg = "";
if (isset($_POST['post_job'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $company = $conn->real_escape_string($_POST['company']);
    $location = $conn->real_escape_string($_POST['location']);
    $category = $conn->real_escape_string($_POST['category']);
    $hr_name = $conn->real_escape_string($_POST['hr_name']);
    $hr_email = $conn->real_escape_string($_POST['hr_email']);
    $hr_phone = $conn->real_escape_string($_POST['hr_phone']);
    $openings = intval($_POST['openings']);
    $salary = $conn->real_escape_string($_POST['salary']);
    $description = $conn->real_escape_string($_POST['description']);

    if (!preg_match('/^[0-9]{10}$/', $hr_phone)) {
        $msg = '<div class="alert alert-danger alert-dismissible fade show">Please enter a valid 10-digit mobile number! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    } else {
        $conn->query("INSERT INTO jobs (title, company, location, category, hr_name, hr_email, hr_phone, openings, salary, description) VALUES ('$title', '$company', '$location', '$category', '$hr_name', '$hr_email', '$hr_phone', $openings, '$salary', '$description')");
        $msg = '<div class="alert alert-success alert-dismissible fade show">Job vacancy posted successfully! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
}

if (isset($_POST['update_job'])) {
    $id = intval($_POST['job_id']);
    $title = $conn->real_escape_string($_POST['title']);
    $company = $conn->real_escape_string($_POST['company']);
    $location = $conn->real_escape_string($_POST['location']);
    $category = $conn->real_escape_string($_POST['category']);
    $hr_name = $conn->real_escape_string($_POST['hr_name']);
    $hr_email = $conn->real_escape_string($_POST['hr_email']);
    $hr_phone = $conn->real_escape_string($_POST['hr_phone']);
    $openings = intval($_POST['openings']);
    $salary = $conn->real_escape_string($_POST['salary']);
    $description = $conn->real_escape_string($_POST['description']);

    if (!preg_match('/^[0-9]{10}$/', $hr_phone)) {
        $msg = '<div class="alert alert-danger alert-dismissible fade show">Please enter a valid 10-digit mobile number! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    } else {
        $conn->query("UPDATE jobs SET title='$title', company='$company', location='$location', category='$category', hr_name='$hr_name', hr_email='$hr_email', hr_phone='$hr_phone', openings=$openings, salary='$salary', description='$description' WHERE id=$id");
        header("Location: admin.php");
        exit();
    }
}

if (isset($_GET['delete_job'])) {
    $conn->query("DELETE FROM jobs WHERE id=" . intval($_GET['delete_job']));
    header("Location: admin.php");
    exit();
}

if (isset($_GET['app_id']) && isset($_GET['status'])) {
    $conn->query("UPDATE applications SET status='" . $conn->real_escape_string($_GET['status']) . "' WHERE id=" . intval($_GET['app_id']));
    header("Location: admin.php");
    exit();
}

if (isset($_GET['delete_app'])) {
    $conn->query("DELETE FROM applications WHERE id=" . intval($_GET['delete_app']));
    header("Location: admin.php");
    exit();
}

$edit_mode = false;
$edit_job = null;
if (isset($_GET['edit_job'])) {
    $edit_mode = true;
    $res = $conn->query("SELECT * FROM jobs WHERE id=" . intval($_GET['edit_job']));
    $edit_job = $res->fetch_assoc();
}

$job_count_res = $conn->query("SELECT COUNT(*) as total FROM jobs");
$job_count_row = $job_count_res->fetch_assoc();
$total_posted_jobs = $job_count_row['total'];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Admin Control Dashboard</h2>
        <p class="text-muted mb-0">Total Active Job Postings: <strong><?php echo $total_posted_jobs; ?></strong></p>
    </div>
    <div>
        <span class="me-3 fw-bold text-dark">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        <a href="logout.php" class="btn btn-danger btn-sm fw-bold px-3">Logout</a>
    </div>
</div>

<?php echo $msg; ?>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white fw-bold">
                <?php echo $edit_mode ? 'Edit Job Vacancy' : 'Post New Job Vacancy'; ?>
            </div>
            <div class="card-body">
                <form method="POST">
                    <?php if ($edit_mode): ?>
                        <input type="hidden" name="job_id" value="<?php echo $edit_job['id']; ?>">
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Job Title</label>
                        <input type="text" name="title" class="form-control" value="<?php echo $edit_mode ? htmlspecialchars($edit_job['title']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Company Name</label>
                        <input type="text" name="company" class="form-control" value="<?php echo $edit_mode ? htmlspecialchars($edit_job['company']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Job Location (City / Place)</label>
                        <input type="text" name="location" class="form-control" value="<?php echo $edit_mode ? htmlspecialchars($edit_job['location']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Category</label>
                        <input type="text" name="category" class="form-control" value="<?php echo $edit_mode ? htmlspecialchars($edit_job['category']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Number of Openings (Available Posts)</label>
                        <input type="number" name="openings" class="form-control" min="1" value="<?php echo $edit_mode ? htmlspecialchars($edit_job['openings']) : '1'; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">HR Person Name</label>
                        <input type="text" name="hr_name" class="form-control" value="<?php echo $edit_mode ? htmlspecialchars($edit_job['hr_name']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">HR Email Address</label>
                        <input type="email" name="hr_email" class="form-control" value="<?php echo $edit_mode ? htmlspecialchars($edit_job['hr_email']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">HR Mobile Number (10 Digits)</label>
                        <input type="text" name="hr_phone" class="form-control" pattern="[0-9]{10}" maxlength="10" value="<?php echo $edit_mode ? htmlspecialchars($edit_job['hr_phone']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Salary Package</label>
                        <input type="text" name="salary" class="form-control" value="<?php echo $edit_mode ? htmlspecialchars($edit_job['salary']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="3" required><?php echo $edit_mode ? htmlspecialchars($edit_job['description']) : ''; ?></textarea>
                    </div>
                    <?php if ($edit_mode): ?>
                        <button type="submit" name="update_job" class="btn btn-success w-100 fw-bold">Update Job</button>
                        <a href="admin.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                    <?php else: ?>
                        <button type="submit" name="post_job" class="btn btn-primary w-100 fw-bold">Publish Job</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white fw-bold">Manage Posted Jobs</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Title & Location</th>
                                <th>Company & HR Details</th>
                                <th>Posted Date & Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $jobs = $conn->query("SELECT * FROM jobs ORDER BY id DESC");
                            if ($jobs->num_rows > 0) {
                                while ($j = $jobs->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td><strong>" . htmlspecialchars($j['title']) . "</strong><br><small class='text-primary fw-bold'><i class='fa-solid fa-location-dot'></i> " . htmlspecialchars($j['location']) . "</small><br><span class='badge bg-primary mt-1'>Openings: " . htmlspecialchars($j['openings']) . "</span> <span class='badge bg-success'>" . htmlspecialchars($j['salary']) . "</span></td>";
                                    echo "<td><strong>" . htmlspecialchars($j['company']) . "</strong><br><small class='text-muted'><i class='fa-solid fa-user'></i> " . htmlspecialchars($j['hr_name']) . "<br><i class='fa-solid fa-envelope'></i> " . htmlspecialchars($j['hr_email']) . "<br><i class='fa-solid fa-phone'></i> " . htmlspecialchars($j['hr_phone']) . "</small></td>";
                                    echo "<td><small class='fw-bold text-dark'>" . date('d M Y, h:i A', strtotime($j['created_at'])) . "</small></td>";
                                    echo "<td>
                                            <a href='admin.php?edit_job=" . $j['id'] . "' class='btn btn-sm btn-warning text-dark mb-1 fw-bold'>Edit</a>
                                            <a href='admin.php?delete_job=" . $j['id'] . "' class='btn btn-sm btn-danger mb-1 fw-bold' onclick='return confirm(\"Are you sure you want to delete this job vacancy?\")'>Delete</a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center text-muted'>No jobs posted yet.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white fw-bold">Job Applications & Accept/Reject Management</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Applicant</th>
                                <th>Email</th>
                                <th>Job Title</th>
                                <th>Status</th>
                                <th>Resume</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $apps = $conn->query("SELECT applications.*, jobs.title FROM applications JOIN jobs ON applications.job_id = jobs.id ORDER BY applications.id DESC");
                            if ($apps->num_rows > 0) {
                                while ($app = $apps->fetch_assoc()) {
                                    $badge = 'bg-warning text-dark';
                                    if ($app['status'] == 'Accepted') {
                                        $badge = 'bg-success';
                                    }
                                    if ($app['status'] == 'Rejected') {
                                        $badge = 'bg-danger';
                                    }

                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($app['applicant_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($app['email']) . "</td>";
                                    echo "<td>" . htmlspecialchars($app['title']) . "</td>";
                                    echo "<td><span class='badge " . $badge . "'>" . $app['status'] . "</span></td>";
                                    echo "<td><a href='" . htmlspecialchars($app['resume_link']) . "' target='_blank' class='btn btn-sm btn-info text-white fw-bold'>View</a></td>";
                                    echo "<td>
                                            <a href='admin.php?app_id=" . $app['id'] . "&status=Accepted' class='btn btn-sm btn-success mb-1 fw-bold'>Accept</a>
                                            <a href='admin.php?app_id=" . $app['id'] . "&status=Rejected' class='btn btn-sm btn-dark mb-1 fw-bold'>Reject</a>
                                            <a href='admin.php?delete_app=" . $app['id'] . "' class='btn btn-sm btn-danger mb-1 fw-bold' onclick='return confirm(\"Are you sure you want to delete this application?\")'>Delete</a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-muted'>No applications received yet.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>