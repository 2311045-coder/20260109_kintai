<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $date = date('Y-m-d');
    $time = date('H:i:s');

    $sql = "INSERT INTO attendance (employee_name, work_date, clock_in)
            VALUES (:name, :work_date, :clock_in)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':work_date' => $date,
        ':clock_in' => $time
    ]);

    $message = "出勤を記録しました。";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>出勤入力</title>
</head>
<body>
<h2>出勤時刻入力</h2>

<?php if (!empty($message)) echo "<p>$message</p>"; ?>

<form method="post">
    従業員名：<input type="text" name="name" required>
    <button type="submit">出勤</button>
</form>

<p><a href="list.php">一覧画面へ</a></p>
</body>
</html>
