<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jugyoin_id = $_POST['jugyoin_id'];
    $today = date('Y-m-d');
    $now = date('Y-m-d H:i:s');

    $sql = "UPDATE kiroku
            SET end_work = :end_work
            WHERE jugyoin_id = :jugyoin_id
              AND DATE(start_work) = :today";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':end_work' => $now,
        ':jugyoin_id' => $jugyoin_id,
        ':today' => $today
    ]);

    $message = "退勤を記録しました。";
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>退勤入力</title>
</head>
<body>
<h2>退勤時刻入力</h2>

<?php if (!empty($message)) echo "<p>$message</p>"; ?>

<form method="post">
    従業員ID：
    <input type="number" name="jugyoin_id" required>
    <button type="submit">退勤</button>
</form>

<p><a href="list.php">一覧へ</a></p>
</body>
</html>
