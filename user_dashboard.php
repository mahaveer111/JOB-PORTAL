<?php
include 'db.php';
include 'header.php';

$email_query = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
$applications = null;

if ($email_query) {
    $applications = $conn->query("SELECT applications.*, jobs.title, jobs.company FROM applications JOIN jobs ON applications.job_id = jobs.id WHERE applications.email = '$email_query' ORDER BY applications.id DESC");
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>User Application Status Dashboard</h2>
    <a href="index.php" class="btn btn-outline-secondary">&larr; Back to Browse Jobs</a>
</div>

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="POST" class="input-group">
            <input type="email" name="email" class="form-control" placeholder="Enter your registered email address to check status..." value="<?php echo htmlspecialchars($email_query); ?>" required>
            <button class="btn btn-primary px-4" type="submit">Check Status</button>
        </form>
    </div>
</div>

<?php if ($email_query): ?>
    <h4 class="mb-3">Application Status for: <?php echo htmlspecialchars($email_query); ?></h4>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Company</th>
                            <th>Applied Date</th>
                            <th>Application Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($applications && $applications->num_rows > 0): ?>
                            <?php while($app = $applications->fetch_assoc()): ?>
                                <?php
                                $badge = 'bg-warning text-dark';
                                if ($app['status'] == 'Accepted') {
                                    $badge = 'bg-success';
                                }
                                if ($app['status'] == 'Rejected') {
                                    $badge = 'bg-danger';
                                }
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($app['title']); ?></td>
                                    <td><?php echo htmlspecialchars($app['company']); ?></td>
                                    <td><?php echo $app['applied_at']; ?></td>
                                    <td><span class="badge <?php echo $badge; ?> fs-6 px-3 py-2"><?php echo $app['status']; ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">No applications found for this email address.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>