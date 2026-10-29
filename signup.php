<?php
include 'db.php';
$msg = "";

if (isset($_POST['signup'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user'; 

    $check = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($check->num_rows > 0) {
        $msg = '<div class="alert alert-danger py-2 small shadow-sm">Email already registered!</div>';
    } else {
        $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
        if ($conn->query($sql) === TRUE) {
            $msg = '<div class="alert alert-success py-2 small shadow-sm">Registration successful! <a href="login.php">Login here</a></div>';
        } else {
            $msg = '<div class="alert alert-danger py-2 small shadow-sm">Error in registration.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Portal - User Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .card-custom {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
        }
        .card-header-custom {
            background: linear-gradient(135deg, #1f2937, #111827);
            color: #fff;
            padding: 25px;
            text-align: center;
            border-bottom: 4px solid #ffc107;
        }
        .btn-custom {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: white;
        }
        .btn-custom:hover {
            opacity: 0.9;
            color: white;
        }
    </style>
</head>
<body>
    <div class="card-custom">
        <div class="card-header-custom">
            <h3 class="fw-bold mb-0 text-warning">
                <i class="fa-solid fa-briefcase me-2"></i>Job Portal
            </h3>
            <p class="small text-muted mb-0 text-light opacity-75">Create Your User Account</p>
        </div>
        <div class="p-4">
            <?php echo $msg; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-secondary">Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-secondary">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-secondary">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="signup" class="btn btn-custom w-100 py-2 fw-bold rounded-pill shadow-sm">Sign Up</button>
                <p class="text-center mt-3 small text-muted">Already have an account? <a href="login.php" class="text-decoration-none fw-bold">Login</a></p>
            </form>
        </div>
    </div>
</body>
</html>