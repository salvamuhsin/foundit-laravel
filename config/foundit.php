<?php

// Shared lookup data for the FoundIt views (categories, status labels, banner photos).
$photo = fn (string $id, int $w = 1800) => "https://images.unsplash.com/{$id}?auto=format&fit=crop&w={$w}&q=70";

return [
    'categories' => ['Wallets', 'Bags', 'Electronics', 'Keys', 'Jewellery', 'Documents', 'Accessories', 'Toys', 'Other'],

    'category_icons' => [
        'Wallets' => 'bi-wallet2', 'Bags' => 'bi-handbag', 'Electronics' => 'bi-phone', 'Keys' => 'bi-key',
        'Jewellery' => 'bi-gem', 'Documents' => 'bi-file-earmark-text', 'Accessories' => 'bi-eyeglasses',
        'Toys' => 'bi-balloon-heart', 'Other' => 'bi-box-seam',
    ],

    // status => [label, css class from foundit.css, icon]
    'statuses' => [
        'open'      => ['Open', 'status-open', 'bi-circle'],
        'pending'   => ['Claim pending', 'status-pending', 'bi-hourglass-split'],
        'meetup'    => ['Meetup arranged', 'status-meetup', 'bi-calendar-event'],
        'returned'  => ['Returned', 'status-returned', 'bi-check2-circle'],
        'approved'  => ['Approved', 'status-meetup', 'bi-hand-thumbs-up'],
        'declined'  => ['Declined', 'status-declined', 'bi-x-circle'],
        'proposed'  => ['Awaiting confirmation', 'status-pending', 'bi-hourglass-split'],
        'changed'   => ['Change proposed', 'status-pending', 'bi-arrow-repeat'],
        'confirmed' => ['Confirmed', 'status-meetup', 'bi-calendar-check'],
        'completed' => ['Completed', 'status-returned', 'bi-check2-circle'],
        'cancelled' => ['Cancelled', 'status-declined', 'bi-slash-circle'],
    ],

    'photos' => [
        'hero'      => $photo('photo-1469854523086-cc02fe5d8800'),
        'browse'    => $photo('photo-1488646953014-85cb44e25828'),
        'report'    => $photo('photo-1553729459-efe14ef6055d'),
        'about'     => $photo('photo-1521791136064-7986c2920216'),
        'contact'   => $photo('photo-1423666639041-f56000c27a9a'),
        'auth'      => $photo('photo-1506905925346-21bda4d32df4'),
        'dashboard' => $photo('photo-1553062407-98eeb64c6a62'),
        'safety'    => $photo('photo-1556742049-0cfed4f6a45d', 1200),
        'community' => $photo('photo-1529156069898-49953e39b3ac', 1200),
    ],
];
