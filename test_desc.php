<?php
$db = new mysqli('localhost', 'root', '', 'db_metrologi');
$res = $db->query('DESCRIBE surat_tugas');
while ($row = $res->fetch_assoc()) { echo $row['Field'] . ' - ' . $row['Type'] . "\n"; }
echo "\nDESCRIBE alat_uttp:\n";
$res = $db->query('DESCRIBE alat_uttp');
while ($row = $res->fetch_assoc()) { echo $row['Field'] . ' - ' . $row['Type'] . "\n"; }
