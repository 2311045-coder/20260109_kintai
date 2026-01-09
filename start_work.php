<?php
date_default_timezone_set('Asia/Tokyo');

// DB接続
$pdo = new PDO(
    'mysql:host=localhost;dbname=kintaidb;charset=utf8',
    'kintaiuser',
    'kintaipass123',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

// 従業員一覧取得
$stmt = $pdo->query("SELECT jugyoin_id, name FROM jugyoin ORDER BY jugyoin_id");
$jugyoinList = $stmt->fetchAll(PDO::FETCH_ASSOC);

$today = date('Y-m-d');
$nowTime = date('H:i');
$message = '';

// 出勤登録処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jugyoin_id = $_POST['jugyoin_id'];
    $time = $_POST['time'];
    $start_work = $today . ' ' . $time . ':00';

    // 同日二重出勤防止
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
            ':start' => $start_work
        ]);
        $message = '出勤を記録しました。';
    } else {
        $message = '本日はすでに出勤済みです。';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>出退勤管理システム</title>
<style>
body {
  margin: 0;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  background: #f3f4f6;
}
header {
  color: #fff;
  padding: 16px 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.container {
  max-width: 800px;
  margin: 40px auto;
  padding: 0 16px;
}
.card {
  background: #fff;
  border-radius: 12px;
  padding: 32px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}
h1 { font-size: 22px; margin: 0; }
h2 { margin-top: 0; font-size: 20px; }

input, select {
  width: 100%;
  padding: 14px;
  font-size: 16px;
  border-radius: 8px;
  border: 1px solid #d1d5db;
  margin-bottom: 20px;
}

button {
  width: 100%;
  padding: 16px;
  font-size: 18px;
  border: none;
  border-radius: 10px;
  color: #fff;
  cursor: pointer;
}
button:hover { opacity: 0.9; }

body.clock-in header { background: #22c55e; }
body.clock-in button { background: #16a34a; }

.nav a {
  color: #fff;
  text-decoration: none;
  margin-left: 16px;
  font-weight: 600;
}
.nav a:hover { text-decoration: underline; }

.message {
  text-align: center;
  margin-bottom: 20px;
  color: #065f46;
  font-weight: bold;
}
</style>
</head>

<body class="clock-in">
<header>
  <h1>出勤打刻</h1>
  <div class="nav">
    <span><?= date('Y/m/d') ?></span>
    <a href="record_list.php">一覧</a>
    <a href="clock_out.php">退勤</a>
  </div>
</header>

<div class="container">
  <div class="card">
    <h2>出勤時刻を入力</h2>

    <?php if ($message): ?>
      <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post">
  <select name="jugyoin_id" required>
    <option value="">従業員を選択</option>
    <?php foreach ($jugyoinList as $j): ?>
      <option value="<?= $j['jugyoin_id'] ?>">
        <?= htmlspecialchars($j['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <input type="time" name="time" value="<?= $nowTime ?>" required>

  <button type="submit">出勤する</button>
</form>

  </div>
</div>
</body>
</html>
