#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

$application = new \Symfony\Component\Console\Application('Console App');
$application->add(new \Jackross\SumCommand());
$application->add(new \Jackross\SckobkiCommand());
$application->run();