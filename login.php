<?php
session_start();
require_once("db.php");
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = $db->prepare("SELECT user_id, screenname FROM Users WHERE email = ? AND password = ?");
    $result->execute([$_POST['lemail'], $_POST['lpassword']]);
    $user = $result->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['screenname'] = $user['screenname'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
    <header>
        <div class="global-header">
            <a href="dashboard.php"><img src="" alt="Logo" style="float:left;"></a>
            <a href="dashboard.php">Dashboard</a>
            <a href="game_history.php">Game History</a>
            <a href="logout.php">Sign Out</a>
            <a href="user_info.php">Profile</a>
            <a href="user_info.php"><img src="" alt="Avatar" style="float:right"></a>
        </div>
    </header>

    <div id="main-container">
        <div class="card">
            <h1>Login</h1>

            <?php if (!empty($error)): ?>
                <p class="error-text" style="margin-bottom: 1rem;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form id="login-form" action="login.php" method="post">

                <div class="field">
                    <label for="login-email">Email</label>
                    <input type="text" name="lemail" id="login-email" />
                    <div id="email-error" class="error-text hidden">Please enter a valid email address.</div>
                </div>

                <div class="field">
                    <label for="login-password">Password</label>
                    <input type="password" name="lpassword" id="login-password" />
                    <div id="password-error" class="error-text hidden">Password must be at least 6 characters.</div>
                </div>

                <div class="form-buttons">
                    <input type="submit" value="Login" />
                </div>
            </form>

            <p style="margin-top: 1.25rem; font-size: 13px; color: #5f5e5a;">
                Don't have an account? <a href="index.php" style="color: #533ab7;">Create one</a>
            </p>
        </div>
    </div>

    <script src="js/eventHandlers.js"></script>
    <script src="js/eventRegisterLogin.js"></script>
</body>
</html>