<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = new mysqli('localhost', 'root', '', 'db_metrologi');

$tables = ['user', 'petugas', 'pemohon', 'alat_uttp', 'pengujian', 'skhp', 'surat_tugas'];

foreach ($tables as $t) {
    try {
        $res = $db->query("SELECT * FROM $t");
        echo str_pad($t, 15) . ": " . $res->num_rows . " rows\n";
    } catch (\Exception $e) {
        echo str_pad($t, 15) . ": ERROR (" . $e->getMessage() . ")\n";
    }
}
