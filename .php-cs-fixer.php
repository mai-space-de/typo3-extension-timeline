<?php

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__ . '/Classes', __DIR__ . '/Configuration'])
    ->exclude('vendor');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER-CS2x0' => true,
        '@PER-CS2x0:risky' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
