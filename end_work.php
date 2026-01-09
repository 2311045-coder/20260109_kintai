<?php
// ========================
// DB接続
// ========================
$pdo = new PDO(
    'mysql:host=localhost;dbname=kintaidb;charset=utf8',
    'kintaiuser',
    'kintaipass123',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
);

$today = date('Y-m-d');
$nowTime = date('H:i');
$message = '';

// ========================
// 退勤登録処理
// ========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jugyoin_id = $_POST['jugyoin_id'];
    $time = $_POST['time'];
    $end_work = $today . ' ' . $time . ':00';

    // 本日の出勤レコード取得（未退勤）
    $sql = "SELECT * FROM kiroku
            WHERE jugyoin_id = :id
              AND DATE(start_work) = :today
              AND end_work IS NULL";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $jugyoin_id,
        ':today' => $today
    ]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($record) {
        // 退勤時刻更新
        $sql = "UPDATE kiroku
                SET end_work = :end
                WHERE jugyoin_id = :id
                  AND start_work = :start";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':end' => $end_work,
            ':id' => $jugyoin_id,
            ':start' => $record['start_work']
        ]);
        $message = '退勤を記録しました。';
    } else {
        $message = '本日の出勤記録が見つからないか、すでに退勤済みです。';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>出退勤管理システム</title>
  <style>
    /* ===== 共通 ===== */
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

    h1 {
      font-size: 22px;
      margin: 0;
    }

    h2 {
      margin-top: 0;
      font-size: 20px;
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

    button:hover {
      opacity: 0.9;
    }

    input {
      width: 100%;
      padding: 14px;
      font-size: 16px;
      border-radius: 8px;
      border: 1px solid #d1d5db;
      margin-bottom: 20px;
    }

    .nav a {
        color: #fff;
        text-decoration: none;
        font-weight: 600;
        margin-left: 16px;
    }

    .nav a:hover {
        text-decoration: underline;
    }

    /* ===== 退勤（青） ===== */
    body.clock-out header { background: #3b82f6; }
    body.clock-out button { background: #1d4ed8; }

    .message {
      text-align: center;
      margin-bottom: 20px;
      color: #1e3a8a;
      font-weight: bold;
    }
  </style>
</head>

<body class="clock-out">

<header>
  <h1>退勤打刻</h1>
  <div class="nav">
    <span><?= date('Y/m/d') ?></span>
    <a href="all_work.php">一覧</a>
    <a href="start_work.php">出勤</a>
  </div>
</header>

<div class="container">
  <div class="card">
    <h2>退勤時刻を入力</h2>

    <?php if ($message): ?>
      <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post">
      <input
        type="number"
        name="jugyoin_id"
        placeholder="従業員番号"
        required
      >

      <input
        type="time"
        name="time"
        value="<?= $nowTime ?>"
        required
      >

      <button type="submit">退勤する</button>
    </form>
  </div>
</div>

</body>
</html>
