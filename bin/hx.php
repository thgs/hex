#!/usr/local/bin/php
<?php

use thgs\Hex\Editor;

require __DIR__ . '/../vendor/autoload.php';

$editor = new Editor();

$editor->selectFile('/Users/theo/test.txt');
$editor->dump();

$editor->update(2, '31');

$results = $editor->seek(0, '6973');   // "is"
var_dump($results);