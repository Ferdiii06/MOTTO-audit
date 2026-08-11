<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Auditor Item Check Mapping
    |--------------------------------------------------------------------------
    |
    | Mapping 23 kode auditor ke array ID audit_processes (Name Matching Final)
    |
    */

    'mapping' => [
        'TWI'   => array_merge(range(37, 65), [402]),
        'EMU'   => range(1, 32),
        'AYP'   => array_merge(range(1, 32), range(66, 78), range(83, 94)),
        'MMR'   => array_merge(range(1, 32), range(66, 78), range(83, 94)),
        'HMA'   => array_merge(range(66, 78), range(83, 112)),
        'DAF'   => range(95, 159),
        'JIKAS' => range(95, 147),
        'NUR'   => range(113, 147),
        'ABD'   => array_merge(range(148, 164), range(184, 193)),
        'OVI'   => array_merge(range(148, 164), range(184, 212), range(234, 239)),
        'FIR'   => array_merge(range(160, 164), range(184, 212), range(234, 266)),
        'ISR'   => array_merge(range(194, 212), range(234, 239)),
        'IAM'   => range(240, 266),
        'SSI'   => range(240, 294),
        'DIAN'  => range(267, 294),
        'NAF'   => array_merge(range(33, 36), range(267, 325)),
        'WWI'   => range(295, 319),
        'RWU'   => array_merge(range(33, 36), range(295, 338)),
        'AIN'   => array_merge(range(33, 36), range(320, 338)),
        'DHS'   => range(339, 359),
        'WSU'   => range(339, 401),
        'NIA'   => range(326, 401),
        'LSE'   => range(360, 401),
    ],
];
