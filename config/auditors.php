<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Auditor Item Check Mapping
    |--------------------------------------------------------------------------
    |
    | Mapping kode auditor ke array ID audit_processes
    |
    */

    'mapping' => [
        // 1. TWI: Penyimpanan Terminal, Wire, Part (Warehouse)
        'TWI'   => array_merge(range(37, 65), [402]),

        // 2. EMU: Workers and Inspector, Jalur Jalan, 5S General Items
        'EMU'   => range(1, 32),

        // 3. HMA: Inspeksi Penerimaan Material, Solder (Deep Solder), Soldering Iron, Bondering, Ultrasonic Welding, Heat Shrink Tube
        'HMA'   => range(66, 112),

        // 4. NUR: Manual Crimping, Penyimpanan Terminal, Penyimpanan Wire (Cutting & Crimping / Pre Assy)
        'NUR'   => range(113, 147),

        // 5. ABD: Penyimpanan APPL, Mesin Cutting & Crimping, LA Terminal Waterproof, Insert Rubber Plug, Wire Hanger (Pre Assy)
        'ABD'   => array_merge(range(148, 193), [403]),

        // 6. ISR: Inspect Pre Assy, Wire Twist, Shield Wire, Wire Store (sebelum Sub-Assembly)
        'ISR'   => array_merge(range(194, 212), range(234, 239)),

        // Kode auditor lainnya
        'AYP'   => range(4, 10),
        'MMR'   => range(4, 10),
        'DAF'   => range(11, 17),
        'JIKAS' => range(11, 15),
        'OVI'   => range(16, 24),
        'FIR'   => range(18, 27),
        'IAM'   => range(25, 27),
        'SSI'   => range(25, 30),
        'DIAN'  => range(28, 30),
        'NAF'   => range(28, 35),
        'WWI'   => range(31, 33),
        'RWU'   => range(31, 36),
        'AIN'   => range(34, 36),
        'DHS'   => range(37, 39),
        'WSU'   => range(37, 42),
        'NIA'   => range(36, 42),
        'LSE'   => range(40, 42),
    ],
];
