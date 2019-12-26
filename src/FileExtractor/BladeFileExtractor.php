<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\FileExtractor;

use Symfony\Component\Finder\SplFileInfo;
use Translation\Extractor\Model\SourceCollection;
use Translation\Extractor\Model\SourceLocation;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class BladeFileExtractor implements FileExtractor
{
    /**
     * {@inheritdoc}
     */
    public function getSourceLocations(SplFileInfo $file, SourceCollection $collection): void
    {
        $realPath = $file->getRealPath();
        $messages = $this->findTranslations($file);
        foreach ($messages as $message) {
            $collection->addLocation(new SourceLocation($message, $realPath, 0));
        }
    }

    /**
     * @author uusa35 and contributors to {@link https://github.com/barryvdh/laravel-translation-manager}
     *
     * @return string[]
     */
    public function findTranslations(SplFileInfo $file): array
    {
        $keys = [];
        $functions = ['trans', 'trans_choice', 'Lang::get', 'Lang::choice', 'Lang::trans', 'Lang::transChoice', '@lang', '@choice'];
        $pattern = // See http://regexr.com/392hu
            "[^\w|>]".// Must not have an alphanum or _ or > before real method
            '('.implode('|', $functions).')'.// Must start with one of the functions
            "\(".// Match opening parenthese
            "[\'\"]".// Match " or '
            '('.// Start a new group to match:
            '[a-zA-Z0-9_-]+'.// Must start with group
            '([.][^\\1)]+)+'.// Be followed by one or more items/keys
            ')'.// Close group
            "[\'\"]".// Closing quote
            "[\),]";                            // Close parentheses or new parameter

        // Search the current file for the pattern
        if (preg_match_all("/$pattern/siU", $file->getContents(), $matches)) {
            // Get all matches
            foreach ($matches[2] as $key) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsExtension(string $extension): bool
    {
        return 'blade.php' === $extension;
    }
}
