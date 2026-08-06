<?php

namespace Database\Seeders;

use App\Models\AuditArea;
use App\Models\AuditProcess;
use App\Models\AuditRecord;
use App\Models\AuditUser;
use Illuminate\Database\Seeder;

class AuditSystemSeeder extends Seeder
{
    public function run(): void
    {
        // Helper function to build process list with range
        $generateProcesses = function (array $configs) {
            $processes = [];
            $order = 1;
            foreach ($configs as $cfg) {
                $prefix = $cfg['prefix'];
                $count = $cfg['count'];
                for ($i = 1; $i <= $count; $i++) {
                    $processes[] = [
                        'name' => "{$prefix} {$i}",
                        'sort_order' => $order++,
                    ];
                }
            }
            return $processes;
        };

        // 1. Seed 14 5S Standard Areas
        $areas5s = [
            [
                'slug' => 'all-process',
                'name' => 'All Process',
                'description' => 'Evaluasi standar 5S pada seluruh lini proses produksi',
                'icon_svg' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z',
                'processes' => $generateProcesses([
                    ['prefix' => 'General Items', 'count' => 21],
                ]),
            ],
            [
                'slug' => 'workers-and-inspectors',
                'name' => 'Workers and Inspectors',
                'description' => 'Pemeriksaan area kerja operator & inspektur kualitas',
                'icon_svg' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                'processes' => $generateProcesses([
                    ['prefix' => 'Workers and Inspectors', 'count' => 7],
                ]),
            ],
            [
                'slug' => 'jalur-jalan',
                'name' => 'Jalur Jalan',
                'description' => 'Area lalu lintas pedestrian & ketersediaan marka jalan',
                'icon_svg' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l5.447 2.724A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7',
                'processes' => $generateProcesses([
                    ['prefix' => 'Jalur Jalan', 'count' => 4],
                ]),
            ],
            [
                'slug' => 'tempat-penyimpanan-alat-kebersihan',
                'name' => 'Tempat Penyimpanan Alat Kebersihan',
                'description' => 'Penataan dan kerapian tempat penyimpanan alat 5S',
                'icon_svg' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                'processes' => $generateProcesses([
                    ['prefix' => 'Tempat Penyimpanan Alat Kebersihan', 'count' => 4],
                ]),
            ],
            [
                'slug' => 'warehouse',
                'name' => 'Warehouse',
                'description' => 'Area pergudangan bahan baku & komponen material',
                'icon_svg' => 'M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20',
                'processes' => $generateProcesses([
                    ['prefix' => 'Penyimpanan Terminal', 'count' => 11],
                    ['prefix' => 'Penyimpanan Wire', 'count' => 10],
                    ['prefix' => 'Penyimpanan Parts', 'count' => 9],
                    ['prefix' => 'Inspeksi Penerimaan Material', 'count' => 4],
                ]),
            ],
            [
                'slug' => 'special-process',
                'name' => 'Special Process',
                'description' => 'Proses khusus yang memerlukan pengawasan & parameter ekstra',
                'icon_svg' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                'processes' => $generateProcesses([
                    ['prefix' => 'Solder ( Deep Solder )', 'count' => 9],
                    ['prefix' => 'Middle Inspeksi (Solder)', 'count' => 4],
                    ['prefix' => 'Soldering Iron ( Gun Solder )', 'count' => 7],
                    ['prefix' => 'Bondering Process', 'count' => 5],
                    ['prefix' => 'Ultrasonic Welding Machine', 'count' => 14],
                    ['prefix' => 'Pemasangan Heat Shrink Tube', 'count' => 4],
                ]),
            ],
            [
                'slug' => 'pre-assy-drums',
                'name' => 'Pre Assy - Drums',
                'description' => 'Area persiapan perakitan awal komponen drum wiring',
                'icon_svg' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'processes' => $generateProcesses([
                    ['prefix' => 'Manual Crimping', 'count' => 10],
                ]),
            ],
            [
                'slug' => 'pre-assy-cassette',
                'name' => 'Pre Assy - Cassette',
                'description' => 'Persiapan perakitan kaset & sub-komponen kelistrikan',
                'icon_svg' => 'M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 4h16a1 1 0 011 1v14a1 1 0 01-1 1H4a1 1 0 01-1-1V5a1 1 0 011-1z',
                'processes' => $generateProcesses([
                    ['prefix' => 'Manual Crimping', 'count' => 6],
                    ['prefix' => 'Penyimpanan Terminal', 'count' => 9],
                    ['prefix' => 'Penyimpanan Wire', 'count' => 10],
                    ['prefix' => 'Penyimpanan APPL', 'count' => 5],
                    ['prefix' => 'Mesin Cutting and Crimping', 'count' => 7],
                    ['prefix' => 'LA Terminal Inspeksi Waterproof', 'count' => 5],
                    ['prefix' => 'Di Sekitar Mesin Otomatis ( Pemotongan hingga inspeksi menengah )', 'count' => 12],
                    ['prefix' => 'TI di sekitar Mesin Otomatis ( Insert Terminal Penempatan Sementara )', 'count' => 8],
                    ['prefix' => 'Insert Rubber Plug', 'count' => 6],
                    ['prefix' => 'Wire Hanger / Trolly Wire (setelah proses cutting)', 'count' => 4],
                    ['prefix' => 'Inspec Pre Assy', 'count' => 7],
                    ['prefix' => 'Wire Twist Process', 'count' => 5],
                    ['prefix' => 'Shield Wire Process', 'count' => 7],                                      
                ]),
            ],
            [
                'slug' => 'store-wire',
                'name' => 'Store Wire',
                'description' => 'Penyimpanan dan kerapian pengorganisasian stok kabel',
                'icon_svg' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                'processes' => $generateProcesses([
                    ['prefix' => 'Wire Store ( sebelum sub-Assembly )', 'count' => 6],
                ]),
            ],
            [
                'slug' => 'store-shipping-area',
                'name' => 'Store & Shipping Area',
                'description' => 'Area penyimpanan produk jadi dan pengiriman barang',
                'icon_svg' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1m-4 0h10',
                'processes' => $generateProcesses([
                    ['prefix' => 'Store and Shipping Area for JUNKAN Product', 'count' => 5],
                ]),
            ],
            [
                'slug' => 'final-assy',
                'name' => 'Final Assy',
                'description' => 'Lini perakitan akhir produk sebelum proses testing',
                'icon_svg' => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m14-6h2m-2 6h2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z',
                'processes' => $generateProcesses([
                    ['prefix' => 'Sub Assembly', 'count' => 8],
                    ['prefix' => 'Wire Layout / Taping', 'count' => 14],
                    ['prefix' => 'Pemasangan Grommet', 'count' => 6],
                    ['prefix' => 'Waterproof Inspection of Grommet', 'count' => 5],
                    ['prefix' => 'Electrical Inspection', 'count' => 17],
                    ['prefix' => 'Finishing / Offline Clip', 'count' => 7],
                    ['prefix' => 'Product Wire Hanger', 'count' => 6],
                    ['prefix' => 'Visual Inspection', 'count' => 12],
                    ['prefix' => 'Fuse Image Inspection Process', 'count' => 6],
                ]),
            ],
            [
                'slug' => 'bolt-tightening',
                'name' => 'Bolt Tightening',
                'description' => 'Stasiun pengencangan baut & kriteria torsi standar',
                'icon_svg' => 'M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z',
                'processes' => $generateProcesses([
                    ['prefix' => 'Bolt Tightening', 'count' => 13],
                ]),
            ],
            [
                'slug' => 're-work-process',
                'name' => 'Re-Work Process',
                'description' => 'Area perbaikan dan pengerjaan ulang item non-conforming',
                'icon_svg' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                'processes' => $generateProcesses([
                    ['prefix' => 'Re-work Process', 'count' => 6],
                ]),
            ],
            [
                'slug' => 'product-shipping-area',
                'name' => 'Product Shipping Area',
                'description' => 'Staging area pengiriman produk akhir ke pelanggan',
                'icon_svg' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                'processes' => $generateProcesses([
                    ['prefix' => 'Storage of Finished Products', 'count' => 8],
                    ['prefix' => 'Products Shipping Area', 'count' => 7],
                ]),
            ],
        ];

        $generalItemsData = [
            1 => [
                'checkpoint' => 'Ada pintu di tiap gerbang masuk. Pintu ke area produksi atau jendela tidak dibiarkan terbuka. (Untuk mencegah masuknya debu atau hewan). Gunakan pintu kasa saat pintu tetap terbuka. Berlaku untuk semua proses (dari warehouse hingga area shipping)',
                'kriteria_judgement' => "(1) Jika pada saat audit jendela dalam keadaan tertutup, evaluasi \"OK\". Jika anda diberitahu bahwa jendela tidak akan dibuka meskipun tidak ada tirai.\n(2) Evaluasi akan \"OK\" jika tirai vinil, jaring, dll dipasang di area pengiriman untuk mencegah masuknya serangga",
            ],
            2 => [
                'checkpoint' => 'Kabel, Pipa (termasuk kabel listrik sementara), stop kontak (outlet listrik) / sambungan listrik bebas dari kerusakan dan harus menggunakan cover sehingga tidak mengganggu jalur jalan untuk pekerja. (stop kontak/sambungan listrik dipasang di dinding/tiang)',
                'kriteria_judgement' => "① Instalasi kabel tidak boleh terlihat di lorong.\n*Jika ada penutup (tie wrap/kabel tie), akan dinilai \"OK\"",
            ],
            3 => [
                'checkpoint' => 'Menggunakan sepatu khusus saat berada di area produksi atau bersihkan sepatu pada keset saat masuk.',
                'kriteria_judgement' => '(1) Evaluasi akan "OK" jika di tempat menaiki forklift atau truck (Terminal) supaya memakai sepatu, dan pada saat memulai proses gantilah dengan sepatu dan pakailah keset agar kotoran terjatuh.',
            ],
            4 => [
                'checkpoint' => 'Nama line, proses dan mesin ditunjukkan dengan jelas',
                'kriteria_judgement' => 'Dalam proses pre assy hanya indikasi mesin cutting yang ditampilkan (mesin cutting/mesin otomatis). Di Final assy, nama mobil, part number, nomor line ditampilkan pada Conveyor dan jika tidak ada nama proses yang didisplay pada SSC dan Checker maka NG',
            ],
            5 => [
                'checkpoint' => 'Standar yang diperlukan (SWCT, OS/IS) disiapkan dan tersedia. Lokasi ditentukan dengan jelas sehingga dapat langsung diperiksa di lokasi produksi saat dibutuhkan',
                'kriteria_judgement' => '"NG" jika tidak ada SWCT',
            ],
            6 => [
                'checkpoint' => 'Area kerja dan jalur jalan diberi batas-batas dan cukup lebar untuk dilalui',
                'kriteria_judgement' => "(1) \"No Work Area\" pada area putaran Conveyor\n(2) Jika perlu melakukan pekerjaan berulang di dekat lorong, sediakan ruang minimal 800mm yang diperlukan untuk pekerjaan tersebut agar proses tersebut tidak dilakukan persis di jalur hijau\n(3) Zona pejalan kaki harus mempunyai lebar 800 mm atau lebih (termasuk lebar marka jalur).",
            ],
            7 => [
                'checkpoint' => 'Memastikan pencahayaan di area produksi sesuai dengan standar. (Produksi : 300Lux~, Inspection : 500Lux~, Warehouse : 75Lux~ ) ※Pastikan disesuaikan dengan peraturan khusus dari negara',
                'kriteria_judgement' => 'Evaluasi dinyatakan "OK" apabila pencahayaan diukur dengan illuminance dan nilai di atas rata-rata',
            ],
            8 => [
                'checkpoint' => 'Part dan product tidak terpapar langsung sinar matahari (Dihalangi kaca atau tirai)',
                'kriteria_judgement' => 'Memeriksa seluruh proses dan jendela warehouse. Jika ada sinar matahari langsung masuk, namun jendelanya menggunakan pelindung/penutup untuk part dan material maka hasilnya OK',
            ],
            9 => [
                'checkpoint' => 'Identifikasi kondisi abnormal dan beberapa orang yang dapat dihubungi bila terjadi. (cek contact person)',
                'kriteria_judgement' => '-',
            ],
            10 => [
                'checkpoint' => 'Letak dari barang-barang yang dapat didorong ditandai dengan layout dan nama produk. Barang bergerak (misalnya trolly/rak) memiliki indikasi produk.',
                'kriteria_judgement' => "① Garis layout ditampilkan sedemikian rupa sehingga secara akurat menentukan posisinya, keempat sudut diberi nilai \"OK\" dan hanya bagian depan/C yang diberi nilai \"NG\"\n② Untuk barang bergerak atau barang yang akan dipindah/dibawa, evaluasi \"OK\" jika menggunakan garis layout dan nama produk\n③ Tidak perlu menampilkan tempat penempatan troli part kecuali pada posisi tetap, misalnya pada saat troli part sedang disuplai.",
            ],
            11 => [
                'checkpoint' => 'Produk dan material yang jarang digunakan dan tidak digunakan selama satu bulan atau lebih, dipasang cover anti debu',
                'kriteria_judgement' => "1) Jika part diletakkan di area atau ruangan terpisah tetapi dilindungi oleh penutup tahan debu, evaluasi \"OK\".\n2) Walaupun part tersebut termasuk \"non-flow product\" evaluasinya \"NG\" jika terdapat debu di area tersebut.",
            ],
            12 => [
                'checkpoint' => 'Item yang tidak perlu untuk proses tidak ditinggalkan di area kerja kecuali di tempat yang ditentukan. Semua item ditempatkan di tempat yang ditentukan.',
                'kriteria_judgement' => "① Jika meletakkan kanban atau karet gelang di tempat selain tempat penempatannya, maka akan dinilai sebagai x.\n② Jika ada item berlebih di bawah tempat inspeksi checker/tempat inspeksi visual, dll., evaluasinya NG\n③ Evaluasi dinyatakan \"OK\" apabila barang pribadi diletakkan di tempat yang ditentukan\n④ Hasil dari proses lain yang dievaluasi sebagai NG untuk item yang sama tidak tercermin dalam item criteria audit",
            ],
            13 => [
                'checkpoint' => 'Setiap barang yang disimpan di luar pabrik memiliki petunjuk lokasi, due date, dan PIC. Untuk penyimpanan jangka panjang, jig board atau jig inspeksi harus ditutup dengan cover pelindung.',
                'kriteria_judgement' => '-',
            ],
            14 => [
                'checkpoint' => 'Part box, box plastik, dan box W/H TIDAK boleh diletakkan di lantai (diperlukan celah minimal 3 cm di antara kotak dan lantai)',
                'kriteria_judgement' => "1) Jika box atau tas diletakkan langsung di lantai, maka evaluasinya \"NG\"\n2) Standarnya 3cm, namun tingginya tidak terlalu menjadi masalah asalkan polytainer tidak diletakkan langsung di lantai untuk mencegah bagian bawah dari debu.",
            ],
            15 => [
                'checkpoint' => 'Untuk menghindari kerusakan akibat jatuh, PC dan peralatan sensitif lainnya dilindungi dengan tindakan pencegahan agar tidak jatuh.',
                'kriteria_judgement' => '-',
            ],
            16 => [
                'checkpoint' => 'Sampah dipisahkan menurut aturan pabrik. Wadah sampah tidak boleh meluap',
                'kriteria_judgement' => "1) \"Pemilahan sampah\" dan \"tempat sampah tidak meluap\" diamati dan dievaluasi\n2) Jika pabrik tidak memerlukan pemilahan/pemisahan jenis sampah, maka akan dapat dilakukan audit jika ada tempat sampah dipasang.",
            ],
            18 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan (polytainer) dan menjaganya tetap bersih *Lepaskan kanban lama/tag yang rusak. *Frekuensi ditentukan oleh masing-masing pabrik *Proses audit akan ditentukan oleh masing-masing pabrik dan polytainer yang dibersihkan di cek. (termasuk yang berlaku untuk audit adalah Junkan, Inspeksi visual, cell dan inspeksi cheker)',
                'kriteria_judgement' => "1) Evaluasi \"OK\" jika polytainer yang digunakan pada 3 proses (pengiriman JUNKAN, inspeksi visual, cheker/inspeksi sell) dibersihkan dan tidak ada kotoran atau debu yang menempel pada produk.\n2) Evaluasi \"OK\" jika polytainer dibersihkan di luar proses inspeksi.\n3) Evaluasi adalah \"NG\" jika Kanban dan tag lama tidak dihapus dari polytainer.",
            ],
            19 => [
                'checkpoint' => 'Wadah (polytainer) tidak retak dan missing rangka besi. holder kanban juga tidak rusak dan terkelupas, tidak ada karat dan kapur/cat (akibat kapur/kerusakan warna) yang menempel di jari saat disentuh. ※Proses yang berlaku untuk audit adalah Junkan, Inspeksi visual, cell dan inspeksi cheker',
                'kriteria_judgement' => '-',
            ],
            20 => [
                'checkpoint' => 'Stripping conductor dan terminal tertentu memiliki cover (atau similar part) *Lihat lembar poin 5S untuk terminal yang ditentukan. Selain itu, harus ada upaya untuk mencegah belitan dengan terminal bundel wire lainnya.',
                'kriteria_judgement' => "1) Terminal yang lebih kecil dari 2.3 II (090II), 6.3 (250), relai terminal dan wire yang terminal spesifiknya di crimping dapat digunakan.\n2) \"OK\" jika tutup pelindung atau benda serupa terpasang, bundle wire tidak bersentuhan pada hanger dan wire terlindung dari debu.",
            ],
            21 => [
                'checkpoint' => 'Terdapat aturan 5W1H yang terdokumentasi tentang pembersihan cap pelindung conductor/terminal dan tidak kotor. *Frekuensi ditentukan oleh masing-masing pabrik',
                'kriteria_judgement' => 'Periksa frekuensi pembersihan, dan apakah waktu pembersihan, orang yang membersihkan, metode pembersihan, dll. Sudah disetting jadi dipakai sesuai standar masing-masing pabrik, evaluasinya "OK". (Instruksikan operator di mesin untuk mengencangkan lengan baju mereka.)',
            ],
        ];

