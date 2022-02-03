<?php
$finder = PhpCsFixer\Finder::create()
  ->in(__DIR__.'/src')
  ->in(__DIR__.'/tests')
  ->exclude('Resources')
  ->name('*.php')
;

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
    ])
    ->setFinder($finder)
;

return $config;
