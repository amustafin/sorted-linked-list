<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ControlStructure\TrailingCommaInMultilineFixer;
use PhpCsFixer\Fixer\Operator\TernaryToNullCoalescingFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPreparedSets(
        psr12: true,
        common: true
    )
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/ecs.php',
    ])
    ->withPhpCsFixerSets(
        php84Migration: true,
    )
    ->withFileExtensions([
        'php',
    ])
    ->withSpacing(
        indentation: '    ',
        lineEnding: "\n",
    )
    ->withConfiguredRule(
        ArraySyntaxFixer::class,
        [
            'syntax' => 'short',
        ]
    )
    ->withConfiguredRule(
        LineLengthFixer::class,
        [
            'line_length' => 130,
            'inline_short_lines' => false,
        ]
    )
    ->withConfiguredRule(
        TrailingCommaInMultilineFixer::class,
        [
            'elements' => [
                'arrays',
                'parameters',
            ],
        ]
    )
    ->withRules([
        TernaryToNullCoalescingFixer::class,
    ]);
