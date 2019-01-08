<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = array(

    /* Allows the user to insert new items into the LOR. */
    'local/lor:insert' => array(
        'riskbitmask' => RISK_CONFIG,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(),
    )

);
