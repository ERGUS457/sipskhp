<?php
$file = 'f:\laragon\www\SIPSKHP_TERA\application\views\dashboard\pengajuan_index.php';
$c = file_get_contents($file);

$search1 = "if (\$is_processed) {";
$replace1 = "\$is_selesai = (isset(\$item['status']) && \$item['status'] === 'Selesai');\n                            if (\$is_selesai) {\n                                \$status_badge = '<span class=\"badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill\"><i class=\"fas fa-check-double me-1\"></i> Uji Selesai</span>';\n                            } elseif (\$is_processed) {";

$c = str_replace($search1, $replace1, $c);

$search2 = "<?php if (\$is_processed): ?>";
$replace2 = "<?php if (\$is_selesai): ?>\n                                    <button class=\"btn btn-primary btn-sm px-3 shadow-sm rounded-pill text-white\" disabled title=\"Uji Selesai, Menunggu Cetak SKHP\">\n                                        <i class=\"fas fa-certificate me-1\"></i> Cetak SKHP\n                                    </button>\n                                <?php elseif (\$is_processed): ?>";

$c = str_replace($search2, $replace2, $c);

$search3 = "<button class=\"btn btn-light btn-sm px-3 rounded-pill text-muted\" disabled title=\"Telah Ditugaskan\">";
$replace3 = "<button class=\"btn btn-light btn-sm px-3 rounded-pill text-muted\" disabled title=\"Proses Uji Di Lapangan\">";
$c = str_replace($search3, $replace3, $c);

$search4 = "<i class=\"fas fa-lock me-1\"></i> Selesai";
$replace4 = "<i class=\"fas fa-cogs me-1\"></i> Proses Uji";
$c = str_replace($search4, $replace4, $c);

file_put_contents($file, $c);
echo "Done";
