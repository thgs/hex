#!/usr/local/bin/php
<?php

use thgs\Hex\Editor;
use thgs\Hex\Output\StreamOutput;

require __DIR__ . '/../vendor/autoload.php';

$editor = new Editor(null, new StreamOutput('php://stdout'));

// $editor->selectFile('/Users/theo/test.txt');
$editor->operateOnCopy('/Users/theo/test.txt', '/Users/theo/test2.txt');
$editor->dump();

$editor->update(2, '32');   // test2.txt has the update

$results = $editor->seek(0, '6973');   // "is"
var_dump($results);