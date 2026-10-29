<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise Job Portal - Roll No. 54</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1f2937, #111827);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            z-index: 1000;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar .brand {
            font-size: 1.2rem;
            font-weight: 700;
            padding: 20px;
            background: #111827;
            border-bottom: 1px solid #374151;
            text-align: center;
            color: #ffc107;
        }
        .sidebar .nav-link {
            color: #d1d5db;
            padding: 15px 20px;
            font-weight: 500;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: #374151;
            color: #ffc107;
            padding-left: 25px;
        }
        .main-content {
            margin-left: 260px;
            padding: 30px;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }
            .main-content {
                margin-left: 0;
            }
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.04);
        }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column">
    <div class="brand">🚀 Enterprise JobPortal</div>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="index.php" class="nav-link"><span class="me-2">&raquo;</span> Browse Jobs</a>
        </li>
        <li class="nav-item">
            <a href="user_dashboard.php" class="nav-link"><span class="me-2">&raquo;</span> User Status Dashboard</a>
        </li>
        
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <li class="nav-item">
                <a href="admin.php" class="nav-link"><span class="me-2">&raquo;</span> Admin Dashboard</a>
            </li>
        <?php endif; ?>
    </ul>

    <div class="p-3 bg-dark border-top border-secondary">
        <?php if (isset($_SESSION['user_name'])): ?>
            <div class="text-light small mb-2">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></div>
            <a href="logout.php" class="btn btn-danger btn-sm w-100 fw-bold">Logout</a>
        <?php else: ?>
            <div class="d-grid gap-2">
                <a href="login.php" class="btn btn-warning btn-sm fw-bold">Login</a>
                <a href="signup.php" class="btn btn-outline-light btn-sm fw-bold">Sign Up</a>
            </div>
        <?php endif; ?>
    </div>
    <div class="p-3 bg-dark text-muted small border-top border-secondary">
        Roll No: 54<br>L.J. Institute of Computer Applications
    </div>
</div>

<div class="main-content">