        $workersAndInspectorsData = [
            1 => [
                'checkpoint' => 'Seragam resmi (topi, jaket, celana dan sepatu) ditentukan dan pekerja memakainya dengan benar. Seragam harus bersih',
                'kriteria_judgement' => 'Standar seragam berbeda-beda tergantung masing-masing pabrik, jadi dipakai sesuai standar masing-masing pabrik, evaluasinya "OK". (Instruksikan operator di mesin untuk mengencangkan lengan baju mereka.)',
            ],
            2 => [
                'checkpoint' => 'Pekerja memakai dan menunjukkan license proses yang diperlukan untuk proses. Dan sesuai dengan aktual proses',
                'kriteria_judgement' => '-',
            ],
            3 => [
                'checkpoint' => 'Name tag dan operation license terpasang rapih',
                'kriteria_judgement' => "1) Evaluasi \"OK\" jika diamankan agar tidak jatuh/longgar. Evaluasi \"NG\" jika jatuh/longgar.\n2) Tempat pemasangan mengikuti aturan masing-masing pabrik (bila tidak sesuai aturan maka evaluasinya adalah \"NG\")",
            ],
            4 => [
                'checkpoint' => 'Operator tidak boleh memakai perhiasan (misalnya: cincin) tanpa sarung tangan saat menangani produk dengan tangan kosong',
                'kriteria_judgement' => '-',
            ],
            5 => [
                'checkpoint' => 'Operator tidak memiliki kuku yang panjang. (Kuku palsu NG) Menggunakan sarung tangan khusus dan sarung tangan tidak kotor atau sobek. Jangan gunakan sarung tangan berbulu halus',
                'kriteria_judgement' => '-',
            ],
            6 => [
                'checkpoint' => 'Gunakan sarung tangan khusus, dan jaga agar tetap bersih dan tidak rusak. (sarung tangan dengan bulu halus dilarang) Sarung tangan kerja boleh digunakan untuk pekerjaan yang tidak melibatkan kontak langsung dengan produk. *Sarung tangan harus dipakai sesuai dengan peraturan yang ditetapkan pabrik.',
                'kriteria_judgement' => 'Memeriksa rule/aturan dan kepatuhan terhadap aturan.',
            ],
            7 => [
                'checkpoint' => 'Tidak melilitkan selotip di ujung jari. Jika melakukannya, sarung tangan harus dipakai. ※Bukti aturan yang ditentukan dari pabrik mengenai penggunaan sarung tangan',
                'kriteria_judgement' => '(1) Evaluasi "OK" jika menambahkan taping pada bagian pergelangan tangan untuk mencegah sarung tangan bergeser',
            ],
        ];

        $jalurJalanData = [
            1 => [
                'checkpoint' => 'Tidak ada barang yang seluruhnya atau sebagian ditempatkan di jalur hijau/area proses (Diletekkan di garis dianggap NG) Tidak boleh ada apapun termasuk tanda yang ditempatkan di jalur hijau dan di garis. Tidak ada tonjolan atau anak tangga yang dapat mengganggu trolly.',
                'kriteria_judgement' => "1) Batas garis untuk keseluruhan pabrik tidak diperlukan, \"OK\" jalur hijau dan lorong kerja diberi garis pada setiap proses.\n2) \"NG\" jika tidak ada layout untuk AC, air yang diminum, dll\n3) Jalur hijau dibatasi sampai garis terluar dari layout. \"NG\" Jika ada barang yang ditempatkan pada jalur hijau.\n4) Rak harus sesuai dengan layout, tidak boleh melebihi yang sudah ditentukan\n5) Tidak ada tangga yang memindahkan troli dll selama proses berlangsung,(tidak termasuk pintu masuk pengunjung, dll.)\n6) \"NG\" jika AGV (Automatic Guided Vehicle) digunakan di dalam Jsafety aisle pada proses\n7) Safety Aisle =Zona berjalan kaki + Rute evakuasi",
            ],
            2 => [
                'checkpoint' => 'Jalur hijau dipisahkan dari area operasi oleh garis, dan garis tidak terputus.',
                'kriteria_judgement' => "Berjalan memutari pabrik untuk memeriksa\n1) Area proses dan jalur ditentukan\n2) Jalur hijau yang terkelupas \"NG\"\n(3) Jsafety aisle dan proses tidak termasuk",
            ],
            3 => [
                'checkpoint' => 'Rentang buka/tutup pintu ditunjukkan dengan garis putus-putus.',
                'kriteria_judgement' => 'Meskipun bukan dengan garis putus putus evaluasi dinyatakan "ok" jika garis line tidak pudar',
            ],
            4 => [
                'checkpoint' => 'AGV yang berada di lorong dilengkapi dengan alarm untuk memberikan sinyal mendekat dan alat berhenti otomatis. AGV: Automatic Guide Vehicle',
                'kriteria_judgement' => "1) Evaluasi \"OK\" jika Contact sensor Bar dan sensor penghalang dipasang dan perangkat peringatan pengoperasian berfungsi.\n2) Evaluasinya adalah \"NG\" jika driving torsi kecil dan henti fungsi. (Kemampuan berhenti otomatis saat tabrakan)",
            ],
        ];

        $kebersihanData = [
            1 => [
                'checkpoint' => 'Nama dan Jumlah semua alat pembersih terdisplay dan semuanya tersedia untuk digunakan.',
                'kriteria_judgement' => '-',
            ],
            2 => [
                'checkpoint' => 'PIC dan prosedur pemeriksaan dan meletakkan kembali alat pembersih ditunjukkan.',
                'kriteria_judgement' => '-',
            ],
            3 => [
                'checkpoint' => 'Area pembersihan terdisplay dan diimplementasikan sesuai dengan plan.',
                'kriteria_judgement' => 'Evaluasi adalah "OK" jika alokasi pembersihan dijelaskan oleh daftar dan bukan dalam layout proses',
            ],
            4 => [
                'checkpoint' => 'Sapu disimpan dengan hati-hati agar sikatnya tidak rusak (misalnya sikat tidak menyentuh lantai.)',
                'kriteria_judgement' => '-',
            ],
        ];

        $penyimpananTerminalData = [
            1 => [
                'checkpoint' => 'Penutup tahan debu digunakan pada rak/rak terminal dan ditutup dengan benar (Jika disimpan dalam kotak atau tas, tidak diperlukan penutup; tapi tutupnya harus ditutup)',
                'kriteria_judgement' => "Karena terminal silver mudah berkarat, untuk melakukan pencegahan training harus diberikan.\n1. \"NG\" Jika tidak ada cover anti debu\n2. \"OK\" apabila terminal berada di dalam kardus (\"NG\" apabila terminal keluar dari kardus)\n3. Meskipun penutup tahan debu dipasang pada rak, terminalnya tetap \"NG\" jika terbuka.",
            ],
            2 => [
                'checkpoint' => 'Terdapat tampilan part number di lokasi penyimpanan, dan tampilan nomor assy serta actual produk (nomor assy box terminal) sesuai. Identitasnya terdapat di bagian atas. Jika diletakkan di bawah, diperlukan instruksi dengan tanda panah untuk menunjukan posisi partnya. Namun, jika tempat penyimpanannya satu tingkat, tidak diperlukan panah.',
                'kriteria_judgement' => "(1) Tidak perlu menampilkan adress\n(2) Evaluasi dinyatakan \"OK\" apabila penyimpanan temporary ditampilkan\n(3) N/A jika penyimpanan dikelola oleh sistem manajemen (SAP, dll.).",
            ],
            3 => [
                'checkpoint' => 'Mengikuti aturan FIFO "First in dan First Out"',
                'kriteria_judgement' => 'N/A jika sistem manajemen diinstal dan penyimpanan dikelola olehnya (SAP, dll.).',
            ],
            4 => [
                'checkpoint' => 'Label Part No. ditempelkan ke setiap gulungan terminal. (NG jika hanya ada petunjuk Kanban.)',
                'kriteria_judgement' => 'Beberapa terminal yang dibeli di luar Yazaki tidak memiliki nomor part instruksi Yazaki) untuk setiap gulungan, evaluasi "OK" jika nomor komponen instruksi produksi dimasukkan.',
            ],
            5 => [
                'checkpoint' => 'Gulungan terminal Side-by-side harus diletakkan dengan menempatkan carrier terminal di sisi bawah. (Letakkan kotak reel dengan arah panah ke atas)',
                'kriteria_judgement' => 'Evaluasi dinyatakan "NG" apabila ditempatkan secara vertikal di warehouse',
            ],
            6 => [
                'checkpoint' => 'Terminal reels end-to-end terminal harus diletakkan secara vertikal untuk mencegah kerusakan. (T/A untuk bahan non-kardus)',
                'kriteria_judgement' => 'Jika orientasi bantalan karton kiri dan kanan berbeda, belum ada peraturan mengenai cara memasang gulungan karton.',
            ],
            7 => [
                'checkpoint' => 'Jangan menumpuk lebih dari 4 gulungan terminal side to side. Jangan menumpuk lebih dari 10 kotak gulungan terminal side to side, dan tidak lebih dari 3 kotak gulungan terminal end to end.',
                'kriteria_judgement' => 'Evaluasi kondisi stok di area receiving dan warehouse',
            ],
            8 => [
                'checkpoint' => 'Box / Reels terminal tidak diletakkan langsung dilantai.',
                'kriteria_judgement' => "(1) Evaluasi dinyatakan \"NG\" apabila meletakkan kardus diatas lantai.\n(2) Evaluasi dinyatakan \"OK\" apabila penempatan lebih dari 3 cm diatas lantai.",
            ],
            9 => [
                'checkpoint' => 'Aturan 5W1H yang tertulis untuk pembersihan dan tidak kotor. Selain itu, kanban lama dan tag yang rusak juga dihapus. ※Frekuensi ditentukan oleh masing-masing pabrik.',
                'kriteria_judgement' => 'Periksa frekuensi pembersihan, dan apakah waktu pembersihan, orang, metode, dll. telah diatur.',
            ],
            10 => [
                'checkpoint' => 'Terminal tidak mencuat dari gulungan. (*Untuk gulungan terminal bekas, pasang clip diujung terminal ke gulungan)',
                'kriteria_judgement' => "(1) Evaluasi dinyatakan \"OK\" apabila terminal terlindungi kertas antar lapisan.\n(2) Evaluasi dinyatakan \"NG\" apabila terminal menjuntai meskipun hanya 50cm.",
            ],
            11 => [
                'checkpoint' => 'Terminal individu (yang tidak dalam gulungan) disimpan dalam kondisi tahan debu.',
                'kriteria_judgement' => "1) \"OK\"\n2) \"OK\" jika plastic bisa ditutup.",
            ],
        ];

