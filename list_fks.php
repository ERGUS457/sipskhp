<?php
$db = new mysqli('localhost', 'root', '', 'db_metrologi');
$res = $db->query("
    SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE REFERENCED_TABLE_SCHEMA = 'db_metrologi'
");
while ($row = $res->fetch_assoc()) {
    echo "FK: {$row['TABLE_NAME']}.{$row['COLUMN_NAME']} -> {$row['REFERENCED_TABLE_NAME']}.{$row['REFERENCED_COLUMN_NAME']} (Constraint: {$row['CONSTRAINT_NAME']})\n";
}
