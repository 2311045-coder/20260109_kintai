<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jugyoin_id = $_POST['jugyoin_id'];
    $today = date('Y-m-d');
    $now = date('Y-m-d H:i:s');

    // 同日の出勤があるか確認
    $sql = "SELECT COUNT(*) FROM kiroku
            WHERE jugyoin_id = :id
              AND DATE(start_work) = :today";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $jugyoin_id,
        ':today' => $today
    ]);

    if ($stmt->fetchColumn() == 0) {
        $sql = "INSERT INTO kiroku (jugyoin_id, start_work)
                VALUES (:id, :start)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $jugyoin_id,
            ':start' => $now
        ]);
        $message = "出勤を記録しました。";
    } else {
        $message = "本日はすでに出勤記録があります。";
    }
}
?>

<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<title>出勤入力</title>
<body>
<h2>出勤入力</h2>

<p><?= $message ?? '' ?></p>

<form method="post">
    従業員番号：
    <input type="number" name="jugyoin_id" required>
    <button type="submit">出勤</button>
</form>

<a href="all_work.php">一覧へ</a>
</body>
</html>
