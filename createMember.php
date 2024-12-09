<?php
include 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $conn->prepare('INSERT INTO Member (username, password) VALUES (?, ?)');
    $stmt->bind_param('ss', $username, $password);
    if ($stmt->execute()) {
        echo "Member created successfully";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<form method="post" action="">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Create Member</button>
</form>
