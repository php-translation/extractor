# Translation extractor

[![Latest Version](https://img.shields.io/github/release/php-translation/extractor.svg?style=flat-square)](https://github.com/php-translation/extractor/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/php-translation/extractor.svg?style=flat-square)](https://packagist.org/packages/php-translation/extractor)

**Extract translation messages from source code**


## Install

Via Composer:

```bash
$ composer require php-translation/extractor
```

## Usage

```php
$extractor = new Extractor();

// Create an extractor for PHP files
$fileExtractor = new PHPFileExtractor();

// Add visitors
$fileExtractor->addVisitor(new ContainerAwareTrans());
$fileExtractor->addVisitor(new ContainerAwareTransChoice());
$fileExtractor->addVisitor(new FlashMessage());
$fileExtractor->addVisitor(new FormTypeChoices());

// Add the file extractor to Extactor
$extractor->addFileExtractor($fileExtractor);

// Define where the source code is
$finder = new Finder();
$finder->in('/foo/bar');

//Start extracting files
$sourceCollection = $extractor->extract($finder);
```

## Found an issue?

Is it something we do not extract? Please add it as a test. Add a new file with your example code in
`tests/Resources/Github/Issue_XX.php`, then edit the `AllExtractorsTest` to make sure the translation
key is found:

```php
// ...
$this->translationExists($sc, 'trans.issue_xx');
```
