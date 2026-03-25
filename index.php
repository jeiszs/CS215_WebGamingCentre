<?php
require_once("db.php");

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$errors = array();
$email = $nickname = $password = $dob = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = test_input($_POST["email"]);
    $nickname = test_input($_POST["nickname"]);
    $password = test_input($_POST["password"]);
    $dob = test_input($_POST["dob"]);

    $emailRegex = "/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/";
    $nickRegex = "/^[a-zA-Z0-9_]+$/";
    $passRegex = "/^.{6,}$/";

    if (!preg_match($emailRegex, $email)) $errors["email"] = "Invalid Email";
    if (!preg_match($nickRegex, $nickname)) $errors["nickname"] = "Use letters, numbers, and underscores only";
    if (!preg_match($passRegex, $password)) $errors["password"] = "Minimum of 6 characters";

    if (empty($errors)) {
        try {
            $result = $db->prepare("INSERT INTO Users (email, screename, password, avatar, dob) VALUES (?, ?, ?, 'notsureyet', ?)");
            $result->execute([$email, $nickname, $password, $dob]);
            header("Location: login.php");
            exit();
        } catch (PDOException $e) {
            $errors["db"] = "Registration failed: " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Account</title>
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
            <h1>Create Account</h1>

            <?php if (!empty($errors["db"])): ?>
                <p class="error-text"><?php echo $errors["db"]; ?></p>
            <?php endif; ?>

            <form id="signup-form" action="index.php" method="post">

                <div class="field">
                    <label for="signup-email">Email</label>
                    <input type="text" name="email" id="signup-email"
                        value="<?php echo htmlspecialchars($email); ?>"
                        class="<?php echo isset($errors['email']) ? 'input-error' : ''; ?>" />
                    <div id="signup-email-error" class="error-text <?php echo isset($errors['email']) ? '' : 'hidden'; ?>">
                        <?php echo isset($errors['email']) ? $errors['email'] : 'Email is required'; ?>
                    </div>
                </div>

                <div class="field">
                    <label for="signup-nickname">Nickname</label>
                    <input type="text" name="nickname" id="signup-nickname"
                        value="<?php echo htmlspecialchars($nickname); ?>"
                        class="<?php echo isset($errors['nickname']) ? 'input-error' : ''; ?>" />
                    <div id="signup-nickname-error" class="error-text <?php echo isset($errors['nickname']) ? '' : 'hidden'; ?>">
                        <?php echo isset($errors['nickname']) ? $errors['nickname'] : 'Please use letters, numbers and underscores only.'; ?>
                    </div>
                </div>

                <div class="field">
                    <label for="signup-password">Password</label>
                    <input type="password" name="password" id="signup-password"
                        class="<?php echo isset($errors['password']) ? 'input-error' : ''; ?>" />
                    <div id="signup-password-error" class="error-text <?php echo isset($errors['password']) ? '' : 'hidden'; ?>">
                        <?php echo isset($errors['password']) ? $errors['password'] : 'Password must be 6 characters and include a number or symbol.'; ?>
                    </div>
                </div>

                <div class="field">
                    <label for="signup-confirm">Confirm Password</label>
                    <input type="password" name="confirm" id="signup-confirm" />
                    <div id="confirm-error" class="error-text hidden">Passwords do not match</div>
                </div>

                <div class="form-buttons">
                    <input type="submit" value="Register" />
                    <input type="reset" value="Clear" />
                </div>
            </form>

            <p style="margin-top: 1.25rem; font-size: 13px; color: #5f5e5a;">
                Already have an account? <a href="login.php" style="color: #533ab7;">Log in here</a>
            </p>
        </div>
    </div>

    <script src="js/eventHandlers.js"></script>
    <script src="js/eventRegisterSignup.js"></script>
</body>
</html>
