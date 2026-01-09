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

// 今日の日付・現在時刻
$today = date('Y-m-d');
$nowTime = date('H:i');
$message = '';

// ========================
// 出勤登録処理
// ========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jugyoin_id = $_POST['jugyoin_id'];
    $time = $_POST['time'];

    $start_work = $today . ' ' . $time . ':00';

    // 同日出勤チェック
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
    h1 { font-size: 22px; margin: 0; }
    h2 { margin-top: 0; font-size: 20px; }
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

    input {
      width: 100%;
      padding: 14px;
      font-size: 16px;
      border-radius: 8px;
      border: 1px solid #d1d5db;
      margin-bottom: 20px;
    }

    body.clock-in header { background: #22c55e; }
    body.clock-in button { background: #16a34a; }

    .message {
      text-align: center;
      margin-bottom: 20px;
      color: #065f46;
      font-weight: bold;
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
  </style>
</head>

<body class="clock-in">

<header>
  <h1>出勤打刻</h1>
  <div class="nav">
    <span><?= date('Y/m/d') ?></span>
    <a href="all_work.php">一覧</a>
    <a href="end_work.php">退勤</a>
  </div>
</header>

<div class="container">
  <div class="card">
    <h2>出勤時刻を入力</h2>

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

      <button type="submit">出勤する</button>
    </form>
  </div>
</div>

</body>
</html>
