<?php
require 'db.php';

$sql = "SELECT jugyoin_id, start_work, end_work
        FROM kiroku
        ORDER BY start_work DESC";
$records = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<title>出退勤一覧</title>
<body>
<h2>出退勤一覧</h2>

<table border="1">
<tr>
    <th>従業員番号</th>
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

<a href="start_work.php">出勤</a> |
<a href="end_work.php">退勤</a>
</body>
</html>