        $penyimpananWireData = [
            1 => [
                'checkpoint' => 'Jumlah gulungan wire dalam tumpukan tidak lebih dari 5 pcs.',
                'kriteria_judgement' => "Evaluasi kondisi penyimpanan di rak setelah menerima wire.\nSebelum menyimpan wire di rak, evaluasi sebagai \"OK\"\nJika ketinggian wire yang ditumpuk di palet berada dalam standar yang ditentukan",
            ],
            2 => [
                'checkpoint' => 'Penyimpanan dipisahkan berdasarkan jenis dan ukuran wire',
                'kriteria_judgement' => "(1) Area yang kosong tidak berlaku (Hanya area yang di kontrol saja)\n2) Temporary area yang digunakan sebagai penerimaan untuk memasuki warehouse tidak berlaku.\n3) Jika di Temporary area ada display (Temporary area) maka evaluasinya \"OK\"",
            ],
            3 => [
                'checkpoint' => 'Nomor part (jenis, ukuran, warna) tertera di lokasi penyimpanan dan sesuai dengan barang actual (Label wire) Identitasnya terletak di bagian atas, jika ingin ditampilkan di bagian bawah maka digunakan tanda panah sebagai penunjuknya, namun tanda panah tidak diperlukan jika penyimpanan hanya mempunyai satu layer',
                'kriteria_judgement' => "(1) Tidak perlu menampilkan identitas alamat/address\n(2) Jika di Temporary area ada display (Temporary area) maka evaluasinya \"OK\"\n(3) N/A jika penyimpanan dikelola oleh sistem manajemen (SAP, dll.)",
            ],
            4 => [
                'checkpoint' => 'Ada aturan untuk "First In dan First Out" dan diimplementasikan',
                'kriteria_judgement' => 'N/A jika sistem kontrol diinstal dan penyimpanan dikelola oleh (SAP dll.)',
            ],
            5 => [
                'checkpoint' => 'Bundle wire ditempatkan miring. (Jika dimasukkan ke dalam cassete reel, dapat ditempatkan secara vertikal)',
                'kriteria_judgement' => "Meskipun disimpan di cassete reel\nJika item control dipatuhi maka evaluasinya \"ok\"",
            ],
            6 => [
                'checkpoint' => 'Wire tidak ditempatkan langsung di lantai, Gunakan papan kayu (RAK, palet).',
                'kriteria_judgement' => "(1) Evaluasi \"OK\" Jika berada 3cm di atas lantai\n(2) Evaluasi \"NG\" apabila trolley dan rak terbuat dari bahan kayu.\n3) Jika menggunakan papan untuk memindahkan wire, Jangan gunakan kayu pada permukaan yang bersentuhan dengan wire (antisipasi dengan menggunakan papan plastik)",
            ],
            7 => [
                'checkpoint' => 'Bundel wire, Cassete reel, dan bobbin (gulungan wire) memiliki label (NG jika hanya ada kanban tanpa label saja).',
                'kriteria_judgement' => '-',
            ],
            8 => [
                'checkpoint' => 'Masukkan ujung wire dari cassete reel atau bobbin (gulungan wire) melalui lubang samping. (Tonjolan wire 150mm atau kurang) Selain itu, untuk wire size besar yang tidak dapat melewati lubang samping, maka kaitkan ujung wire digulungan saja agar gulungan tidak rusak/hancur',
                'kriteria_judgement' => "Evaluasi sebagai \"OK\" jika ujung wire terpasang erat dan wire yang longgar dapat dicegah dengan metode seperti memasukkan wire melalui lubang samping atau menyelipkan ujung wire ke dalam bundel.\nUjung wire tidak menonjol terlalu panjang",
            ],
            9 => [
                'checkpoint' => 'Untuk cassete reel dan wire reel, pinggiran luar reel (body utama/cover), yang mungkin bersentuhan/terkena oleh wire dan tidak boleh damage (Cassete reel yang dimodifikasi juga tidak diperbolehkan)',
                'kriteria_judgement' => '-',
            ],
            10 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk kebersihan dan tidak kotor. *Frekuensi ditentukan oleh tiap pabrik',
                'kriteria_judgement' => 'Periksa frekuensi pembersihan, waktu pembersihan orang yang membersihkan, metode, dll telah ditentukan',
            ],
        ];

        $penyimpananPartsData = [
            1 => [
                'checkpoint' => 'Part disimpan dalam kotak atau tas, dan ada identifikasi nomor assy. (NG jika hanya ada kanban)',
                'kriteria_judgement' => "Untuk part dibeli di luar Yazaki tidak memiliki nomor part instruksi pembuatan (Nomor Yazaki) untuk setiap box/kantong.\nEvaluasi \"OK\" jika nomor part instruksi pembuatan dimasukkan.",
            ],
            2 => [
                'checkpoint' => 'Nomor assy ditunjukkan di lokasi penyimpanan dan cocok dengan actual barangnya. (P/nomor part box) Identitasnya terletak di bagian atas. Jika ingin ditampilkan di bagian bawah gunakan tanda panah sebagai penunjuk. Namun, panah tidak diperlukan jika tempat penyimpanan hanya 1 layer saja.',
                'kriteria_judgement' => "(1) Tidak perlu menampilkan identitas alamat/adress\n(2) Jika di Temporary area ada display (Temporary area) maka evaluasinya \"OK\"\n(3) Meskipun lokasi penyimpanan di tampilkan di satu tempat dalam daftar maka evaluasinya \"OK\"\n(4) N/A jika penyimpanan dikelola oleh sistem manajemen (SAP, dll.)",
            ],
            3 => [
                'checkpoint' => 'Ada aturan yang terdokumentasi untuk FIFO "First in dan First Out"',
                'kriteria_judgement' => 'N/A jika sistem kontrol diinstal dan penyimpanan dikelola oleh (SAP dll.)',
            ],
            4 => [
                'checkpoint' => 'Part tidak diletakkan langsung di lantai',
                'kriteria_judgement' => "(1) \"NG\" jika box diletakkan langsung di lantai\n(2) \"OK\" apabila diletakan dengan jarak minimal 3cm dari lantai",
            ],
            5 => [
                'checkpoint' => 'Perbedaan antara part yang similar ditunjukkan dengan jelas dan ditulis/ditandai, dan part tersebut di cross check sebelum di suplly ke line produksi *Jika sulit membedakan/menilai perbedaannya dengan sampel dan actual gambar, tentukan perbedaannya lainnya yang spesifik',
                'kriteria_judgement' => "(1) Cek conector dan part yang similar\n(2) Jika part di suplay dalam box atau tas ke produksi, sampel part tidak perlu ditampilkan\n(Jika part tidak diperiksa dengan no part melainkan dengan metode seperti pemindahan barcode atau kode QR saat suply ke proses, apabila tidak menunjukkan point perbedaan pada similar no part maka hasil evaluasinya \"NG\")",
            ],
            6 => [
                'checkpoint' => 'Material diisi dalam jumlah yang cukup untuk mencegah meluap dan tidak tumpah. *Cara mengisi material ke dalam kotak dipertimbangkan dengan baik untuk mencegahnya jatuh. E.g - Kotak komponen memiliki garis kontrol.',
                'kriteria_judgement' => "1) Sekalipun ada indikasi garis (batas limit) pengisian, Jika ada kemungkinan part jatuh karena kondisi penyimpanan yang miring maka hasilnya \"NG\"\n2) Jika ada mekanisme untuk mencegah part tercampur seperti dengan menutup box, hal itu akan dievaluasi \"OK\" meskipun tidak ada garis line nya.",
            ],
            7 => [
                'checkpoint' => 'Material yang terjatuh segera dimasukkan ke dalam wadah khusus (kotak dan kantong plastik juga dapat digunakan) dan handle oleh supervisor.',
                'kriteria_judgement' => "(1) Pasang kotak untuk part yang jatuh di satu tempat atau lebih dalam proses dan jika part jatuh dimasukan ke situ maka hasilnya \"OK\"\n(2) \"NG\" apabila SPV tidak menentukan perawatan part jatuh dan penanggulangannya\n(3) Pada saat pembuangan part yang jatuh, jika peraturannya jelas dan di ikuti maka evaluasinya",
            ],
            8 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan dan tidak kotor. *Frekuensi ditentukan oleh setiap pabrik',
                'kriteria_judgement' => 'Periksa frekuensi pembersihan, waktu pembersihan orang yang membersihkan, metode, dll telah ditentukan',
            ],
            9 => [
                'checkpoint' => 'Part box tidak rusak *Jika box rusak, periksa kondisi part dan masukkan ke box lain dan tempelkan label kontrol baru. *Atau kembali ke suplier',
                'kriteria_judgement' => 'Jika ada part box yang rusak, periksa isinya, ganti kotaknya, dan ganti label kontrol, lalu pengambilan tindakan pengembalian part yang rusak ke suplier.',
            ],
        ];

        $inspeksiPenerimaanData = [
            1 => [
                'checkpoint' => 'Lokasi penyimpanan tool inspeksi, lembar pemeriksaan inspeksi ditempatkan sesuai.',
                'kriteria_judgement' => 'Penempatan sementara (Choioki) adalah "NG" bahkan selama proses.',
            ],
            2 => [
                'checkpoint' => 'Tanggal kalibrasi tertera pada equipment pengukuran dan valid.',
                'kriteria_judgement' => 'Cek equipment yang memerlukan kalibrasi, dan jika dalam tanggal valid, evaluasi "OK"',
            ],
            3 => [
                'checkpoint' => 'Terdapat indikasi inspeksi "Sebelum" dan "Sesudah" inspeksi serta produk diklasifikasikan dengan baik.',
                'kriteria_judgement' => "(1) \"OK\" jika before dan after inspect diklasifikasikan\n(2) \"NG\" jika before dan after inspect tidak ada identitasnya",
            ],
            4 => [
                'checkpoint' => 'Terdapat tempat penyimpanan khusus untuk produk defect dan label defect terpasang.',
                'kriteria_judgement' => "(1) Evaluasi \"OK\" jika defect disimpan dalam penyimpanan produk defect dan tag indikasi cacat dipasang\n(2) \"NG\" jika penyimpanan produk defect tidak disediakan",
            ],
        ];

        $solderDeepData = [
            1 => [
                'checkpoint' => 'Pengaturan suhu rendaman solder standar internal ditampilkan, dan tidak ada yang terkelupas, sobek, atau tergores yang membuat isinya tidak dapat dibaca.',
                'kriteria_judgement' => "Cek kondisi actual\n1) (batas atas dan batas bawah) suhu di pabrik harus ditentukan, jika tidak maka \"NG\"\n(2) Evaluasi dinyatakan \"OK\" apabila actualnya diukur, dan berada pada suhu yang telah ditentukan\n(3) Evaluasi dinyatakan \"NG\" apabila display suhu tidak dapat terbaca",
            ],
            2 => [
                'checkpoint' => 'Lokasi barang-barang yang diperlukan ditunjukkan dan barang-barang ditempatkan sesuai. *Item yang tidak berada di lokasi yang ditentukan tidak dapat diterima.',
                'kriteria_judgement' => "1) Meskipun menggunakan kanban dan karet gelang penempatan sementara (Choioki) evaluasinya \"NG\"\n(2) Evaluasi akan dinyatakan \"OK\" apabila menaruh barang pribadi di tempat yang sudah di tentukan",
            ],
            3 => [
                'checkpoint' => 'Bantalan/pad pelurus ujung wire dipasang dan digunakan dengan benar.',
                'kriteria_judgement' => 'Meskipun ada pad/bantalan, hasilnya "NG" apabila ada kotoran dan karat',
            ],
            4 => [
                'checkpoint' => 'Terdapat aturan tertulis untuk menghilangkan residu dan endapan oxide film di wadah penyolderan selama proses. Selain itu, kotak khusus untuk oxide film yang dibuang juga dipasang.',
                'kriteria_judgement' => '"NG" jika metode menghilangkan lapisan oksida tidak ditentukan.',
            ],
            5 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan wadah solder dan tidak kotor *Frekuensi ditentukan oleh masing-masing pabrik.',
                'kriteria_judgement' => "(1) Cek hasil inspeksi reguler\n(2) Cek frekuensi kebersihan, waktu, orang, dan metodenya",
            ],
            6 => [
                'checkpoint' => 'Ada indikasi "before" dan "after" produk diklasifikasikan dengan baik.',
                'kriteria_judgement' => '-',
            ],
            7 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan tangki solder dan tidak kotor. *Frekuensi ditentukan oleh masing-masing pabrik',
                'kriteria_judgement' => 'Cek frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum.',
            ],
            8 => [
                'checkpoint' => 'Operator memakai kacamata pelindung dan sarung tangan kerja selama proses. ※Ikuti aturan pabrik. N/A jika tidak ada aturan untuk memakai kacamata pelindung dan sarung tangan.',
                'kriteria_judgement' => '-',
            ],
            9 => [
                'checkpoint' => 'Pembuangan dan ventilasi dipasang',
                'kriteria_judgement' => "Cek ventilasi udara selama bekerja\n(1) \"NG\" jika hanya membuka jendela saja",
            ],
        ];

        $middleInspeksiData = [
            1 => [
                'checkpoint' => 'Di area produksi disediakan penerangan yang cukup (area produksi : lebih dari 300 lux, inspeksi : lebih dari 500 lux, warehouse : lebih dari 75 lux) *Pastikan untuk mematuhi peraturan setiap negara',
                'kriteria_judgement' => 'Kecerahan yang diukur dengan pengukur pencahayaan berada di atas nilai standar.',
            ],
            2 => [
                'checkpoint' => 'Ada indikasi "before" dan "after" inspection dan produk diklasifikasikan dengan baik.',
                'kriteria_judgement' => "(1) Ada klasifikasi sebelum dan sesudah inspect\n(2) \"NG\" jika tidak ada indikasi before dan after inspect",
            ],
            3 => [
                'checkpoint' => 'Tempat penyimpanan untuk produk defect ditentukan, dan label produk defect ditempelkan pada actual produk dengan informasi yang diperlukan diisi.',
                'kriteria_judgement' => "(1) Product defect disediakan tempat penyimpanan dan label defect terpasang\n(2) \"NG\" apabila tidak ada tempat penyimpanan produk defect",
            ],
            4 => [
                'checkpoint' => 'Perangkat peringatan abnormal dipasang dan berfungsi dengan baik. Peringatan harus divisualisasikan dan sebaiknya memiliki alarm yang memberi sinyal kelainan. (contoh: andon)',
                'kriteria_judgement' => "1) Jika kondisi abnormal bisa divisualisasikan dengan bendera dll maka hasilnya \"OK\"\n(2) \"NG\" apabila hanya dengan suara atau audio saja",
            ],
        ];

        $solderingGunData = [
            1 => [
                'checkpoint' => 'Dokumen seperti Point Kerja ditampilkan dan tidak rusak (tidak terkelupas, sobek atau tidak bisa dibaca)',
                'kriteria_judgement' => '-',
            ],
            2 => [
                'checkpoint' => 'Lokasi barang-barang yang diperlukan ditunjukkan dan barang-barang ditempatkan sesuai. *Item yang tidak berada di lokasi yang ditentukan tidak dapat diterima.',
                'kriteria_judgement' => "1) Meskipun menggunakan kanban dan karet gelang penempatan sementara (Choioki) evaluasinya \"NG\"\n(2) Evaluasi akan dinyatakan \"OK\" apabila menaruh barang pribadi di tempat yang sudah ditentukan",
            ],
            3 => [
                'checkpoint' => 'Suhu yang ditentukan untuk solder iron dikontrol dan dicatat.',
                'kriteria_judgement' => "N/A jika kontrol suhu dapat dijamin oleh equipment\n1) Jika tidak ada display suhu\n2) Suhu disetting secara otomatis",
            ],
            4 => [
                'checkpoint' => 'Solder iron diperiksa setiap kali sebelum digunakan, dan ada aturan pembersihan 5W1H yang terdokumentasi dan tidak kotor. *Frekuensi ditentukan oleh masing-masing pabrik.',
                'kriteria_judgement' => "(1) Cek hasil pemeriksaan berkala (checksheet)\n(2) Cek frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum.",
            ],
            5 => [
                'checkpoint' => 'Ada aturan 5W1H yang tertulis untuk membersihkan meja kerja dan tidak kotor. *Frekuensi ditentukan oleh tiap pabrik',
                'kriteria_judgement' => 'Cek frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum.',
            ],
            6 => [
                'checkpoint' => 'Ada indikasi "before" dan "after" produk diklasifikasikan dengan baik.',
                'kriteria_judgement' => '-',
            ],
            7 => [
                'checkpoint' => 'Operator memakai kacamata pelindung dan sarung tangan kerja. *Sarung tangan dan kacamata pelindung sesuai dengan aturan yang ditentukan pabrik.',
                'kriteria_judgement' => 'Jika menaati aturan pabrik maka evaluasinya "OK" dan "NG" jika tidak',
            ],
        ];

        $bonderingData = [
            1 => [
                'checkpoint' => 'Dokumen seperti Point Kerja ditampilkan dan tidak rusak (tidak terkelupas, sobek atau tidak bisa dibaca).',
                'kriteria_judgement' => '-',
            ],
            2 => [
                'checkpoint' => 'Tempat Lokasi barang-barang yang diperlukan ditunjukkan dan barang-barang ditempatkan sesuai. *Item yang tidak berada di lokasi yang ditentukan tidak dapat diterima.',
                'kriteria_judgement' => "1) Meskipun menggunakan kanban dan karet gelang penempatan sementara (Choioki) evaluasinya \"NG\"\n(2) Evaluasi akan dinyatakan \"OK\" apabila menaruh barang pribadi di tempat yang sudah ditentukan",
            ],
            3 => [
                'checkpoint' => 'Parameter heat welding (bonder) diklarifikasi dan catatannya disimpan',
                'kriteria_judgement' => 'Evaluasinya "NG" jika tidak ada garis kontrol atas dan bawah',
            ],
            4 => [
                'checkpoint' => 'Ada aturan 5W1H yang tercatat untuk membersihkan dan tidak kotor. *Frekuensi ditentukan oleh tiap pabrik',
                'kriteria_judgement' => 'Cek frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum.',
            ],
            5 => [
                'checkpoint' => 'Terdapat indikasi proses "Before" dan "After" dan produk diklasifikasikan dengan baik sesuai indikasi',
                'kriteria_judgement' => 'Harus ada evaluasi sebelum dan sesudah bonder, produk dari masing-masing proses tidak tercampur.',
            ],
        ];

        $ultrasonicData = [
            1 => [
                'checkpoint' => 'Dokumen seperti Point Kerja ditampilkan dan tidak rusak (tidak terkelupas, sobek atau tidak bisa dibaca)',
                'kriteria_judgement' => '-',
            ],
            2 => [
                'checkpoint' => 'Lokasi barang-barang yang diperlukan ditunjukkan dan barang-barang ditempatkan sesuai dengan lokasinya *Barang-barang yang tidak berada di lokasi yang ditentukan NG',
                'kriteria_judgement' => "1) Meskipun menggunakan kanban dan karet gelang penempatan sementara (Choioki) evaluasinya \"NG\"\n(2) Evaluasi akan dinyatakan \"OK\" apabila menaruh barang pribadi di tempat yang sudah ditentukan",
            ],
            3 => [
                'checkpoint' => 'Terdapat indikasi proses "Before" dan "After" dan produk diklasifikasikan dengan baik sesuai indikasi',
                'kriteria_judgement' => '-',
            ],
            4 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan dan tidak kotor. *Frekuensi ditentukan oleh setiap pabrik',
                'kriteria_judgement' => 'Cek frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum.',
            ],
            5 => [
                'checkpoint' => 'Nomor part ditunjukkan di lokasi penyimpanan dan sesuai dengan actual produk (P/no part box) Penunjuknya terletak di bagian atas, jika ingin ditampilkan di bagian bawah gunakan tanda panah sebagai penunjuknya, namun tanda panah tidak diperlukan jika rak hanya 1 layer',
                'kriteria_judgement' => 'Hanya boleh ada satu identitas pada part box',
            ],
            6 => [
                'checkpoint' => 'Ada aturan tertulis untuk "First in dan First out" dan dipatuhi dengan ketat.',
                'kriteria_judgement' => 'N/A jika penyimpanan dikelola oleh sistem manajemen (SAP, dll.)',
            ],
            7 => [
                'checkpoint' => 'Part tersebut disimpan dalam jumlah yang sesuai agar tidak meluap dan tidak terjatuh. *Jika ada garis batas maksmiali, maka harap diikuti',
                'kriteria_judgement' => "1) Sekalipun ada indikasi garis (batas garis), evaluasi sebagai \"NG\" jika ada kemungkinan part jatuh karena kondisi penyimpanan (misalnya posisi box kemiringan)\n(2) Meskipun tidak ada batas indikasi garis, jika box memiliki tutup untuk mencegah part tercampur maka evaluasinya \"OK\"",
            ],
            8 => [
                'checkpoint' => 'Part yang jatuh segera ditempatkan di tempat bagian yang jatuh dan dihandle oleh supervisor.',
                'kriteria_judgement' => "1) Ada tempat penyimpanan part yang jatuh di satu lokasi atau lebih\n2) Ada Frekuensi hanlde part yang jatuh, jika tidak ada maka \"NG\"\n3) Ada aturan tentang part yang jatuh dan di ikuti",
            ],
            9 => [
                'checkpoint' => 'Safety cover mesin welding ultrasonik ( dipasang dengan benar dan terdapat tindakan pencegahan terhadap kecelakaan kerja',
                'kriteria_judgement' => '-',
            ],
            10 => [
                'checkpoint' => 'Penyimpanan terminal individual tahan debu.',
                'kriteria_judgement' => "(1) Cover tahan debu tidak menutup seluruh area penyimpanan terminal. Evaluasinya \"NG\" jika tidak menutupi keseluruhan\n(2) Meskipun terdapat cover tahan debu pada rak, akan dievaluasi \"NG\" apabila ujung terminal ter expose",
            ],
            11 => [
                'checkpoint' => 'Part yang digunakan secara berbeda tergantung pada nomor assy produksi diberi identifikasi penggunaan',
                'kriteria_judgement' => '"OK" jika ada tampilan list penggunaan, tutup/cover, dan LED navigasi dll.',
            ],
            12 => [
                'checkpoint' => 'Jika ada risiko wire atau terminal bersentuhan dengan lantai, pasang gutter untuk memastikan wire atau terminal tidak menyentuh lantai',
                'kriteria_judgement' => "(1) Tidak boleh menyentuh lantai\n(2) Jika tidak ada gutter maka wire harus berada 20cm dari lantai",
            ],
            13 => [
                'checkpoint' => 'Ada aturan 5W1H yang tertulis/terdokumentasi untuk membersihkan wadah chip stripping dan wadah tidak over/tumpah *Frekuensi dan metode pembuangan ditentukan oleh masing-masing pabrik',
                'kriteria_judgement' => 'Tidak boleh meluap',
            ],
            14 => [
                'checkpoint' => 'Perangkat peringatan abnormal dipasang dan berfungsi dengan baik. Peringatan harus divisualisasikan dan sebaiknya memiliki alarm yang memberi sinyal kelainan. (contoh: andon)',
                'kriteria_judgement' => "1) Jika terjadi kelainan dapat divisualisasikan dengan bendera dll.\n(2) Evaluasi akan \"NG\" apabila hanya dengan suara atau audio saja",
            ],
        ];

        $heatShrinkData = [
            1 => [
                'checkpoint' => 'Dokumen seperti Point Kerja ditampilkan dan tidak rusak (tidak terkelupas, sobek atau tidak bisa dibaca)',
                'kriteria_judgement' => '-',
            ],
            2 => [
                'checkpoint' => 'Tempat Lokasi barang-barang yang diperlukan ditunjukkan dan barang-barang ditempatkan sesuai. *Item yang tidak berada di lokasi yang ditentukan tidak dapat diterima.',
                'kriteria_judgement' => "1) Meskipun menggunakan kanban dan karet gelang penempatan sementara (Choioki) evaluasinya \"NG\"\n(2) Evaluasi akan dinyatakan \"OK\" apabila menaruh barang pribadi di tempat yang sudah ditentukan",
            ],
            3 => [
                'checkpoint' => 'Terdapat indikasi proses "Before" dan "After" dan produk diklasifikasikan dengan baik sesuai indikasi',
                'kriteria_judgement' => '-',
            ],
            4 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan dan tidak kotor. * Frekuensi ditentukan oleh setiap pabrik',
                'kriteria_judgement' => 'Konfirmasi frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum.',
            ],
        ];

        $manualCrimpingDrumData = [
            1 => [
                'checkpoint' => 'Nomor part ditunjukkan di lokasi penyimpanan dan sesuai dengan barang sebenarnya (P/no part box) Identitasnya terletak di bagian atas, Jika ingin ditampilkan di bagian bawah gunakan tanda panah sebagai penunjuknya, namun tanda panah tidak diperlukan jika lokasi penyimpanan hanya memiliki satu layer',
                'kriteria_judgement' => "(1) Ada kotak untuk part yang jatuh di satu tempat atau lebih\n(2) SPV menentukan frekuensi untuk produk yang jatuh\n(3) Ada aturan tertulis dan produk yang jatuh segera dibuang",
            ],
            2 => [
                'checkpoint' => 'Ada aturan tertulis untuk "First in dan First out" dan dipatuhi.',
                'kriteria_judgement' => 'N/A jika penyimpanan dikelola oleh sistem manajemen (SAP, dll.)',
            ],
            3 => [
                'checkpoint' => 'Part box diisi secukupnya untuk mencegahnya meluap/tumpah *Pertimbangkan cara mengisi part agar tidak terjatuh.',
                'kriteria_judgement' => "1) Sekalipun ada indikasi garis (batas garis), evaluasi \"NG\" jika ada kemungkinan jatuh karena kondisi penyimpanan (misalnya ada kemiringan)\n2) Sekalipun tidak ada garis indikasi, evaluasinya \"OK\" jika ada sistem untuk mencegah tercampurnya part (misalnya, memasang penutup untuk menutup kotak, dll.)",
            ],
            4 => [
                'checkpoint' => 'Part yang jatuh segera dimasukkan ke dalam wadah yang telah ditentukan dan dihandle oleh supervisor.',
                'kriteria_judgement' => "(1) Ada kotak untuk part yang jatuh di satu tempat atau lebih\n(2) SPV menentukan frekuensi untuk produk yang jatuh\n(3) Ada aturan tertulis dan produk yang jatuh segera dibuang",
            ],
            5 => [
                'checkpoint' => 'Lokasi penyimpanan barang yang diperlukan seperti tape, jig tool, dan lembar catatan ditunjukkan dan barang-barang ditempatkan dengan sesuai. *Item yang tidak berada di lokasi yang ditentukan NG (Tool jig dan lembar catatan memiliki tempat penyimpanan sendiri)',
                'kriteria_judgement' => "1) Meskipun menggunakan kanban dan karet gelang penempatan sementara (Choioki) evaluasinya \"NG\"\n(2) Evaluasi akan dinyatakan \"OK\" apabila menaruh barang pribadi di tempat yang sudah ditentukan",
            ],
            6 => [
                'checkpoint' => 'Ada tempat penyimpanan wire cutting dan crimping. Jika wire dan terminal menyentuh lantai, pasang gutter agar tidak menyentuh lantai',
                'kriteria_judgement' => "(1) \"NG\" apabila produk menyentuh lantai\n(2) Jika tidak ada gutter ujung wire harus berada 20cm dari lantai",
            ],
            7 => [
                'checkpoint' => 'Mesin press yang memerlukan kalibrasi telah di cek sesuai undang-undang dan sesuai batas waktu yang ditentukan (menampilkan jadwal inspeksi berikutnya)',
                'kriteria_judgement' => '-',
            ],
            8 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan equipment crimping (mesin crimping, mesin press, insert rubber, mesin trimming, gutter, dll.) dan bersih. *Frekuensi ditentukan oleh masing-masing pabrik.',
                'kriteria_judgement' => 'Konfirmasi frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum.',
            ],
            9 => [
                'checkpoint' => 'Mesin crimping manual dilengkapi dengan carrier cutter atau carrier guide dan wadah yang dipasang pada mesin untuk mencegah nya berserakan',
                'kriteria_judgement' => '-',
            ],
            10 => [
                'checkpoint' => 'Ada aturan pembersihan 5W1H yang terdokumentasi untuk menghilangkan sisa chip dan carrier pada equipment dan tidak kotor. ※Frekuensi ditentukan oleh masing-masing pabrik.',
                'kriteria_judgement' => '-',
            ],
        ];

        $manualCrimpingCassetteData = [
            1 => [
                'checkpoint' => 'Safety Cover dipasang pada APPL (aplikator) Untuk mesin manual crimping dan digunakan sesuai instruksi. Dan terdapat tindakan penceghan untuk kecelakaan kerja',
                'kriteria_judgement' => 'Safety Cover terpasang (Bentuk safety cover tidak dipermasalahkan)',
            ],
            2 => [
                'checkpoint' => 'Setiap item yang memerlukan kalibrasi (equipment) ditunjukkan dengan tanggal kalibrasi dan valid.',
                'kriteria_judgement' => 'Cek kalibrasi equipment, evaluasi "OK" jika belum kadaluarsa',
            ],
            3 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan PI (aplikator), dan tidak ada chip terminal yang dapat mempengaruhi crimping. ※Frekuensi ditentukan oleh masing-masing pabrik',
                'kriteria_judgement' => '-',
            ],
            4 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan carrier chip dan wadah penampungnya tidak penuh *Frekuensi ditentukan oleh tiap pabrik',
                'kriteria_judgement' => 'Tidak meluap dan menumpuk',
            ],
            5 => [
                'checkpoint' => 'Terdapat aturan 5W1H yang terdokumentasi untuk membersihkan area yang bersentuhan dengan terminal dan tidak kotor. ※Frekuensi ditentukan oleh masing-masing pabrik',
                'kriteria_judgement' => 'Konfirmasi frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum',
            ],
            6 => [
                'checkpoint' => 'Terdapat Lembar Spesifikasi Crimp Terminal (TCSS) yang telah disiapkan. Dilarang menggunakan dokumen selain TCSS atau standar crimping lainnya.',
                'kriteria_judgement' => '-',
            ],
        ];

        $penyimpananTerminalCuttingData = [
            1 => [
                'checkpoint' => 'Cover tahan debu terpasang pada rak penyimpanan terminal dan tertutup.',
                'kriteria_judgement' => "(1) Evaluasi \"OK\" apabila masuk kedalam kardus\n(2) Evaluasi dinyatakan \"NG\" apabila terklupas dan keluar, meskipun terdapat cover pada terminal tersebut.",
            ],
            2 => [
                'checkpoint' => 'Terdapat tampilan nomor assy di lokasi penyimpanan, dan tampilan nomor assy serta actual produk (nomor produk reel termianl) sesuai. Identitasnya ada di atas. Jika ditampilkan di bawah, diperlukan tanda panah. Namun, jika tempat penyimpanannya satu tingkat, tidak diperlukan panah.',
                'kriteria_judgement' => "(1) Tidak perlu menampilkan identitas alamat/adress\n(2) Evaluasi dinyatakan \"OK\" apabila penyimpanan sementara memiliki identitas\n(3) N/A jika penyimpanan dikelola oleh sistem manajemen (SAP, dll.)",
            ],
            3 => [
                'checkpoint' => 'Nomor assy ditunjukkan pada setiap reel terminal. (NG jika hanya ada label Kanban)',
                'kriteria_judgement' => 'Beberapa terminal yang dibeli di luar Yazaki tidak memiliki nomor part instruksi produksi (nomor part Yazaki) untuk setiap gulungan, evaluasi "OK" jika nomor part instruksi produksi dimasukkan.',
            ],
            4 => [
                'checkpoint' => 'Side feed terminal ditempatkan secara horizontal dengan terminal carrier menghadap ke bawah. *Jika tidak dapat dicegah untuk menempatkannya secara vertikal, simpanlah di tempat yang jauh untuk mencegah deformasi. *Lihat lembar poin 5S, PMD-A00-MOT-5S-005-P02 untuk penjelasan lebih detail',
                'kriteria_judgement' => 'Terminal side-by-side ditempatkan secara vertikal sedemikian rupa sehingga pinggirannya tidak berubah bentuk/deform',
            ],
            5 => [
                'checkpoint' => 'End Feed terminal diletakkan secara vertical (karton menghadap secara vertikal) (NA untuk bahan non kardus)',
                'kriteria_judgement' => 'Belum ada aturan mengenai tata cara penempatan gulungan karton diagonal',
            ],
            6 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan dan tetap bersih. *Frekuensi ditentukan oleh masing-masing pabrik.',
                'kriteria_judgement' => 'Konfirmasi frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum',
            ],
            7 => [
                'checkpoint' => 'Terminal tidak menonjol keluar dari reel *Terminal yang saat ini digunakan dipasang pada sisi carrier dengan crimping/pengencang',
                'kriteria_judgement' => "(1) \"OK\" meskipun terminalnya diproses dengan kertas penggulung terminal\n(2) Terminal yang menonjol walaupun hanya 50cm evaluasinya \"NG\"",
            ],
            8 => [
                'checkpoint' => 'Terminal reel tidak ditempatkan langsung di lantai.',
                'kriteria_judgement' => "(1) Evaluasi dinyatakan \"NG\" apabila meletakkan kardus diatas lantai.\n(2) Evaluasi dinyatakan \"OK\" apabila penempatan lebih dari 3 cm diatas lantai.",
            ],
            9 => [
                'checkpoint' => 'Ada aturan tertulis untuk "First in dan First out" dan dipatuhi.',
                'kriteria_judgement' => 'N/A jika penyimpanan dikelola oleh sistem manajemen (SAP, dll.)',
            ],
        ];

        $penyimpananWireCuttingCassetteData = [
            1 => [
                'checkpoint' => 'Jumlah lapisan bundel wire adalah lima atau kurang.',
                'kriteria_judgement' => 'Evaluasi kondisi penyimpanan di rak setelah menerima wire wire, ketinggian wire yang ditumpuk di palet berada dalam standar pabrik evaluasinya "OK".',
            ],
            2 => [
                'checkpoint' => 'Nomor assy (jenis, ukuran, warna) tertera di lokasi penyimpanan dan sesuai dengan actual produk (label wire) Identitasnya terletak di bagian atas, Jika ingin ditampilkan di bagian bawah gunakan tanda panah sebagai penunjuknya, namun tanda panah tidak diperlukan jika penyimpanan hanya mempunyai satu tingkat/layer',
                'kriteria_judgement' => "(1) Free lokasi tidak termasuk (hanya di area yang di kontrol saja)\n(2) Penyimpanan sementara antara penerimaan dan warehouse tidak termasuk\n(3) Temporary storage memiliki identitas",
            ],
            3 => [
                'checkpoint' => 'Jangan letakkan wire yang memiliki ukuran dan variasi berbeda dengan warna yang sama (similar) secara berdekatan atau harap buatkan tanda/sign',
                'kriteria_judgement' => "(1) Tidak perlu menampilkan identitas alamat/adress\n(2) Evaluasi dinyatakan \"OK\" apabila penyimpanan sementara memiliki identitas\n(3) N/A jika penyimpanan dikelola oleh sistem manajemen (SAP, dll.)",
            ],
            4 => [
                'checkpoint' => 'Ada aturan tertulis untuk "First in dan First out" dan dipatuhi.',
                'kriteria_judgement' => 'N/A jika penyimpanan dikelola oleh sistem manajemen (SAP, dll.)',
            ],
            5 => [
                'checkpoint' => 'Bundel wire yang tidak ditempatkan pada cassete reel ditempatkan secara horizontal (datar)',
                'kriteria_judgement' => 'Walaupun disimpan di cassete reel, evaluasinya "OK" jika item kontrol dipatuhi',
            ],
            6 => [
                'checkpoint' => 'Bundel wire tidak langsung diletakkan di lantai atau papan kayu (rak, palet, dll) untuk mencegah goresan.',
                'kriteria_judgement' => "(1) Evaluasi \"OK\" jika berada 3cm di atas lantai\n(2) Evaluasi \"NG\" apabila trolley dan rak terbuat dari bahan kayu.\n3) Jika menggunakan papan untuk memindahkan wire, Jangan gunakan kayu pada permukaan yang bersentuhan dengan wire",
            ],
            7 => [
                'checkpoint' => 'Label wire ditempelkan pada bundel wire, cassete reel, dan wire reel (NG jika hanya tag kanban yang digunakan.)',
                'kriteria_judgement' => '-',
            ],
            8 => [
                'checkpoint' => 'Masukkan ujung cassete reel dan wire reel melalui lubang samping. (Panjang wire yang menonjol tidak boleh lagi 150mm) Untuk wire tebal, kencangkan wire sedemikian rupa untuk mencegah wire lepas, terbelit, dan tergores.',
                'kriteria_judgement' => 'Apabila sudah terikat, meskipun tidak dilewatkan melalui lubang samping akan dievaluasi "OK" (Namun, wire tidak boleh terlalu panjang)',
            ],
            9 => [
                'checkpoint' => 'Permukaan luar (body, cover) cassete reel dan wire reel, mungkin bersentuhan dengan wire, dan tidak rusak (Memodifikasi Cassete reel NG)',
                'kriteria_judgement' => '-',
            ],
            10 => [
                'checkpoint' => 'Ada aturan 5W1H yang tertulis untuk membersihkan dan tidak kotor. *Frekuensi ditentukan oleh tiap pabrik',
                'kriteria_judgement' => 'Konfirmasi frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum',
            ],
        ];

        $penyimpananAPPLData = [
            1 => [
                'checkpoint' => 'Crimper wire dan anvil dilindungi oleh spacer untuk mencegahnya bersentuhan. (Dilarang menggunakan sekrup pengencang ram)',
                'kriteria_judgement' => "(1) \"OK\" jika APPL disimpan dengan spacer untuk transportasi.\n(2) shank di setting di rak evaluasinya \"OK\"\n(3) \"NG\" jika spacer tidak digunakan saat APPL dipindahkan.",
            ],
            2 => [
                'checkpoint' => 'APPL (aplikator) memiliki indikasi nomor assy dari perintah kerja. Nomor assy perwakilan juga OK untuk APPL common',
                'kriteria_judgement' => "(1) \"NG\" jika nomor part Yazaki tidak dicantumkan.\n(2) \"OK\" jika nomor part hanya ditunjukkan pada name plate (hanya nomor part Yazaki).\n(3) \"NG\" jika APPL tidak memiliki nomor part tertentu saat dimodifikasi",
            ],
            3 => [
                'checkpoint' => 'Nomor part tertera di lokasi penyimpanan dan sesuai dengan actual produk (APPL p/no) (Jika sedang diinspect, status juga harus ditunjukkan) Penunjuknya terletak di bagian atas, jika ingin ditampilkan di bagian bawah gunakan tanda panah sebagai penunjuknya, namun tanda panah tidak diperlukan jika tempat penyimpanan hanya 1 tingkat',
                'kriteria_judgement' => "(1) \"OK\" jika indikasi dalam proses inspect ditampilkan pada posisi (bawah) di mana APPL ditempatkan\n(2) \"NG\" jika tidak ada identitas meskipun dikelola dengan barcode",
            ],
            4 => [
                'checkpoint' => 'Cover tahan debu dipasang di atas rak penyimpanan APPL (aplikator) atau di atas APPL (aplikator) itu sendiri. *Perubahan APPL yang dipasang pada equipment tidak termasuk',
                'kriteria_judgement' => '-',
            ],
            5 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan APPL. Tidak ada carrier scrap dan chip terminal yang dapat mempengaruhi crimping. ※Aplikator harus bersih',
                'kriteria_judgement' => '-',
            ],
        ];

        $mesinCuttingCrimpingData = [
            1 => [
                'checkpoint' => 'Ada aturan pemisahan untuk wire scrap dan terminal. petunjuk disediakan dan wire serta terminal scrap disortir sesuai dengan aturan.',
                'kriteria_judgement' => '-',
            ],
            2 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan wadah chip stripping dan chip carrier terminal. Wadah tidak meluap. ※Jumlah diperiksa di setiap pabrik',
                'kriteria_judgement' => '"OK" jika actualnya tidak meluap',
            ],
            3 => [
                'checkpoint' => 'Lokasi item yang diperlukan ditempatkan, barang yang ditempatkan seperti yang ditunjukkan. *Item yang tidak ada lokasinya NG',
                'kriteria_judgement' => "1) Meskipun menggunakan kanban dan karet gelang penempatan sementara (Choioki) evaluasinya \"NG\"\n(2) Evaluasi akan dinyatakan \"OK\" apabila menaruh barang pribadi di tempat yang sudah ditentukan",
            ],
            4 => [
                'checkpoint' => 'Terdapat aturan 5W1H yang terdokumentasi untuk membersihkan area yang bersentuhan dengan terminal dan tidak kotor. ※Frekuensi ditentukan oleh masing-masing pabrik',
                'kriteria_judgement' => 'Konfirmasi frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum',
            ],
            5 => [
                'checkpoint' => 'Ada tempat untuk cutting dan crimping wire, serta wire dan terminal tidak menyentuh lantai',
                'kriteria_judgement' => "(1) Produk tidak boleh menyentuh lantai\n(2) Jika tidak menggunakan gutter, ujung wire harus berada 20cm dari permukaan lantai",
            ],
            6 => [
                'checkpoint' => 'Terdapat Lembar Spesifikasi Crimp Terminal (TCSS) yang telah disiapkan. Dilarang menggunakan dokumen selain TCSS atau standar crimping lainnya.',
                'kriteria_judgement' => '-',
            ],
            7 => [
                'checkpoint' => 'Tanggal kalibrasi tertera pada peralatan ukur dan valid.',
                'kriteria_judgement' => 'Cek equipment yang memerlukan kalibrasi, dan jika dalam tanggal valid, evaluasi "OK"',
            ],
        ];

        $laTerminalWaterproofData = [
            1 => [
                'checkpoint' => 'Inspectiont point ditampilkan tidak rusak (tidak terkelupas, sobek, pudar)',
                'kriteria_judgement' => '-',
            ],
            2 => [
                'checkpoint' => 'Lokasi item yang diperlukan ditempatkan, barang yang ditempatkan seperti yang ditunjukkan. *Item yang tidak ada lokasinya NG',
                'kriteria_judgement' => "1) Meskipun menggunakan kanban dan karet gelang penempatan sementara (Choioki) evaluasinya \"NG\"\n(2) Evaluasi akan dinyatakan \"OK\" apabila menaruh barang pribadi di tempat yang sudah ditentukan",
            ],
            3 => [
                'checkpoint' => 'Ada indikasi "before" dan "after" inspection dan produk diklasifikasikan dengan baik.',
                'kriteria_judgement' => "(1) Before dan After inspection di klasifikasikan\n(2) Evaluasi dinyatakan \"NG\" apabila before dan after inspeksi tidak ada identitas",
            ],
            4 => [
                'checkpoint' => 'Proses inspeksi memiliki kecerahan yang cukup (lebih dari 500 lux) *Ikuti hukum dan peraturan di setiap negara',
                'kriteria_judgement' => 'Evaluasi dinyatakan "OK" apabila pencahayaan diukur dengan illuminance dan nilai di atas rata-rata',
            ],
            5 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan dan tidak kotor. *Frekuensi ditentukan oleh setiap pabrik',
                'kriteria_judgement' => 'Konfirmasi frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum',
            ],
        ];

        $diSekitarMesinOtomatisData = [
            1 => [
                'checkpoint' => 'Terdapat aturan pemisahan untuk wire dan terminal yang Loss wire dan terminal loss disortir sesuai dengan aturan.',
                'kriteria_judgement' => '-',
            ],
            2 => [
                'checkpoint' => 'Ada aturan 5W1H tentang pembersihan rak wire dan tidak kotor *Frekuensi ditentukan oleh masing-masing pabrik.',
                'kriteria_judgement' => 'Konfirmasi frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum',
            ],
            3 => [
                'checkpoint' => 'Rubber seal yang tidak terpakai lindungi dari debu saat tidak digunakan. (Misalnya : Simpan di rak, cover, di simpan di tas)',
                'kriteria_judgement' => "(1) Rubber shield yang tidak digunakan menggunakan dilindungi dari debu\n(2) Konfirmasi frekuensi penggunaan rubber shield \"NG\" apabila tidak menggunakan cover tahan debu\n(3) Evaluasi dinyatakan \"NG\" apabila pada saat istirahat rubber shield dikeluarkan dan dibiarkan begitu saja",
            ],
            4 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan chip stripping, dan ship terminal carrier. *Frekuensi dan Metode pembuangan ditentukan oleh tiap pabrik',
                'kriteria_judgement' => 'Evaluasi "OK" jika tidak meluap',
            ],
            5 => [
                'checkpoint' => 'Lokasi item yang diperlukan ditempatkan, barang yang ditempatkan seperti yang ditunjukkan. *Item yang tidak ada lokasinya NG',
                'kriteria_judgement' => "1) Meskipun menggunakan kanban dan karet gelang penempatan sementara (Choioki) evaluasinya \"NG\"\n(2) Evaluasi akan dinyatakan \"OK\" apabila menaruh barang pribadi di tempat yang sudah ditentukan",
            ],
            6 => [
                'checkpoint' => 'Cover tahan debu digunakan pada rak dan ditutup dengan benar (Cover Juga harus ditutup selama proses)',
                'kriteria_judgement' => "(1) \"NG\" jika penutup tahan debu tidak menutupi seluruh area penyimpanan terminal\n(2) Apabila penutup tahan debu telah digunakan namun jika terminal terekspose keluar maka \"NG\"",
            ],
            7 => [
                'checkpoint' => 'Terdapat aturan 5W1H yang terdokumentasi untuk membersihkan area yang bersentuhan dengan terminal dan tidak kotor. *Frekuensi ditentukan oleh masing-masing pabrik',
                'kriteria_judgement' => 'Konfirmasi frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum',
            ],
            8 => [
                'checkpoint' => 'Jika wire dan terminal ada kemungkinan bersentuhan dengan lantai, pasang gutter untuk memastikan wire atau terminal tidak menyentuh lantai.',
                'kriteria_judgement' => "(1) Produk tidak boleh menyentuh lantai\n(2) Jika tidak menggunakan gutter, ujung wire harus berada 20cm dari permukaan lantai",
            ],
            9 => [
                'checkpoint' => 'Part jatuh langsung ditempatkan di wadah part khusus Jatuh dan dihandle oleh supervisor.',
                'kriteria_judgement' => "(1) Tempat untuk part yang jatuh di pasang di satu titik atau lebih\n(2) Evaluasi dinyatakan \"NG\" apabila SPV tidak menghandle frekuensi part yang jatuh",
            ],
            10 => [
                'checkpoint' => 'Kaca pembesar dipasang untuk pemeriksaan middle inspection dan selama proses.',
                'kriteria_judgement' => '-',
            ],
            11 => [
                'checkpoint' => 'Jika sao temporary ditempatkan pada middle inspection harus ada aturan FIFO yang terdokumentasi dan dipatuhi dengan ketat.',
                'kriteria_judgement' => '-',
            ],
            12 => [
                'checkpoint' => 'Tanggal kalibrasi tertera pada peralatan ukur dan valid.',
                'kriteria_judgement' => 'Cek equipment yang memerlukan kalibrasi, dan jika dalam tanggal valid, evaluasi "OK"',
            ],
        ];

        $tiDiSekitarMesinOtomatisData = [
            1 => [
                'checkpoint' => 'Terdapat contoh atau gambar yang menunjukkan arah konektor yang akan disetel ke TI.',
                'kriteria_judgement' => '-',
            ],
            2 => [
                'checkpoint' => 'Lokasi item yang diperlukan ditempatkan, barang yang ditempatkan seperti yang ditunjukkan. *Item yang tidak ada lokasinya NG',
                'kriteria_judgement' => "1) Meskipun menggunakan kanban dan karet gelang penempatan sementara (Choioki) evaluasinya \"NG\"\n(2) Evaluasi akan dinyatakan \"OK\" apabila menaruh barang pribadi di tempat yang sudah ditentukan",
            ],
            3 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan dan tidak kotor *Frekuensi ditentukan oleh setiap pabrik',
                'kriteria_judgement' => 'Konfirmasi frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum',
            ],
            4 => [
                'checkpoint' => 'Jika wire, terminal dan conector ada kemungkinan bersentuhan dengan lantai,pasang gutter, agar terminalnya tidak menentuh lantai. (Sama halnya dengan pipa wire)',
                'kriteria_judgement' => "(1) Produk tidak boleh menyentuh lantai\n(2) Jika tidak menggunakan gutter, ujung wire harus berada 20cm dari permukaan lantai",
            ],
            5 => [
                'checkpoint' => 'Part yang terjatuh segera dimasukkan ke dalam wadah yang telah ditentukan dan dihandle oleh supervisor. Rubber seal yang terjatuh segera dibuang.',
                'kriteria_judgement' => "(1) Ada satu atau lebih tempat untuk handle part yang jatuh dan part yang jatuh langsung dimasukkan ke dalam kotak segera\n(2) Semua part (termasuk COT-Tube) Jika tidak di handle di box part yang Jatuh maka \"NG\"\n(3) Pembuangan part yang Jatuh memiliki role dan diikuti\n(4) Evaluasi dinyatakan \"NG\" apabila SPV tidak menghandle item yang jatuh",
            ],
            6 => [
                'checkpoint' => 'Nomor part ditunjukkan di lokasi penyimpanan dan cocok dengan nomor part pada actual box part. Penunjuknya terletak di bagian atas, Jika ingin ditampilkan di bagian bawah gunakan tanda panah sebagai penunjuknya, namun tanda panah tidak diperlukan jika rak hanya 1 tingkat',
                'kriteria_judgement' => 'Jika part box sudah ditentukan, gunakan hanya satu identitas saja (Tidak perlu menampilkan identitas pada keduanya, part box dan tempat meletakkan part box)',
            ],
            7 => [
                'checkpoint' => 'Jika SAO ditempatkan sementara setelah insert terminal untuk menunggu proses selanjutnya, ada aturan tertulis "FIFO" dan diikuti.',
                'kriteria_judgement' => '-',
            ],
            8 => [
                'checkpoint' => 'Ada aturan tertulis tentang cara membersihkan tempat SAO ditempatkan sementara setelah pemasangan terminal dengan instruksi berdasarkan 5W1H. Actual nya harus bersih.',
                'kriteria_judgement' => 'Konfirmasi frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum',
            ],
        ];

        $insertRubberPlugData = [
            1 => [
                'checkpoint' => 'Rubber atau tube tidak boleh berserakan di meja kerja (gunakan baki atau tas). JANGAN letakkan rubber pada permukaan bertekstur seperti handuk.',
                'kriteria_judgement' => 'N/A jika silikon dibersihkan dengan tisu.',
            ],
            2 => [
                'checkpoint' => 'Nomor part ditunjukkan di lokasi penyimpanan dan cocok dengan nomor part pada actual box part. Penunjuknya terletak di bagian atas, Jika ingin ditampilkan di bagian bawah gunakan tanda panah sebagai penunjuknya, namun tanda panah tidak diperlukan jika rak hanya 1 tingkat',
                'kriteria_judgement' => "Konfirmasi actual product\n(1) Evaluasi dinyatakan \"NG\" apabila identitas dan actual product tidak sama\n(2) \"NG\" apabila pada kotak penyimpanan rubber shield tidak tertera identitas\n(3) Pada penyimpanan sementara, evaluasi dinyatakan \"OK\" apabila di tempat penyimpanan sementara ada tampilan identitasnya",
            ],
            3 => [
                'checkpoint' => 'Rubber plug terlindungi dari debu saat tidak digunakan. (Part disimpan di dalam laci, tertutup atau kantong paketnya tertutup)',
                'kriteria_judgement' => "(1) Rubber shield yang tidak digunakan menggunakan dilindungi dari debu\n(2) Konfirmasi frekuensi penggunaan rubber shield \"NG\" apabila tidak menggunakan cover tahan debu\n(3) Evaluasi dinyatakan \"NG\" apabila pada saat istirahat rubber shield dikeluarkan dan dibiarkan begitu saja",
            ],
            4 => [
                'checkpoint' => 'Ada aturan tertulis "First In First Out". Tas baru akan terbuka setelah bagian lama selesai.',
                'kriteria_judgement' => "(1) Cek similar part\n(2) Jika part di supply dalam kotak atau plastic ke produksi, sampel identitas tidak diperlukan\n(\"NG\" jika part tidak diperiksa berdasarkan nomor part (barcode, QR Code) dan jika bagian similar tidak ada identitas apapun maka evaluasinya \"NG\")",
            ],
            5 => [
                'checkpoint' => 'Similar produk ditandai dengan jelas perbedaan pada bagian yang serupa, dan di check serta didistribusikan. *Jika sulit menilai berdasarkan produk atau actual foto, harap nyatakan perbedaannya yang spesifik',
                'kriteria_judgement' => "(1) Cek conector dan clip yang memiliki similar part\n(2) Jika part di supply dalam kotak atau plastic ke produksi, sampel identitas tidak diperlukan\n(\"NG\" jika part tidak diperiksa berdasarkan nomor part (barcode, QR Code) dan jika bagian similar tidak ada identitas apapun maka evaluasinya \"NG\")",
            ],
            6 => [
                'checkpoint' => 'Bagian yang jatuh segera dimasukkan ke dalam wadah khusus dan diperiksa aturan cara meenanganninya berdasarkan instruksi 5W1H. * rubber plug yang jatuh tidak boleh digunakan lagi.',
                'kriteria_judgement' => "(1) Ada satu atau lebih tempat untuk handle part yang jatuh dan part yang jatuh langsung dimasukkan ke dalam kotak segera\n(2) Frekuensi handle part yang jatuh dikelola oleh SPV",
            ],
        ];

        $wireHangerTrollyData = [
            1 => [
                'checkpoint' => 'Jika ada risiko wire atau terminal bersentuhan dengan lantai, pasang gutter untuk memastikan wire atau terminal tidak menyentuh lantai',
                'kriteria_judgement' => "(1) Produk tidak boleh menyentuh lantai\n(2) Jika tidak menggunakan gutter, ujung wire harus berada 20cm dari permukaan lantai\n(3) Jika tidak ada tray untuk mencegah wire menyentuh lantai,evaluasinya \"OK\" jika wire digulung untuk mecegahnya menyentuh lantai",
            ],
            2 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan dan tidak kotor. * Frekuensi ditentukan oleh setiap pabrik',
                'kriteria_judgement' => 'Konfirmasi frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum',
            ],
            4 => [
                'checkpoint' => 'Pasang kanban atau perintah kerja ke setiap bundel wire',
                'kriteria_judgement' => '-',
            ],
        ];

        $inspecPreAssyData = [
            1 => [
                'checkpoint' => 'Tempat penyimpanan dokumen seperti lembar catatan inspeksi ditunjukkan dan item ditempatkan sesuai.',
                'kriteria_judgement' => 'Penempatan sementara (Choioki) "NG" bahkan selama proses',
            ],
            2 => [
                'checkpoint' => 'Penerangan disediakan secukupnya pada proses inspeksi (lebih dari 500 lux) *Mematuhi peraturan masing-masing negara.',
                'kriteria_judgement' => 'Evaluasi dinyatakan "OK" apabila pencahayaan diukur dengan illuminance dan nilai di atas rata-rata',
            ],
            3 => [
                'checkpoint' => 'Terdapat Lembar Spesifikasi Crimp Terminal (TCSS) yang telah disiapkan. JANGAN gunakan spesifikasi yang berbeda dari TCSS atau standar crimping yang dikeluarkan secara resmi.',
                'kriteria_judgement' => '-',
            ],
            4 => [
                'checkpoint' => 'Tanggal kalibrasi tertera pada peralatan ukur dan valid.',
                'kriteria_judgement' => 'Cek kalibrasi equipment, evaluasi "OK" jika belum kadaluarsa',
            ],
            5 => [
                'checkpoint' => 'Terdapat indikasi proses "Before inspection" dan "After inspection" dan produk diklasifikasikan',
                'kriteria_judgement' => "(1) Before dan after inspection di klasifikasikan\n(2) \"NG\" apabila identitas before dan after inspect tidak ada",
            ],
            6 => [
                'checkpoint' => 'Ada tempat penyimpanan khusus untuk produk defect dan label defect dengan informasi yang diperlukan terlampir.',
                'kriteria_judgement' => "1) Ada tempat penyimpanan untuk produk defect dan label defect terpasang\n(2) \"NG\" apabila tidak ada tempat penyimpanan produk defect",
            ],
            7 => [
                'checkpoint' => 'Perangkat peringatan abnormal dipasang dan berfungsi dengan baik. Peringatan harus divisualisasikan dan sebaiknya memiliki alarm yang memberi sinyal kelainan. (contoh: andon)',
                'kriteria_judgement' => "(1) Selain perangkat, evaluasi akan \"OK\" jika abnormal divisualisasikan dengan bendera, dll\n(2) Evaluasi akan \"NG\" apabila hanya dengan suara atau audio saja",
            ],
        ];

        $wireTwistData = [
            1 => [
                'checkpoint' => 'Dokumen seperti point kerja ditampilkan dan tidak rusak (tidak terkelupas, sobek atau tinta pudar, tidak dapat dibaca).',
                'kriteria_judgement' => '-',
            ],
            2 => [
                'checkpoint' => 'Lokasi barang-barang yang diperlukan ditunjukkan dan barang-barang ditempatkan sesuai dengan itu. *Barang-barang yang tidak berada di lokasi yang ditentukan NG',
                'kriteria_judgement' => "1) Meskipun menggunakan kanban dan karet gelang penempatan sementara (Choioki) evaluasinya \"NG\"\n(2) Evaluasi akan dinyatakan \"OK\" apabila menaruh barang pribadi di tempat yang sudah ditentukan",
            ],
            3 => [
                'checkpoint' => 'Terdapat indikasi proses "Before Proses" dan "After Proses" dan produk diklasifikasikan',
                'kriteria_judgement' => '-',
            ],
            4 => [
                'checkpoint' => 'Ujung wire yang diproses (twist wire dengan terminal) memiliki tutup/cap pelindung',
                'kriteria_judgement' => "1) Terminal yang lebih kecil dari 2.3 II (090II), 6.3 (250), terminal relai dan wire yang terminal spesifiknya dicrimping dapat digunakan.\n2) \"OK\" jika cap pelindung terpasang, bundle wire tidak bersentuhan pada hanger dan wire terlindung dari debu.",
            ],
            5 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan dan tidak kotor. *Frekuensi ditentukan oleh setiap pabrik',
                'kriteria_judgement' => 'Konfirmasi frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum',
            ],
        ];

        $shieldWireData = [
            1 => [
                'checkpoint' => 'Dokumen seperti Point Kerja ditampilkan dan tidak rusak (tidak terkelupas, sobek atau tidak bisa dibaca)',
                'kriteria_judgement' => '-',
            ],
            2 => [
                'checkpoint' => 'Lokasi barang-barang yang diperlukan ditunjukkan dan barang-barang ditempatkan sesuai dengan itu. *Barang-barang yang tidak berada di lokasi yang ditentukan NG',
                'kriteria_judgement' => "1) Meskipun menggunakan kanban dan karet gelang penempatan sementara (Choioki) evaluasinya \"NG\"\n(2) Evaluasi akan dinyatakan \"OK\" apabila menaruh barang pribadi di tempat yang sudah ditentukan",
            ],
            3 => [
                'checkpoint' => 'Terdapat indikasi proses "Before Proses" dan "After Proses" dan produk diklasifikasikan',
                'kriteria_judgement' => '-',
            ],
            4 => [
                'checkpoint' => 'Ujung wire yang diproses (shield wire dengan terminal) memiliki tutup/cap pelindung.',
                'kriteria_judgement' => "1) Terminal yang lebih kecil dari 2.3 II (090II), 6.3 (250), terminal relai dan wire yang terminal spesifiknya dicrimping dapat digunakan.\n2) \"OK\" jika cap pelindung terpasang, bundle wire tidak bersentuhan pada hanger dan wire terlindung dari debu.",
            ],
            5 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan dan tidak kotor. *Frekuensi ditentukan oleh setiap pabrik',
                'kriteria_judgement' => 'Konfirmasi frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum',
            ],
        ];

        $wireStoreData = [
            1 => [
                'checkpoint' => 'Jika wire dan terminal ada resiko bersentuhan dengan lantai, pasang gutter agar wire dan terminal tidak menyentuh lantai.',
                'kriteria_judgement' => "(1) Produk tidak boleh menyentuh lantai\n(2) Jika tidak ada gutter, produk harus berada sekitar 20cm dari lantai\n(3) Jika tidak ada tray untuk mencegah wire menyentuh lantai,evaluasinya \"OK\" jika wire digulung untuk mecegahnya menyentuh lantai",
            ],
            2 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan wire store dan tidak kotor. *Frekuensi ditentukan oleh tiap pabrik',
                'kriteria_judgement' => 'Konfirmasi frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum',
            ],
            3 => [
                'checkpoint' => 'Tidak ada bundel wire yang bertumpuk. (Tidak lebih dari 4 tumpukan bundel terminal/wire)',
                'kriteria_judgement' => "(1) Bundle wire ditumpuk hingga 3 tumpukan walaupun besar bundlenya tidak sama evaluasinya \"OK\"\n(2) Alasan menyetting hingga 4 tumpukan ➡ Menumpuk empat atau lebih tumpukan wire kecil dapat menyebabkan terjatuh, dan menyebabkan deformasi terminal atau kerusakan pada wire",
            ],
            4 => [
                'checkpoint' => 'Setiap bundel wire memiliki kanban atau instruksi produksi.',
                'kriteria_judgement' => '-',
            ],
            5 => [
                'checkpoint' => 'Nomor assy (kode wire) ditunjukkan di lokasi penyimpanan dan cocok dengan actual produk (deskripsi wire) Identitasnya terletak di bagian atas, jika ingin ditampilkan di bagian bawah maka gunakan tanda panah sebagai penunjuknya, namun tanda panah tidak diperlukan jika tempat penyimpanan hanya satu tingkat',
                'kriteria_judgement' => 'Kondisi item yang jarang digunakan, akan dinyatakan "NG" apabila tidak ada identitas di tempat penyimpanan sementara',
            ],
            6 => [
                'checkpoint' => 'Ada aturan yang terdokumentasi untuk "First in dan First Out"',
                'kriteria_judgement' => 'N/A jika penyimpanan dikelola oleh sistem manajemen (SAP, dll.)',
            ],
        ];

        $storeShippingJunkanData = [
            1 => [
                'checkpoint' => 'Kapasitas pengisian harus di bawah metal frame (Atau di bawah 80% politainer)',
                'kriteria_judgement' => '-',
            ],
            2 => [
                'checkpoint' => 'Label menggunakan format tertentu yang melekat erat pada wadahnya (politainer) Contoh : Menggunakan holder kartu, taping 2 spot',
                'kriteria_judgement' => 'Label hanya pada satu tempat, evaluasi "NG"',
            ],
            3 => [
                'checkpoint' => 'Ketinggian tumpukan wadah (politainer) harus 120 cm atau kurang (hal yang sama berlaku untuk wadah kosong). EX: PT37 --- tidak lebih dari 6 politainer PT56/78 --- tidak lebih dari 5 politainer',
                'kriteria_judgement' => "1) \"NG\" jika tinggi tumpukan lebih dari 120cm, terlepas dari aturan masing-masing pabrik\n2) \"OK\" jika tinggi tumpukan di bawah 120cm (tidak termasuk tinggi troli)\n3) \"OK\" jika tumpukan politainer kurang dari 120cm dan terakumulasi di troli.",
            ],
            4 => [
                'checkpoint' => 'Wadah (politainer) dengan ukuran berbeda tidak ditumpuk menjadi satu.',
                'kriteria_judgement' => '-',
            ],
            5 => [
                'checkpoint' => 'Gunakan lembaran pelindung seperti plastic gusset, dan harus bersih dan tidak rusak.',
                'kriteria_judgement' => '"NG" jika tidak ada aturan membersihkan atau membuang kantong kotor',
            ],
        ];

        $subAssemblyData = [
            1 => [
                'checkpoint' => 'Nomor assy ditunjukkan pada pipa wire dan sesuai dengan actual produk (deskripsi wire) A: Feeding side: Kode wire dan warna wire B: Sisi operator: lokasi insert dan warna wire. Identitasnya terletak di bagian atas, jika ingin ditampilkan di bagian bawah gunakan tanda panah sebagai penunjuknya, namun tanda panah tidak diperlukan jika hanya satu tingkat',
                'kriteria_judgement' => '-',
            ],
            2 => [
                'checkpoint' => 'Ada aturan yang terdokumentasi untuk "First in dan First Out"',
                'kriteria_judgement' => 'N/A jika penyimpanan dikelola oleh sistem manajemen (SAP, dll.)',
            ],
            3 => [
                'checkpoint' => 'Nomor assy ditunjukkan di lokasi penyimpanan part box, dan cocok dengan nomor assy sebenarnya yang tertera di part box. Indikasinya terletak di bagian atas, jika ingin ditampilkan di bagian bawah maka digunakan tanda panah sebagai penunjuknya, namun tanda panah tidak diperlukan jika hanya satu tingkat',
                'kriteria_judgement' => 'Jika part box sudah ditentukan, gunakan hanya satu identitas saja (Tidak perlu menampilkan identitas pada keduanya, part box dan tempat meletakkan part box)',
            ],
            4 => [
                'checkpoint' => 'Part Box diisi dalam jumlah yang cukup untuk mencegahnya tumpah. *Pertimbangkan cara mengisi part box agar tidak terjatuh.',
                'kriteria_judgement' => "1) Sekalipun ada indikasi garis (batas garis), evaluasi \"NG\" jika ada kemungkinan jatuh karena kondisi penyimpanan (misalnya ada kemiringan)\n2) Sekalipun tidak ada garis indikasi, evaluasinya \"OK\" jika ada sistem untuk mencegah tercampurnya part (misalnya, memasang penutup untuk menutup kotak, dll.)",
            ],
            5 => [
                'checkpoint' => 'Part yang jatuh langsung ditempatkan di wadah part jatuh dan dihandle oleh supervisor.',
                'kriteria_judgement' => "(1) Ada satu atau lebih tempat untuk handle part yang jatuh dan part yang jatuh langsung dimasukkan ke dalam kotak segera\n(2) Semua part (termasuk COT-Tube) jika tidak di handle di box part yang jatuh maka \"NG\"\n(3) Pembuangan part yang jatuh memiliki role dan diikuti\n(4) Evaluasi dinyatakan \"NG\" apabila SPV tidak menghandle item yang jatuh",
            ],
            6 => [
                'checkpoint' => 'Dokumen seperti point kerja ditampilkan (pre insert drawing) tidak rusak (tidak terkelupas, sobek, atau tinta pudar)',
                'kriteria_judgement' => 'Evaluasi kondisi layout seperti drawing, sub drawing, quality point dan instruksi',
            ],
            7 => [
                'checkpoint' => 'Jika wire, terminal atau conector mungkin bersentuhan dengan lantai, pasang gutter. (Sama halnya dengan pipa wire)',
                'kriteria_judgement' => "(1) Produk tidak boleh menyentuh lantai\n(2) Jika tidak ada gutter, produk harus berada sekitar 20cm dari lantai\n(3) Jika tidak ada tray untuk mencegah wire menyentuh lantai,evaluasinya \"OK\" jika wire digulung untuk mecegahnya menyentuh lantai",
            ],
            8 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan tube, gutter, mating part, dan meja kerja agar tetap bersih. *Frekuensi ditentukan oleh masing-masing pabrik.',
                'kriteria_judgement' => "(1) Cek apakah ada alat pembersih untuk pipa wire\n(2) Konfirmasi frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum.",
            ],
        ];

        $wireLayoutTapingData = [
            1 => [
                'checkpoint' => 'Dokumen seperti drawing Jig board, drawing post insert, work point sheet, dan lain-lain tidak rusak (tidak terkelupas, sobek, atau tintanya pudar)',
                'kriteria_judgement' => '-',
            ],
            2 => [
                'checkpoint' => 'No part box ditunjukkan di lokasi penyimpanan dan cocok dengan nomor sebenarnya yang tertera pada part box. Identitasnya terletak di bagian atas, jika ingin ditampilkan di bagian bawah gunakan tanda panah sebagai penunjuknya, namun tanda panah tidak diperlukan jika hanya satu tingkat',
                'kriteria_judgement' => "(1) \"NG\" jika hanya nomor identitas pada part box (tidak ada part number)\n2) \"OK\" jika part dan lokasi pemasangan pada papan jig ditunjukkan dengan angka atau tanda.\n3) Untuk produk bervolume kecil, \"OK\" jika nomor part dan penyimpanan dicantumkan pada part box\n4) Jika part box sudah ditentukan, gunakan hanya satu identitas saja (Tidak perlu menampilkan identitas pada keduanya, part box dan tempat meletakkan part box)",
            ],
            3 => [
                'checkpoint' => 'Box part diisi dalam jumlah yang cukup untuk mencegahnya tumpah. *Pertimbangkan cara mengisi part box agar tidak terjatuh.',
                'kriteria_judgement' => "1) Sekalipun ada indikasi garis (batas garis), evaluasi \"NG\" jika ada kemungkinan jatuh karena kondisi penyimpanan (misalnya ada kemiringan)\n2) Sekalipun tidak ada garis indikasi, evaluasinya \"OK\" jika ada sistem untuk mencegah tercampurnya part (misalnya, memasang penutup untuk menutup kotak, dll.)",
            ],
            4 => [
                'checkpoint' => 'Part yang terjatuh segera dimasukkan ke dalam wadah yang telah ditentukan dan dihandle oleh supervisor. *Untuk pengisian ulang (misalnya Wasurenbou, dll.), lakukan tindakan segera dengan memanggil line leader/atasan. Jika tidak ada part khusus untuk part yang jatuh. (diperlukan aturan yang terdokumentasi/tertulis). *Untuk spesifik part (misalnya COT, selotip, VS sheet, dll.) yang tidak mudah pecah, ternoda, atau salah assembly, operator dapat menilai sendiri apakah bagian tersebut masih dapat digunakan atau tidak (diperlukan aturan yang terdokumentasi/tertulis).',
                'kriteria_judgement' => "1) Ada satu atau lebih tempat untuk handle part yang jatuh dan part yang jatuh langsung dimasukkan ke dalam kotak segera\n2) Semua part termasuk COT-tube, akan dievaluasi \"NG\" apabila belum di isolasi\n3) Untuk tape, tube/COT, dan sheet yang terjatuh dan dapat menimbulkan risiko damage, kotor, atau salah penggunaan, maka yang bersangkutan boleh memeriksa dan menilai part yang jatuh tersebut (sesuai dengan aturan yang ada).\n4) Evaluasi dinyatakan \"NG\" apabila SPV tidak menghandle item yang jatuh",
            ],
            5 => [
                'checkpoint' => 'Lokasi penyimpanan jig tool dan material ditunjukkan dan item ditempatkan sesuai. (misalnya jig tool: Wasurenbou, COT Assembly jig, clip gun, dll. Material: tape termasuk tape yang sedang digunakan, sub assy)',
                'kriteria_judgement' => 'Penempatan sementara (Choioki) untuk tool dan tape "NG" pada saat proses',
            ],
            6 => [
                'checkpoint' => 'Area kerja konveyor ditunjukkan dan diikuti. *Posisi awal dan akhir ditunjukkan dengan jelas.',
                'kriteria_judgement' => "1) \"OK\" jika area proses ditunjukkan proses di dalam area tersebut diamati (Proses yang belum selesai ➡ informasi abnormal).\n(2) Evaluasi dinyatakan \"OK\" apabila ada area overlap",
            ],
            7 => [
                'checkpoint' => 'Perangkat peringatan abnormal dipasang dan berfungsi dengan baik. Peringatan harus divisualisasikan dan sebaiknya memiliki alarm yang memberi sinyal kelainan. (contoh: andon)',
                'kriteria_judgement' => "(1) Selain perangkat, evaluasi akan \"OK\" jika ada abnormal yang divisualisasikan dengan bendera dll\n(2) Evaluasi akan \"NG\" apabila hanya dengan suara atau audio saja",
            ],
            8 => [
                'checkpoint' => 'Part yang digunakan berbeda-beda tergantung pada nomor assy produksi yang ditunjukkan. Dan Penggunaan nya juga ditunjukkan.',
                'kriteria_judgement' => '"OK" jika ada tampilan list penggunaan, tutup/cover, barcode dan LED navigasi dll.',
            ],
            9 => [
                'checkpoint' => 'Ada aturan 5W1H yang terdokumentasi untuk membersihkan Assembly Jig board, gutter, dan conector holder, dan tidak kotor. *Frekuensi ditentukan oleh setiap pabrik',
                'kriteria_judgement' => 'Konfirmasi frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum',
            ],
            10 => [
                'checkpoint' => 'Nomor assy ditunjukkan dan cocok dengan actual produk',
                'kriteria_judgement' => "1) Jika ada kanban pada assembly jig evaluasinya \"OK\"\n2) Jika hanya memproduksi satu nomor part, evaluasinya \"OK\" jika ada identitas besar pada satu tempat\n3) Evaluasi \"OK\" jika indikasi nama digunakan sebagai no part produk, kecuali jika sudah terpasang di conveyor sejak awal",
            ],
            11 => [
                'checkpoint' => 'Ada aturan yang terdokumentasi 5W1H untuk membersihkan bagian dalam konveyor dan tidak kotor. *Frekuensi ditentukan oleh tiap pabrik',
                'kriteria_judgement' => 'Cek aturan yang terdokumentasi dan cek juga waktu implementasi pembersihan jika memungkinkan',
            ],
            12 => [
                'checkpoint' => 'Rentang putaran/pergerakan konveyor bundar mempunyai tanda "Dilarang bekerja di area ini". Dan tidak ada proses yang dilakukan disana.',
                'kriteria_judgement' => 'Pemasangan pagar di area larangan kerja dinilai "OK" meskipun tidak wajib.',
            ],
            13 => [
                'checkpoint' => 'Penanggung jawab pembersihan dan inspeksi harian ditentukan untuk setiap equipment, dan ditampilkan pada setiap jig. *Target: jig board static',
                'kriteria_judgement' => 'Konfirmasi frekuensi kebersihan, waktu, orang, dan metodenya sudah diterapkan apa belum',
            ],
        ];

        foreach ($areas5s as $index => $data) {
            $area = AuditArea::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'category' => '5s_standard',
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'icon_svg' => $data['icon_svg'],
                    'sort_order' => $index + 1,
                ]
            );

            if (isset($data['processes'])) {
                foreach ($data['processes'] as $pData) {
                    $cp = $pData['checkpoint'] ?? null;
                    $kj = $pData['kriteria_judgement'] ?? null;

                    if (str_starts_with($pData['name'], 'General Items ')) {
                        $num = (int) str_replace('General Items ', '', $pData['name']);
                        if (isset($generalItemsData[$num])) {
                            $cp = $generalItemsData[$num]['checkpoint'];
                            $kj = $generalItemsData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Workers and Inspectors ')) {
                        $num = (int) str_replace('Workers and Inspectors ', '', $pData['name']);
                        if (isset($workersAndInspectorsData[$num])) {
                            $cp = $workersAndInspectorsData[$num]['checkpoint'];
                            $kj = $workersAndInspectorsData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Jalur Jalan ')) {
                        $num = (int) str_replace('Jalur Jalan ', '', $pData['name']);
                        if (isset($jalurJalanData[$num])) {
                            $cp = $jalurJalanData[$num]['checkpoint'];
                            $kj = $jalurJalanData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Tempat Penyimpanan Alat Kebersihan ')) {
                        $num = (int) str_replace('Tempat Penyimpanan Alat Kebersihan ', '', $pData['name']);
                        if (isset($kebersihanData[$num])) {
                            $cp = $kebersihanData[$num]['checkpoint'];
                            $kj = $kebersihanData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Penyimpanan Terminal ')) {
                        $num = (int) str_replace('Penyimpanan Terminal ', '', $pData['name']);
                        if (isset($penyimpananTerminalData[$num])) {
                            $cp = $penyimpananTerminalData[$num]['checkpoint'];
                            $kj = $penyimpananTerminalData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Penyimpanan Wire ')) {
                        $num = (int) str_replace('Penyimpanan Wire ', '', $pData['name']);
                        if ($area->slug === 'pre-assy-cassette' && isset($penyimpananWireCuttingCassetteData[$num])) {
                            $cp = $penyimpananWireCuttingCassetteData[$num]['checkpoint'];
                            $kj = $penyimpananWireCuttingCassetteData[$num]['kriteria_judgement'];
                        } elseif (isset($penyimpananWireData[$num])) {
                            $cp = $penyimpananWireData[$num]['checkpoint'];
                            $kj = $penyimpananWireData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Penyimpanan APPL ')) {
                        $num = (int) str_replace('Penyimpanan APPL ', '', $pData['name']);
                        if (isset($penyimpananAPPLData[$num])) {
                            $cp = $penyimpananAPPLData[$num]['checkpoint'];
                            $kj = $penyimpananAPPLData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Mesin Cutting and Crimping ')) {
                        $num = (int) str_replace('Mesin Cutting and Crimping ', '', $pData['name']);
                        if (isset($mesinCuttingCrimpingData[$num])) {
                            $cp = $mesinCuttingCrimpingData[$num]['checkpoint'];
                            $kj = $mesinCuttingCrimpingData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with(strtolower($pData['name']), 'la terminal inspeksi waterproof ')) {
                        $num = (int) str_replace(['LA Terminal Inspeksi Waterproof ', 'LA terminal inspeksi waterproof '], '', $pData['name']);
                        if (isset($laTerminalWaterproofData[$num])) {
                            $cp = $laTerminalWaterproofData[$num]['checkpoint'];
                            $kj = $laTerminalWaterproofData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Penyimpanan Parts ')) {
                        $num = (int) str_replace('Penyimpanan Parts ', '', $pData['name']);
                        if (isset($penyimpananPartsData[$num])) {
                            $cp = $penyimpananPartsData[$num]['checkpoint'];
                            $kj = $penyimpananPartsData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Inspeksi Penerimaan Material ')) {
                        $num = (int) str_replace('Inspeksi Penerimaan Material ', '', $pData['name']);
                        if (isset($inspeksiPenerimaanData[$num])) {
                            $cp = $inspeksiPenerimaanData[$num]['checkpoint'];
                            $kj = $inspeksiPenerimaanData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Solder ( Deep Solder ) ')) {
                        $num = (int) str_replace('Solder ( Deep Solder ) ', '', $pData['name']);
                        if (isset($solderDeepData[$num])) {
                            $cp = $solderDeepData[$num]['checkpoint'];
                            $kj = $solderDeepData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Middle Inspeksi (Solder) ')) {
                        $num = (int) str_replace('Middle Inspeksi (Solder) ', '', $pData['name']);
                        if (isset($middleInspeksiData[$num])) {
                            $cp = $middleInspeksiData[$num]['checkpoint'];
                            $kj = $middleInspeksiData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Soldering Iron ( Gun Solder ) ')) {
                        $num = (int) str_replace('Soldering Iron ( Gun Solder ) ', '', $pData['name']);
                        if (isset($solderingGunData[$num])) {
                            $cp = $solderingGunData[$num]['checkpoint'];
                            $kj = $solderingGunData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Bondering Process ')) {
                        $num = (int) str_replace('Bondering Process ', '', $pData['name']);
                        if (isset($bonderingData[$num])) {
                            $cp = $bonderingData[$num]['checkpoint'];
                            $kj = $bonderingData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with(strtolower($pData['name']), 'ultrasonic welding machine ')) {
                        $num = (int) str_replace(['Ultrasonic Welding Machine ', 'Ultrasonic welding machine '], '', $pData['name']);
                        if (isset($ultrasonicData[$num])) {
                            $cp = $ultrasonicData[$num]['checkpoint'];
                            $kj = $ultrasonicData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with(strtolower($pData['name']), 'pemasangan heat shrink tube ')) {
                        $num = (int) str_replace(['Pemasangan Heat Shrink Tube ', 'Pemasangan Heat shrink tube '], '', $pData['name']);
                        if (isset($heatShrinkData[$num])) {
                            $cp = $heatShrinkData[$num]['checkpoint'];
                            $kj = $heatShrinkData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Di Sekitar Mesin Otomatis ( Pemotongan hingga inspeksi menengah ) ')) {
                        $num = (int) str_replace('Di Sekitar Mesin Otomatis ( Pemotongan hingga inspeksi menengah ) ', '', $pData['name']);
                        if (isset($diSekitarMesinOtomatisData[$num])) {
                            $cp = $diSekitarMesinOtomatisData[$num]['checkpoint'];
                            $kj = $diSekitarMesinOtomatisData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'TI di sekitar Mesin Otomatis ( Insert Terminal Penempatan Sementara ) ')) {
                        $num = (int) str_replace('TI di sekitar Mesin Otomatis ( Insert Terminal Penempatan Sementara ) ', '', $pData['name']);
                        if (isset($tiDiSekitarMesinOtomatisData[$num])) {
                            $cp = $tiDiSekitarMesinOtomatisData[$num]['checkpoint'];
                            $kj = $tiDiSekitarMesinOtomatisData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Insert Rubber Plug ')) {
                        $num = (int) str_replace('Insert Rubber Plug ', '', $pData['name']);
                        if (isset($insertRubberPlugData[$num])) {
                            $cp = $insertRubberPlugData[$num]['checkpoint'];
                            $kj = $insertRubberPlugData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Wire Hanger / Trolly Wire (setelah proses cutting) ')) {
                        $num = (int) str_replace('Wire Hanger / Trolly Wire (setelah proses cutting) ', '', $pData['name']);
                        if (isset($wireHangerTrollyData[$num])) {
                            $cp = $wireHangerTrollyData[$num]['checkpoint'];
                            $kj = $wireHangerTrollyData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Inspec Pre Assy ')) {
                        $num = (int) str_replace('Inspec Pre Assy ', '', $pData['name']);
                        if (isset($inspecPreAssyData[$num])) {
                            $cp = $inspecPreAssyData[$num]['checkpoint'];
                            $kj = $inspecPreAssyData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Wire Twist Process ')) {
                        $num = (int) str_replace('Wire Twist Process ', '', $pData['name']);
                        if (isset($wireTwistData[$num])) {
                            $cp = $wireTwistData[$num]['checkpoint'];
                            $kj = $wireTwistData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Shield Wire Process ')) {
                        $num = (int) str_replace('Shield Wire Process ', '', $pData['name']);
                        if (isset($shieldWireData[$num])) {
                            $cp = $shieldWireData[$num]['checkpoint'];
                            $kj = $shieldWireData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Wire Store ( sebelum sub-Assembly ) ')) {
                        $num = (int) str_replace('Wire Store ( sebelum sub-Assembly ) ', '', $pData['name']);
                        if (isset($wireStoreData[$num])) {
                            $cp = $wireStoreData[$num]['checkpoint'];
                            $kj = $wireStoreData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Store and Shipping Area for JUNKAN Product ')) {
                        $num = (int) str_replace('Store and Shipping Area for JUNKAN Product ', '', $pData['name']);
                        if (isset($storeShippingJunkanData[$num])) {
                            $cp = $storeShippingJunkanData[$num]['checkpoint'];
                            $kj = $storeShippingJunkanData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Sub Assembly ')) {
                        $num = (int) str_replace('Sub Assembly ', '', $pData['name']);
                        if (isset($subAssemblyData[$num])) {
                            $cp = $subAssemblyData[$num]['checkpoint'];
                            $kj = $subAssemblyData[$num]['kriteria_judgement'];
                        }
                    } elseif (str_starts_with($pData['name'], 'Wire Layout / Taping ')) {
                        $num = (int) str_replace('Wire Layout / Taping ', '', $pData['name']);
                        if (isset($wireLayoutTapingData[$num])) {
                            $cp = $wireLayoutTapingData[$num]['checkpoint'];
                            $kj = $wireLayoutTapingData[$num]['kriteria_judgement'];
                        }
                    }

                    AuditProcess::updateOrCreate(
                        ['audit_area_id' => $area->id, 'name' => $pData['name']],
                        [
                            'description' => "Pemeriksaan {$pData['name']} pada area {$area->name}",
                            'checkpoint' => $cp ?? "Checkpoint untuk {$pData['name']} - menyusul",
                            'kriteria_judgement' => $kj ?? "Kriteria OK/NG untuk {$pData['name']} - menyusul",
                            'status' => 'Ready',
                            'sort_order' => $pData['sort_order'],
                        ]
                    );
                }
            } else {
                // Seed 3 dummy processes for remaining areas
                for ($i = 1; $i <= 3; $i++) {
                    $pName = "Process {$i}";
                    AuditProcess::updateOrCreate(
                        ['audit_area_id' => $area->id, 'name' => $pName],
                        [
                            'description' => "Inspeksi standar 5S Tahap {$i} pada area {$area->name}",
                            'checkpoint' => "Checkpoint untuk {$pName} - menyusul",
                            'kriteria_judgement' => "Kriteria OK/NG untuk {$pName} - menyusul",
                            'status' => 'Ready',
                            'sort_order' => $i,
                        ]
                    );
                }
            }
        }

        // 2. Seed Change Point Area & 24 processes (12 "Cutting & Crimping" and 12 "Assembly Process")
        $changePointArea = AuditArea::updateOrCreate(
            ['slug' => 'change-point-management'],
            [
                'category' => 'change_point',
                'name' => 'Change Point Management',
                'description' => 'Audit kesiapan & verifikasi perubahan pada lini produksi (Man, Machine, Material, Method)',
                'icon_svg' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                'sort_order' => 15,
            ]
        );

        $changePointCuttingData = [
            1 => [
                'checkpoint' => 'Untuk change point management yang dikeluarkan oleh departemen QA, metode pelatihan, inspeksi dan evaluasi proses diklarifikasi dalam 5W1H. ※Lihat materi tambahan',
                'kriteria_judgement' => '"NG" jika 19 item change point hanya terdaftar dan ditampilkan tetapi tidak ada tindakan yang diambil',
            ],
            2 => [
                'checkpoint' => 'Visualisasikan lokasi titik perubahan/change point dan posisi operator di papan visual.',
                'kriteria_judgement' => 'Tidak perlu menampilkan semua change point 4M dalam satu board. Misalnya) menggunakan lembar alokasi kerja untuk perubahan Man power dan layout proses untukchange point lainnya',
            ],
            3 => [
                'checkpoint' => '"Before" dan "After" 4M change divisualisasikan di papan VM.',
                'kriteria_judgement' => '-',
            ],
            4 => [
                'checkpoint' => 'Berdasarkan 1, item yang memerlukan pendidikan adalah menerapkan pendidikan.',
                'kriteria_judgement' => '-',
            ],
            5 => [
                'checkpoint' => 'Lot pertama diberikan tag 4M change.',
                'kriteria_judgement' => '-',
            ],
            6 => [
                'checkpoint' => 'Hasil pemeriksaan khusus dari lot pertama dicatat dan disimpan. (Periode penyimpanan sama dengan catatan inspeksi.)',
                'kriteria_judgement' => 'Format tabel kontrol bersifat opsional, tetapi "NG" jika produk tidak dapat dilacak',
            ],
            7 => [
                'checkpoint' => 'Ketika operator berubah proses (ex: AC81→Bonder),visualisasikan indikator ke operator dan proses dibawah pengawasan instruktur level 2. (Tidak berlaku ketika didivisi yang sama, perubahan nomor mesin, AC81→AC90 dll)',
                'kriteria_judgement' => "1) Pergantian operator dalam area proses yang sama adalah N/A (misal: Dari AC81-1 ke AC81-2)\n2) \"OK\" jika mengindikasikan adanya perubahan pada posisi kerja seperti equipment atau stasiun kerja.\n3) \"OK\" jika ada aturan yang menunjukkan perubahan dan aturan tersebut diikuti\n4) Sekalipun perubahan ditunjukkan melalui operator (tutup, kartu, bibs, dl), \"NG\" jika tidak ditunjukkan di change point management board",
            ],
            8 => [
                'checkpoint' => 'Ketika ditemukan defect di titik perubahan, maka proses harus distop dan langsung diambil tindakan.',
                'kriteria_judgement' => '-',
            ],
            9 => [
                'checkpoint' => 'Operator/staff perbantuan harus memiliki license proses.',
                'kriteria_judgement' => "Cari operator di bawah change point management dan periksa status lisensi yang diperoleh di daftar operator.\n\"OK\" jika operator memiliki lisensi yang diperlukan untuk pekerjaan tersebut.",
            ],
            10 => [
                'checkpoint' => 'Supervisor harus mengecheck operator perbantuan terkait 4M change minimal 2 kali sehari. ( jika SPV sibuk, bisa digantikan oleh Forman atau LL yang berkompeten)',
                'kriteria_judgement' => "1) \"NG\" jika instruktur change point dan orang yang memeriksa status pekerjaan sama\n2) \"OK\" jika ada catatan bahwa supervisor mengkonfirmasi pekerjaan tersebut",
            ],
            11 => [
                'checkpoint' => 'Manajer Area akan memverifikasi dan mengkonfirmasi apakah pengoperasian titik perubahan oleh operator telah diikuti seperti yang diinstruksikan.',
                'kriteria_judgement' => '"OK" jika final check terhadapchange point dikonfirmasi oleh manajer',
            ],
            12 => [
                'checkpoint' => 'Untuk mengamankan MP yang diperlukan, hari libur disesuaikan terlebih dahulu dan TANOKO dikembangkan.',
                'kriteria_judgement' => "Perlu memiliki dokumentasi untuk menunjukkan hari libur disesuaikan terlebih dahulu dan pekerja multi-skill seperti lembar keterampilan atau daftar lisensi.\n1. Butuh rencana liburan/cuti berbayar dan hari libur\n2. Daftar Operator Multi Skill di line Produksi.\n3. Prosedur untuk menangani keadaan darurat, seperti operator secara tidak sengaja meninggalkan pekerjaan karena hal yang sangat penting tetapi tidak ada informasi.",
            ],
        ];

        $changePointAssemblyData = [
            1 => [
                'checkpoint' => 'Untuk change point management yang dikeluarkan oleh Dept QA, proses dan metode training, inspection, dan evaluasi proses berdasarkan 5W1H ※Lihat lampiran',
                'kriteria_judgement' => '"NG" jika 20 item change point hanya terdaftar dan ditampilkan tetapi tidak ada tindakan yang diambil',
            ],
            2 => [
                'checkpoint' => 'Visualisasikan lokasi titik perubahan dan posisi operator di papan visual.',
                'kriteria_judgement' => 'Layout proses diperlukan',
            ],
            3 => [
                'checkpoint' => '"Before" dan "After" 4M change divisualisasikan di papan VM.',
                'kriteria_judgement' => '-',
            ],
            4 => [
                'checkpoint' => 'Sesuai dengan yang no 1, Operator perubahan mendapat training terkait proses. (operation check /3 kali dan inspection /3 product )',
                'kriteria_judgement' => "1) \"OK\" jika melihat operator sedang diawasi setidaknya satu kali.\n2) Jika produksi non-massal kurang dari 3 pcs, evaluasi akan dilakukan dengan memeriksa volume produksi\n3) Evaluasi dianggap \"OK\" dengan memeriksa 3 produk apa pun pada jalur konveyor yang memproduksi berbagai produk",
            ],
            5 => [
                'checkpoint' => '3 produk pertama diberi tag',
                'kriteria_judgement' => "1) Jika role (metode tampilan) dapat dikonfirmasi dan diidentifikasi, evaluasi akan dilakukan \"NG\"\n2) Jika produksi tidak sampai 3 pcs per sehari, evaluasi sebagai \"OK\" dengan mengonfirmasi volume produksi.\n3) Evaluasi dianggap \"OK\" dengan memeriksa 3 produk apa pun pada jalur konveyor yang memproduksi berbagai produk",
            ],
            6 => [
                'checkpoint' => 'Hasil pemeriksaan khusus dari 3 produk pertama dicatat dan disimpan. (Periode penyimpanan sama dengan catatan inspeksi.)',
                'kriteria_judgement' => "1) Format tabel kontrol bersifat opsional, tetapi \"NG\" jika produk tidak dapat dilacak\n2) Jika produksi tidak sampai 3 pcs per sehari, evaluasi sebagai \"OK\" dengan mengonfirmasi volume produksi.\n3) Evaluasi dianggap \"OK\" dengan memeriksa 3 produk apa pun pada jalur konveyor yang memproduksi berbagai produk",
            ],
            7 => [
                'checkpoint' => 'Selama proses, posisi, dan line berubah, titik perubahan/change point ditampilkan di papan visual dan operator bekerja dibawah pengawasan instruktur level 2.',
                'kriteria_judgement' => "1) \"OK\" jika terdapat indikasi pada work position seperti equipment atau tempat work station\n2) \"OK\" jika ada aturan yang menunjukkan perubahan dan aturan tersebut dipatuhi",
            ],
            8 => [
                'checkpoint' => 'Ketika ditemukan defect di titik perubahan, maka proses harus distop dan langsung diambil tindakan.',
                'kriteria_judgement' => '-',
            ],
            9 => [
                'checkpoint' => 'Operator/staff perbantuan harus memiliki license proses.',
                'kriteria_judgement' => 'Cari operator di bawah vhange point management perubahan dan periksa status lisensi yang diperoleh di daftar operator. "OK" jika operator memiliki lisensi yang diperlukan untuk pekerjaan tersebut.',
            ],
            10 => [
                'checkpoint' => 'Supervisor harus mengecheck operator perbantuan terkait 4M change minimal 2 kali sehari. ( jika SPV sibuk, bisa digantikan oleh Forman atau LL yang berkompeten.)',
                'kriteria_judgement' => "1) \"NG\" jika instruktur change point dan orang yang memeriksa status pekerjaan adalah sama\n2) \"OK\" jika ada catatan bahwa supervisor mengkonfirmasi pekerjaan tersebut",
            ],
            11 => [
                'checkpoint' => 'Manajer Area akan memverifikasi dan mengkonfirmasi apakah pengoperasian titik perubahan oleh operator telah diikuti seperti yang diinstruksikan.',
                'kriteria_judgement' => '"OK" jika final check terhadapchange point dikonfirmasi oleh manajer.',
            ],
            12 => [
                'checkpoint' => 'Untuk mengamankan MP yang diperlukan, hari libur disesuaikan terlebih dahulu dan TANOKO dikembangkan.',
                'kriteria_judgement' => "Perlu memiliki dokumentasi untuk menunjukkan hari libur disesuaikan terlebih dahulu dan pekerja multi-skill seperti lembar keterampilan atau daftar lisensi.\n1. Butuh rencana liburan/cuti berbayar dan hari libur\n2. Daftar Operator Multi Skill di line Produksi.\n3. Prosedur untuk menangani keadaan darurat, seperti operator secara tidak sengaja meninggalkan pekerjaan karena hal yang sangat penting tetapi tidak ada informasi.",
            ],
        ];

        AuditProcess::where('audit_area_id', $changePointArea->id)
            ->whereIn('name', ['Cutting & Crimping', 'Assembly Process'])
            ->delete();

        for ($i = 1; $i <= 12; $i++) {
            $pName = "Cutting & Crimping {$i}";
            AuditProcess::updateOrCreate(
                ['audit_area_id' => $changePointArea->id, 'name' => $pName],
                [
                    'description' => "Audit manajemen perubahan pada proses Cutting & Crimping {$i}",
                    'checkpoint' => $changePointCuttingData[$i]['checkpoint'] ?? "Checkpoint untuk {$pName} - menyusul",
                    'kriteria_judgement' => $changePointCuttingData[$i]['kriteria_judgement'] ?? "Kriteria OK/NG untuk {$pName} - menyusul",
                    'status' => 'Ready',
                    'sort_order' => $i,
                ]
            );
        }

        for ($i = 1; $i <= 12; $i++) {
            $pName = "Assembly Process {$i}";
            AuditProcess::updateOrCreate(
                ['audit_area_id' => $changePointArea->id, 'name' => $pName],
                [
                    'description' => "Audit manajemen perubahan pada proses Assembly Process {$i}",
                    'checkpoint' => $changePointAssemblyData[$i]['checkpoint'] ?? "Checkpoint untuk {$pName} - menyusul",
                    'kriteria_judgement' => $changePointAssemblyData[$i]['kriteria_judgement'] ?? "Kriteria OK/NG untuk {$pName} - menyusul",
                    'status' => 'Ready',
                    'sort_order' => 12 + $i,
                ]
            );
        }

        // 3. Seed License System Area & 18 processes ("All Process 1" to "All Process 18")
        $licenseSystemArea = AuditArea::updateOrCreate(
            ['slug' => 'license-system'],
            [
                'category' => 'license_system',
                'name' => 'License System',
                'description' => 'Audit lisensi sertifikasi & kualifikasi operasional personel',
                'icon_svg' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                'sort_order' => 16,
            ]
        );

        $licenseSystemData = [
            1 => [
                'checkpoint' => 'Ada promotor Operation Licence System di pabrik.',
                'kriteria_judgement' => '"OK" jika terdapat setidaknya satu operator yang ditunjuk',
            ],
            2 => [
                'checkpoint' => 'Terdapat cukup instruktur level 1 di setiap pabrik yang mampu mengontrol lisensi sistem (contoh : Kontrol plan tahunan; data management; memperbarui lisensi; mengidentifikasi masalah dengan metode dan materi pelatihan, membuat proposal kaizen; menerbitkan lisensi, dll)',
                'kriteria_judgement' => '-',
            ],
            3 => [
                'checkpoint' => 'Instruktur level 2 memahami 9 item metode pelatihan (langkah mengajar). *Konfirmasi hasil lembar evaluasi lisensi Instruktur level 2 atau konfirmasi dengan melakukan wawancara dengan instruktur level 2',
                'kriteria_judgement' => '"OK" jika DB (Database) atau daftar evaluasi penilaian lisensi dikonfirmasi dan isinya dipahami oleh instruktur level 2 berdasarkan wawancara.',
            ],
            4 => [
                'checkpoint' => 'SWCT atau Operation Analisi form untuk training disiapkan oleh instruktur level 2',
                'kriteria_judgement' => "(1) Operator analisis form diperlukan, karena hanya dengan SWCT dan kualitas teknis saja tidak cukup. Table rincian kerja diperlukan.\n(2) Operator analisis form actual diperlukan di ruang training",
            ],
            5 => [
                'checkpoint' => 'Terdapat cukup banyak instruktur level 2 dengan lisensi yang diperlukan, yang memberikan training dan mengawasi pekerjaan operator dengan temporary lisensi.',
                'kriteria_judgement' => 'Satu instruktur wajib memiliki maksimal lima peserta pelatihan baru untuk training lisensi resmi',
            ],
            6 => [
                'checkpoint' => 'Ada Ruang training (training teori dan training praktek) untuk izin temporary lisensi',
                'kriteria_judgement' => 'Ruang meeting atau kantin juga dapat digunakan sebagai area seminar atau training',
            ],
            7 => [
                'checkpoint' => 'Equipment, jig tool dan hal lain yang diperlukan untuk training temporary lisensi disiapkan oleh departemen training. (Lihat PMD-B00-MOT-LC-001-P02) Siapkan sampel training khusus (disarankan sekitar 30 circuit)',
                'kriteria_judgement' => '"OK" jika ada item persyaratan minimum (lihat PMD-A00-MOT-LC-001-P02)',
            ],
            8 => [
                'checkpoint' => 'Departemen yang bertanggung jawab atas pelatihan temporary lisensi menyiapkan kurikulum training',
                'kriteria_judgement' => '"NG" jika kurikulum untuk temporary dan lisensi resmi tidak ada',
            ],
            9 => [
                'checkpoint' => "Dokumen pendukung untuk training temporary lisensi telah disiapkan oleh departemen training (Lihat pedoman OLS di hal 9)\n1.Standar dokumen\n2.Text Book\n3.Sampel\n4.Ujian\n5.Lembar penilaian",
                'kriteria_judgement' => '"OK" jika semua item yang diperlukan sudah disiapkan (lih. Halaman.9 pedoman OLS )',
            ],
            10 => [
                'checkpoint' => 'Melakukan evaluasi untuk memperoleh temporary lisensi dan hasilnya dicatat (ujian tertulis, tes praktik).',
                'kriteria_judgement' => "1) Berlaku untuk semua proses (harus menyimpan hasil lisensi sementara sampai lisensi resmi diterbitkan)\n2) Hasil lisensi sementara diperlukan apabila ada operator yang mempunyai lisensi sementara. (\"NG\" jika tidak memiliki tes tertulis dan praktik)\n3) Tidak sebatas menyimpan hasil setelah mendapat izin resmi (\"OK\" jika hasil untuk lisensi sementara dikonfirmasi)",
            ],
            11 => [
                'checkpoint' => 'Operator yang telah di training dan dievaluasi menunjukan kartu temporary lisensi (Lisensi harus valid)',
                'kriteria_judgement' => "1) Tidak ada format yang ditentukan untuk lisensi sementara jika informasi yang diperlukan disebutkan maka evaluasinya \"OK\"\n2) Jika tidak ada orang yang applicable untuk di konfirmasi pada saat audit, evaluasi \"OK\" selama peraturan untuk indikasi lisensi ditentukan dan dipatuhi. (misalnya, menunjukan lisensi dengan menempelkannya pada topi, seragam, dll.)\n3) \"NG\" jika tidak ada catatan pelatihan dan tidak ada lembar evaluasi meskipun memiliki temporary lisensi",
            ],
            12 => [
                'checkpoint' => 'Operator temporary lisensi bekerja di bawah pengawasan instruktur level 2',
                'kriteria_judgement' => 'Jika tidak ada operator dengan lisensi sementara selama audit, tanyakan lokasi instruktur level-2. "OK" jika dia berada di tempat dimana dia dapat kembali berproduksi kapan saja.',
            ],
            13 => [
                'checkpoint' => 'Evaluasi untuk lisensi reguler diimplementasikan dan hasilnya disimpan (tes praktik)',
                'kriteria_judgement' => "1) Berlaku untuk semua proses.\n2) \"OK\" jika hasil evaluasi lisensi proses disimpan atau bisa dikonfirmasi didisplay board\n3) \"NG\" jika tidak ada ujian praktik.",
            ],
            14 => [
                'checkpoint' => 'Pekerjaan operator pemilik lisensi di observasi minimal sebulan sekali oleh instruktur level 2 dan hasilnya dicatat.',
                'kriteria_judgement' => "1) Berlaku untuk semua proses.\n2) \"OK\" jika ada catatan proses pekerjaan yang dilakukan sesuai SWCT dan memiliki kualitas kinerja yang baik (Jangan hanya fokus pada hasil regular lisensi.)",
            ],
            15 => [
                'checkpoint' => 'Data skill untuk operator reguler disiapkan',
                'kriteria_judgement' => "1) Berlaku untuk semua proses. (Pahami hasil evaluasi operator dalam proses sendiri)\n2) Terdapat data skill untuk operator reguler.",
            ],
            16 => [
                'checkpoint' => "Status perolehan lisensi yang diperlukan semua operator dan inspector terkonfirmasi di database.\nIzin lisensi dan proses actual yang dilakukan harus sesuai.",
                'kriteria_judgement' => "1) Berlaku untuk semua proses (Hasil evaluasi untuk self-proses harus diketahui.)\n2) Periksa apakah operator mempunyai lisensi sebagaimana tercantum pada data status lisensi\n3) \"NG\" apabila operator tetap tidak mempunyai lisensi atas pekerjaan yang dilakukannya.",
            ],
            17 => [
                'checkpoint' => "License renewal plan tersedia.\nDan renual serta evaluasi harus dilakukan sebelum tanggal expired. (NG jika expired)",
                'kriteria_judgement' => "1) Berlaku untuk semua proses (Plan update lisensi untuk self-proses harus diketahui.)\n2) \"OK\" jika ada rencana pembaruan lisensi dan jika evaluasi keterampilan dilakukan\n3) \"NG\" apabila tidak dilakukan evaluasi untuk memperbaharui seluruh lisensi yang diperoleh operator terkait.",
            ],
            18 => [
                'checkpoint' => 'Ketika proses berpindah divisi dari Pre-assembly / Assembly / Inspection, operator mulai lagi dari training temporary license.',
                'kriteria_judgement' => "Melakukan wawancara untuk mengecek peraturan dan status pada saat pergantian pekerjaan di luar divisi.\n·Dalam kategori yang sama, tidak diperlukan temporary education\nContoh 1) Final Assy → Final Assy : Tidak diperlukan revisi\nContoh 2) Final assy → Pre assy: Diperlukan akuisisi ulang",
            ],
        ];

        AuditProcess::where('audit_area_id', $licenseSystemArea->id)->where('name', 'All Process')->delete();

        for ($i = 1; $i <= 18; $i++) {
            $pName = "All Process {$i}";
            AuditProcess::updateOrCreate(
                ['audit_area_id' => $licenseSystemArea->id, 'name' => $pName],
                [
                    'description' => "Pemeriksaan dan evaluasi lisensi sertifikasi operasional All Process {$i}",
                    'checkpoint' => $licenseSystemData[$i]['checkpoint'] ?? "Checkpoint untuk {$pName} - menyusul",
                    'kriteria_judgement' => $licenseSystemData[$i]['kriteria_judgement'] ?? "Kriteria OK/NG untuk {$pName} - menyusul",
                    'status' => 'Ready',
                    'sort_order' => $i,
                ]
            );
        }

        // Populate placeholder for all AuditProcess records missing checkpoint/kriteria_judgement
        foreach (AuditProcess::all() as $proc) {
            if (empty($proc->checkpoint) || empty($proc->kriteria_judgement)) {
                $proc->update([
                    'checkpoint' => $proc->checkpoint ?? "Checkpoint untuk {$proc->name} - menyusul",
                    'kriteria_judgement' => $proc->kriteria_judgement ?? "Kriteria OK/NG untuk {$proc->name} - menyusul",
                ]);
            }
        }

        // 4. Seed Audit Records (Snapshot fields: area_name & auditor_name)
        $user = AuditUser::first();
        $warehouseArea = AuditArea::where('slug', 'warehouse')->first();
        $allProcessArea = AuditArea::where('slug', 'all-process')->first();
        $workersArea = AuditArea::where('slug', 'workers-and-inspectors')->first();
        $jalurArea = AuditArea::where('slug', 'jalur-jalan')->first();
        $kebersihanArea = AuditArea::where('slug', 'tempat-penyimpanan-alat-kebersihan')->first();

        $records = [
            [
                'audit_area_id' => $warehouseArea?->id,
                'audit_process_id' => $warehouseArea?->processes()->where('name', 'Penyimpanan Terminal 1')->first()?->id ?? $warehouseArea?->processes()->first()?->id,
                'audit_user_id' => $user?->id,
                'audit_date' => '2026-08-04',
                'area_name' => 'Warehouse',
                'auditor_name' => 'Budi Santoso',
                'score' => 85.00, // NG
                'status' => 'Selesai',
            ],
            [
                'audit_area_id' => $allProcessArea?->id,
                'audit_process_id' => $allProcessArea?->processes()->where('name', 'General Items 1')->first()?->id ?? $allProcessArea?->processes()->first()?->id,
                'audit_user_id' => $user?->id,
                'audit_date' => '2026-08-03',
                'area_name' => 'All Process',
                'auditor_name' => 'Siti Nurhaliza',
                'score' => 96.00, // OK
                'status' => 'Selesai',
            ],
            [
                'audit_area_id' => $workersArea?->id,
                'audit_process_id' => $workersArea?->processes()->where('name', 'Workers and Inspectors 1')->first()?->id ?? $workersArea?->processes()->first()?->id,
                'audit_user_id' => $user?->id,
                'audit_date' => '2026-08-03',
                'area_name' => 'Workers and Inspectors',
                'auditor_name' => 'Ahmad Fauzi',
                'score' => 92.00, // OK
                'status' => 'Selesai',
            ],
            [
                'audit_area_id' => $jalurArea?->id,
                'audit_process_id' => $jalurArea?->processes()->where('name', 'Jalur Jalan 1')->first()?->id ?? $jalurArea?->processes()->first()?->id,
                'audit_user_id' => $user?->id,
                'audit_date' => '2026-08-02',
                'area_name' => 'Jalur Jalan',
                'auditor_name' => 'Dewi Lestari',
                'score' => 88.00, // NG
                'status' => 'Selesai',
            ],
            [
                'audit_area_id' => $kebersihanArea?->id,
                'audit_process_id' => $kebersihanArea?->processes()->where('name', 'Tempat Penyimpanan Alat Kebersihan 1')->first()?->id ?? $kebersihanArea?->processes()->first()?->id,
                'audit_user_id' => $user?->id,
                'audit_date' => '2026-08-01',
                'area_name' => 'Tempat Penyimpanan Alat Kebersihan',
                'auditor_name' => 'Eko Prasetyo',
                'score' => 95.00, // OK
                'status' => 'Selesai',
            ],
            [
                'audit_area_id' => $warehouseArea?->id,
                'audit_process_id' => $warehouseArea?->processes()->where('name', 'Penyimpanan Wire 2')->first()?->id ?? $warehouseArea?->processes()->first()?->id,
                'audit_user_id' => $user?->id,
                'audit_date' => '2026-08-01',
                'area_name' => 'Warehouse',
                'auditor_name' => 'Rina Wijaya',
                'score' => 94.00, // OK
                'status' => 'Selesai',
            ],
        ];

        foreach ($records as $rec) {
            AuditRecord::create($rec);
        }
    }
}
