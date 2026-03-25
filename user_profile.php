<!-- Assignment 2: Page created by Jorrell Salazar (jgs015) -->

<?php
session_start();
require_once("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$errors = [];
$success = "";

// Fetch current user info
$stmt = $db->prepare("SELECT email, screename, dob, avatar FROM Users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Delete account
    if (isset($_POST["delete_account"])) {
        $stmt = $db->prepare("DELETE FROM Users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }

    // Update profile
    if (isset($_POST["save_profile"])) {
        $nickname = trim($_POST["nickname"]);
        $dob      = trim($_POST["dob"]);
        $email    = trim($_POST["email"]);
        $password = trim($_POST["userpw"]);
        $repw     = trim($_POST["userrepw"]);

        if (!preg_match("/^[a-zA-Z0-9_]+$/", $nickname)) {
            $errors[] = "Nickname may only contain letters, numbers, and underscores.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address.";
        }
        if (!empty($password)) {
            if (strlen($password) < 6) {
                $errors[] = "Password must be at least 6 characters.";
            } elseif ($password !== $repw) {
                $errors[] = "Passwords do not match.";
            }
        }

        if (empty($errors)) {
            if (!empty($password)) {
                $stmt = $db->prepare("UPDATE Users SET screename=?, dob=?, email=?, password=? WHERE user_id=?");
                $stmt->execute([$nickname, $dob, $email, $password, $user_id]);
            } else {
                $stmt = $db->prepare("UPDATE Users SET screename=?, dob=?, email=? WHERE user_id=?");
                $stmt->execute([$nickname, $dob, $email, $user_id]);
            }
            $_SESSION['screenname'] = $nickname;
            $success = "Profile updated successfully.";

            // Re-fetch updated info
            $stmt = $db->prepare("SELECT email, screename, dob, avatar FROM Users WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
        }
    }
}
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile</title>
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

    <div class="profile-grid">

        <!-- Left: current info snapshot -->
        <div class="card">
            <div class="profile-avatar">
                <?php echo strtoupper(substr($user['screename'] ?? 'U', 0, 1)); ?>
            </div>
            <div class="section-title">Your profile</div>
            <div class="info-row">
                <span class="info-label">Nickname</span>
                <span class="info-value"><?php echo htmlspecialchars($user['screename'] ?? '—'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value"><?php echo htmlspecialchars($user['email'] ?? '—'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Date of birth</span>
                <span class="info-value"><?php echo htmlspecialchars($user['dob'] ?? '—'); ?></span>
            </div>
        </div>

        <!-- Right: edit form -->
        <div class="card">
            <div class="section-title">Edit profile</div>

            <?php if (!empty($success)): ?>
                <div class="alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert-error">
                    <?php foreach ($errors as $e): ?>
                        <div><?php echo htmlspecialchars($e); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form id="user-basic-info" action="user_info.php" method="post" enctype="multipart/form-data">

                <div class="field">
                    <label for="nickname">Nickname</label>
                    <input type="text" id="nickname" name="nickname"
                        value="<?php echo htmlspecialchars($user['screename'] ?? ''); ?>" />
                </div>

                <div class="field">
                    <label for="dob">Date of birth</label>
                    <input type="date" id="dob" name="dob"
                        value="<?php echo htmlspecialchars($user['dob'] ?? ''); ?>" />
                </div>

                <div class="field">
                    <label for="userpfp">Profile picture</label>
                    <input type="file" id="userpfp" name="userpfp" accept="image/png, image/jpeg, image/jpg" />
                </div>

                <div class="field">
                    <label for="useremail">Email</label>
                    <input type="email" id="useremail" name="email"
                        value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" />
                </div>

                <div class="field">
                    <label for="userpw">New password <span style="color:#888780; font-weight:400;">(leave blank to keep current)</span></label>
                    <input type="password" id="userpw" name="userpw" />
                </div>

                <div class="field">
                    <label for="userrepw">Confirm new password</label>
                    <input type="password" id="userrepw" name="userrepw" />
                </div>

                <div class="form-buttons">
                    <input type="submit" name="save_profile" value="Save changes" />
                    <input type="reset" value="Cancel" />
                </div>
            </form>
        </div>
    </div>

    <!-- Delete account -->
    <div class="danger-zone">
        <div class="danger-card">
            <div>
                <strong style="font-size:14px; color:#a32d2d;">Delete account</strong>
                <p>This will permanently remove your account and all associated data. This cannot be undone.</p>
            </div>
            <form action="user_info.php" method="post"
                onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.');">
                <button type="submit" name="delete_account" class="btn-danger">Delete account</button>
            </form>
        </div>
    </div>

    <footer>
        <a href="dashboard.php">Return to Dashboard</a>
    </footer>

    <script src="js/eventHandlers.js"></script>
    <script src="js/eventRegisterUserinfo.js"></script>
</body>
</html>