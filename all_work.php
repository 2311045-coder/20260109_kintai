<?php
require 'db.php';

$sql = "SELECT jugyoin_id, start_work, end_work
        FROM kiroku
        ORDER BY start_work DESC";
$stmt = $pdo->query($sql);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>出退勤一覧</title>
<style>
table {
    border-collapse: collapse;
}
th, td {
    border: 1px solid #666;
    padding: 6px 10px;
}
</style>
</head>
<body>
<h2>出退勤記録一覧</h2>

<table>
<tr>
    <th>従業員ID</th>
    <th>出勤日時</th>
    <th>退勤日時</th>
</tr>

<?php foreach ($records as $r): ?>
<tr>
    <td><?= htmlspecialchars($r['jugyoin_id']) ?></td>
    <td><?= $r['start_work'] ?></td>
    <td><?= $r['end_work'] ?? '-' ?></td>
</tr>
<?php endforeach; ?>

</table>

<p>
    <a href="start_work.php">出勤入力</a> |
    <a href="end_work.php">退勤入力</a>
</p>
</body>
</html>
