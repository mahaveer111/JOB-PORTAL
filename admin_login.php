<?php
include 'db.php';
session_start();

$msg = "";
if (isset($_POST['admin_login'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if ((password_verify($password, $user['password']) || $password == $user['password']) && $user['role'] == 'admin') {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = 'admin';

            header("Location: admin.php");
            exit();
        } else {
            $msg = '<div class="alert alert-danger py-2 small shadow-sm">Invalid Admin Credentials!</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger py-2 small shadow-sm">Admin account not found!</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Portal - Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .card-custom {
            background: rgba(255,255,255,0.95);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
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
            background: linear-gradient(135deg, #1f2937, #111827);
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
            <p class="small text-light opacity-75 mb-0">Admin Control Panel Login</p>
        </div>
        <div class="p-4">
            <?php echo $msg; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-secondary">
                        <i class="fa-solid fa-user-shield me-1"></i> Admin Email
                    </label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-secondary">
                        <i class="fa-solid fa-key me-1"></i> Password
                    </label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="admin_login" class="btn btn-custom w-100 py-2 fw-bold rounded-pill shadow-sm">Secure Admin Login</button>
                <div class="text-center mt-3">
                    <a href="login.php" class="text-decoration-none small text-muted fw-bold">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to User Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>