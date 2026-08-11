<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Auditor Item Check Mapping
    |--------------------------------------------------------------------------
    |
    | Mapping kode auditor ke array ID audit_processes (No. 1-42)
    |
    */

    'mapping' => [
        'TWI'   => range(1, 3),
        'EMU'   => range(4, 6),
        'AYP'   => range(4, 10),
        'MMR'   => range(4, 10),
        'HMA'   => range(7, 12),
        'DAF'   => range(11, 17),
        'JIKAS' => range(11, 15),
        'NUR'   => range(13, 15),
        'ABD'   => range(16, 20),
        'OVI'   => range(16, 24),
        'FIR'   => range(18, 27),
        'ISR'   => range(21, 24),
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
