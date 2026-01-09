<?php
$pdo = new PDO(
    'mysql:host=localhost;dbname=kintaidb;charset=utf8mb4',
    'kintaiuser',
    'kintaipass123',
    [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]
);

// 勤務記録＋氏名（存在しない場合は匿名）
$sql = "
SELECT
  k.jugyoin_id,
  COALESCE(j.name, '匿名') AS name,
  k.start_work,
  k.end_work
FROM kiroku k
LEFT JOIN jugyoin j
  ON k.jugyoin_id = j.jugyoin_id
ORDER BY k.start_work DESC
";

$stmt = $pdo->query($sql);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>勤務記録一覧</title>
  <style>
    body {
      margin: 0;
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background: #f3f4f6;
    }

    header {
      background: #f59e0b;
      color: #fff;
      padding: 16px 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .container {
      max-width: 900px;
      margin: 40px auto;
      padding: 0 16px;
    }

    .card {
      background: #fff;
      border-radius: 12px;
      padding: 32px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #e5e7eb;
      text-align: center;
    }

    th {
      background: #fff7ed;
    }

    a {
      color: #ea580c;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>

<body class="record">

<header>
  <h1>勤務記録一覧</h1>
  <a href="start_work.php">出勤</a>
    <a href="end_work.php">退勤</a>
</header>

<div class="container">
  <div class="card">
    <table>
      <tr>
        <th>従業員番号</th>
        <th>氏名</th>
        <th>出勤時刻</th>
        <th>退勤時刻</th>
      </tr>

      <?php foreach ($records as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['jugyoin_id']) ?></td>
          <td><?= htmlspecialchars($r['name']) ?></td>
          <td><?= htmlspecialchars($r['start_work']) ?></td>
          <td><?= htmlspecialchars($r['end_work'] ?? '-') ?></td>
        </tr>
      <?php endforeach; ?>

    </table>
  </div>
</div>

</body>
</html>
