<?php
include 'db.php';
include 'header.php';

$msg = "";
if (isset($_POST['apply_job'])) {
    $job_id = intval($_POST['job_id']);
    $name = $conn->real_escape_string($_POST['applicant_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $age = intval($_POST['age']);
    $education = $conn->real_escape_string($_POST['education']);
    $resume = $conn->real_escape_string($_POST['resume_link']);

    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $msg = '<div class="alert alert-danger alert-dismissible fade show">Please enter a valid 10-digit mobile number! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = '<div class="alert alert-danger alert-dismissible fade show">Please enter a valid email address! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    } else {
        $check = $conn->query("SELECT * FROM applications WHERE job_id = $job_id AND email = '$email'");
        if ($check->num_rows > 0) {
            $msg = '<div class="alert alert-danger alert-dismissible fade show">You have already applied for this job using this email address! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        } else {
            $sql = "INSERT INTO applications (job_id, applicant_name, email, phone, gender, age, education, resume_link, status) VALUES ($job_id, '$name', '$email', '$phone', '$gender', $age, '$education', '$resume', 'Pending')";
            if ($conn->query($sql) === TRUE) {
                $msg = '<div class="alert alert-success alert-dismissible fade show">Application submitted successfully! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
            } else {
                $msg = '<div class="alert alert-danger alert-dismissible fade show">Error in submitting application. Please try again. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
            }
        }
    }
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where = $search ? "WHERE title LIKE '%$search%' OR category LIKE '%$search%' OR company LIKE '%$search%' OR location LIKE '%$search%'" : "";

$count_result = $conn->query("SELECT COUNT(*) as total FROM jobs $where");
$count_row = $count_result->fetch_assoc();
$total_jobs = $count_row['total'];

$result = $conn->query("SELECT * FROM jobs $where ORDER BY id DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Available Job Openings</h2>
        <p class="text-muted mb-0">Total <strong><?php echo $total_jobs; ?></strong> job vacancies available right now.</p>
    </div>
    <a href="user_dashboard.php" class="btn btn-outline-primary fw-bold">Check Application Status &rarr;</a>
</div>

<?php echo $msg; ?>

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="GET" class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by job title, company, location, or category..." value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-primary px-4 fw-bold" type="submit">Search</button>
            <?php if ($search): ?>
                <a href="index.php" class="btn btn-outline-secondary">Reset</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="row">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-start border-primary border-4 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start">
                            <h4 class="card-title text-primary fw-bold"><?php echo htmlspecialchars($row['title']); ?></h4>
                            <span class="badge bg-success fs-6"><?php echo htmlspecialchars($row['salary']); ?></span>
                        </div>
                        
                        <h6 class="card-subtitle mb-2 text-muted mt-1">
                            <i class="fa-solid fa-building"></i> <strong><?php echo htmlspecialchars($row['company']); ?></strong> | 
                            <span class="text-danger fw-bold"><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($row['location']); ?></span>
                        </h6>

                        <div class="bg-light p-2 rounded mb-3 small text-muted">
                            <div><i class="fa-solid fa-briefcase"></i> Openings: <strong class="text-dark"><?php echo htmlspecialchars($row['openings']); ?> Posts</strong> | Category: <span class="badge bg-secondary"><?php echo htmlspecialchars($row['category']); ?></span></div>
                            <div><i class="fa-solid fa-user-tie"></i> HR: <strong><?php echo htmlspecialchars($row['hr_name']); ?></strong> (<i class="fa-solid fa-phone"></i> <?php echo htmlspecialchars($row['hr_phone']); ?> | <i class="fa-solid fa-envelope"></i> <?php echo htmlspecialchars($row['hr_email']); ?>)</div>
                            <div><i class="fa-solid fa-calendar-days"></i> Posted On: <strong><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></strong></div>
                        </div>

                        <p class="card-text mt-1 flex-grow-1 text-secondary"><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                        
                        <button class="btn btn-success mt-3 align-self-start px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#applyModal<?php echo $row['id']; ?>">Apply Now</button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="applyModal<?php echo $row['id']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title fw-bold">Apply for <?php echo htmlspecialchars($row['title']); ?></h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="job_id" value="<?php echo $row['id']; ?>">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Full Name</label>
                                    <input type="text" name="applicant_name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email Address</label>
                                    <input type="email" name="email" class="form-control" placeholder="e.g. user@gmail.com" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Mobile Number (10 Digits)</label>
                                    <input type="text" name="phone" class="form-control" pattern="[0-9]{10}" maxlength="10" placeholder="e.g. 9876543210" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Gender</label>
                                        <select name="gender" class="form-control" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Age</label>
                                        <input type="number" name="age" class="form-control" min="18" max="70" placeholder="e.g. 22" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Education Qualification</label>
                                    <select name="education" class="form-control" required>
                                        <option value="">Select Education Qualification</option>
                                        <option value="MCA">MCA (Master of Computer Applications)</option>
                                        <option value="BCA">BCA (Bachelor of Computer Applications)</option>
                                        <option value="B.Tech / M.Tech">B.Tech / M.Tech</option>
                                        <option value="B.Sc / M.Sc IT">B.Sc / M.Sc IT</option>
                                        <option value="MBA">MBA</option>
                                        <option value="B.Com / M.Com">B.Com / M.Com</option>
                                        <option value="Other">Other Degree / Graduate</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Resume Link (Google Drive URL)</label>
                                    <input type="url" name="resume_link" class="form-control" placeholder="https://..." required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="apply_job" class="btn btn-success w-100 fw-bold">Submit Application</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="col-12"><div class="alert alert-info">No job vacancies found matching your search criteria.</div></div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>