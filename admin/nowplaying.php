<?php

require 'sessionstart.php';
$DAYS = [
    _DAY0,
    _DAY1,
    _DAY2,
    _DAY3,
    _DAY4,
    _DAY5,
    _DAY6,
];
$DAY  = date('w');
echo trim(file_get_contents('./../triggers/nowplayingv'));
