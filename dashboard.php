<!-- Assignment 2: Page created by Jorrell Salazar (jgs015) -->

<?php
session_start();
require_once("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$query = "SELECT Topics.title, Topics.created_dt, COUNT(Notes.note_id) AS numNotes 
            FROM Notes 
            RIGHT JOIN Topics ON Notes.topic_id = Topics.topic_id 
            LEFT JOIN Access ON (Topics.topic_id = Access.topic_id)
            WHERE Access.user_id = ?
            GROUP BY Topics.topic_id";

$result = $db->prepare($query);
$result->execute([$_SESSION['user_id']]);
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
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

    <div id="welcome-banner">
        <div class="card">
            <a href="user_info.php"><img src="" alt="Avatar" style="float:left; margin-right: 1rem;"></a>
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['screenname'] ?? 'Player'); ?>!</h1>
            <p style="color: #5f5e5a; font-size: 15px; margin-top: 0.5rem;">Select a game below to play.</p>
        </div>
    </div>

    <footer>
        <a href="index.php">Return to Home</a>
    </footer>
</body>
</html>