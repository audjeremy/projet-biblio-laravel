<?php

return [
    'currency' => '$', // affichage
    // Montréal (QC): TPS 5% + TVQ 9.975% 
    'taxes' => [
        'gst' => 0.05,     // 5%
        'qst' => 0.09975,  // 9.975%
    ],
    'shipping_flat' => 0, // si jamais tu veux un frais fixe
];
