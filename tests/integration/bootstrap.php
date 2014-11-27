<?php

ini_set('display_errors', 1);
umask(0);
require __DIR__ . '/utils/TestHelper.php';
require __DIR__ . '/utils/TestCase.php';
(new TestHelper())->initMagento();
