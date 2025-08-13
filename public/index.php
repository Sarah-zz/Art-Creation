<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Database\TestClass;

$test = new TestClass();
echo $test->hello();