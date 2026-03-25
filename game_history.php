<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Game History</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
    <header>
        <div class="global-header">
            <a href="dashboard.php"><img src="" alt="Logo" style="float:left;" /></a>
            <a href="dashboard.php">Dashboard</a>
            <a href="game_history.php">Game History</a>
            <a href="logout.php">Sign Out</a>
            <a href="user_info.php">Profile</a>
            <a href="user_info.php"><img src="" alt="Avatar" style="float:right" /></a>
        </div>
    </header>

    <div class="history-container">
        <h1>Past Games</h1>

        <table>
            <thead>
                <tr>
                    <th>Game</th>
                    <th>Opponent</th>
                    <th>Result</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Chess</td>
                    <td>The_Goat99</td>
                    <td><span class="badge badge-win">Win</span></td>
                    <td>2026-01-15</td>
                </tr>
                <tr>
                    <td>Tic-Tac-Toe</td>
                    <td>The_Goat99</td>
                    <td><span class="badge badge-loss">Loss</span></td>
                    <td>2026-01-16</td>
                </tr>
                <tr>
                    <td>Tic-Tac-Toe</td>
                    <td>The_Goat99</td>
                    <td><span class="badge badge-loss">Loss</span></td>
                    <td>2026-01-17</td>
                </tr>
            </tbody>
        </table>

        <div class="pagination">
            <button type="button">← Previous</button>
            <button type="button">Next →</button>
        </div>
    </div>
</body>
</html>