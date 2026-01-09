<?php
// =======================
// DB接続
// =======================
$pdo = new PDO(
    'mysql:host=localhost;dbname=kintaidb;charset=utf8',
    'kintaiuser',
    'kintaipass123',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
);

// ========================
// 記録取得
// ========================
$sql = "SELECT jugyoin_id, start_work, end_work
        FROM kiroku
        ORDER BY start_work DESC";
$stmt = $pdo->query($sql);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
      max-width: 1000px;
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

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #e5e7eb;
      text-align: center;
    }

    th {
      background: #fff7ed;
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


    /* ===== 一覧（オレンジ） ===== */
    body.record header { background: #f59e0b; }
  </style>
</head>

<body class="record">

<header>
  <h1>勤務記録一覧</h1>
  <div class="nav">
    <span><?= date('Y/m/d') ?></span>
    <a href="start_work.php">出勤</a>
    <a href="end_work.php">退勤</a>
</header>

<div class="container">
  <div class="card">
    <h2>全記録</h2>

    <table>
      <thead>
        <tr>
          <th>従業員番号</th>
          <th>日付</th>
          <th>出勤時刻</th>
          <th>退勤時刻</th>
          <th>勤務時間</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($records as $r): ?>
          <?php
            $start = new DateTime($r['start_work']);
            $end = $r['end_work'] ? new DateTime($r['end_work']) : null;

            if ($end) {
                $diff = $start->diff($end);
                $workTime = sprintf('%02d:%02d', $diff->h + $diff->d * 24, $diff->i);
            } else {
                $workTime = '—';
            }
          ?>
          <tr>
            <td><?= htmlspecialchars($r['jugyoin_id']) ?></td>
            <td><?= $start->format('Y/m/d') ?></td>
            <td><?= $start->format('H:i') ?></td>
            <td><?= $end ? $end->format('H:i') : '—' ?></td>
            <td><?= $workTime ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </div>
</div>

</body>
</html>
