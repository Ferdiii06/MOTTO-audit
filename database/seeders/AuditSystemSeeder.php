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
                    ['prefix' => 'Penyimpanan Terminal', 'count' => 10],
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
                    ['prefix' => 'Di Sekitar Mesin Otomatis ( Pemotongan hingga inspeksi menengah )', 'count' => 11],
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
            17 => [
                'checkpoint' => 'Adanya Patroli/Audit safety yang dilakukan secara berkala dan hasil inspeksi tersedia. Jika ada masalah, maka tetapkan due date dan ambil tindakan.',
                'kriteria_judgement' => "Konfirmasi hasil inspeksi dari Safety comite yang dilakukan di setiap tempat produksi. Evaluasi dinyatakan \"OK\" apabila ada hasil inspeksi dan apabila ada masalah, pastikan ada tindakan dan lakukan konfirmasi ditempat. Evaluasi dinyatakan \"NG\" apabila tidak ada hasil inspeksi, Evaluasi dinyatakan \"NG\" apabila tidak ada tindakan.",
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

        AuditProcess::where('audit_area_id', $changePointArea->id)
            ->whereIn('name', ['Cutting & Crimping', 'Assembly Process'])
            ->delete();

        for ($i = 1; $i <= 12; $i++) {
            $pName = "Cutting & Crimping {$i}";
            AuditProcess::updateOrCreate(
                ['audit_area_id' => $changePointArea->id, 'name' => $pName],
                [
                    'description' => "Audit manajemen perubahan pada proses Cutting & Crimping {$i}",
                    'checkpoint' => "Checkpoint untuk {$pName} - menyusul",
                    'kriteria_judgement' => "Kriteria OK/NG untuk {$pName} - menyusul",
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
                    'checkpoint' => "Checkpoint untuk {$pName} - menyusul",
                    'kriteria_judgement' => "Kriteria OK/NG untuk {$pName} - menyusul",
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

        AuditProcess::where('audit_area_id', $licenseSystemArea->id)->where('name', 'All Process')->delete();

        for ($i = 1; $i <= 18; $i++) {
            $pName = "All Process {$i}";
            AuditProcess::updateOrCreate(
                ['audit_area_id' => $licenseSystemArea->id, 'name' => $pName],
                [
                    'description' => "Pemeriksaan dan evaluasi lisensi sertifikasi operasional All Process {$i}",
                    'checkpoint' => "Checkpoint untuk {$pName} - menyusul",
                    'kriteria_judgement' => "Kriteria OK/NG untuk {$pName} - menyusul",
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
