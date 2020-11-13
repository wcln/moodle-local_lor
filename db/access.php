<?php

defined('MOODLE_INTERNAL') || die;

$capabilities = [
    'local/lor:manage' => [
        'riskbitbask'  => RISK_DATALOSS,
        'captype'      => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes'   => [
            'manager' => CAP_ALLOW,
        ],
    ],
];
