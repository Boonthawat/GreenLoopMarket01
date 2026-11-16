<?php
require_once __DIR__ . '/../init.php';

$contentsQuery = $db->query('SELECT * FROM contents ORDER BY start_at DESC');
$newContents = $contentsQuery->fetch_all(MYSQLI_ASSOC);
$FN = $newContents ? array_shift($newContents):null;
$sumRecycles = $db->query("SELECT COUNT(*) AS n, COALESCE(SUM(weight_kg), 0) AS kg, COALESCE(SUM(co2_kg), 0) AS co2 FROM recycles WHERE status = 'done'")->fetch_assoc();
$MC = $db->query("SELECT COUNT(*) FROM users WHERE status = 'active' AND role = 'user'")->fetch_row()[0];

?>