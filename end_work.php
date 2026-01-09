<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jugyoin_id = $_POST['jugyoin_id'];
    $today = date('Y-m-d');
    $now = date('Y-m-d H:i:s');

    $sql = "UPDATE kiroku
            SET end_work = :end
            WHERE jugyoin_id = :id
              AND DATE(start_work) = :today
              AND end_work IS NULL";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':end' => $now,
        ':id' => $jugyoin_id,
        ':today' => $today
    ]);

    if ($stmt->rowCount() > 0) {
        $message = "退勤を記録しました。";
    } else {
        $message = "本日の出勤記録が見つかりません。";
    }
}
?>

<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<title>退勤入力</title>
<body>
<h2>退勤入力</h2>

<p><?= $message ?? '' ?></p>

<form method="post">
    従業員番号：
    <input type="number" name="jugyoin_id" required>
    <button type="submit">退勤</button>
</form>

<a href="list.php">一覧へ</a>
</body>
</html>
