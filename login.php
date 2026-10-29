<?php
include 'db.php';
session_start();

$msg = "";
if (isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password']) || $password == $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: index_1.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $msg = '<div class="alert alert-danger py-2 small shadow-sm">Invalid Password!</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger py-2 small shadow-sm">No account found with this email!</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Portal - User Login</title>
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
            background: linear-gradient(135deg, #198754, #157347);
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
            <p class="small text-muted mb-0 text-light opacity-75">User Login Portal</p>
        </div>
        <div class="p-4">
            <?php echo $msg; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-secondary">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-secondary">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-custom w-100 py-2 fw-bold rounded-pill shadow-sm">Login</button>
                <p class="text-center mt-3 small text-muted">Don't have an account? <a href="signup.php" class="text-decoration-none fw-bold">Sign Up</a></p>
                <div class="text-center mt-2">
                    <a href="admin_login.php" class="text-muted small text-decoration-none">Admin Login &rarr;</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>