# Translation extractor

[![Latest Version](https://img.shields.io/github/release/php-translation/extractor.svg?style=flat-square)](https://github.com/php-translation/extractor/releases)
[![Build Status](https://travis-ci.org/php-translation/extractor.svg?branch=master)](http://travis-ci.org/php-translation/extractor)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/php-translation/extractor.svg?style=flat-square)](https://scrutinizer-ci.com/g/php-translation/extractor)
[![Quality Score](https://img.shields.io/scrutinizer/g/php-translation/extractor.svg?style=flat-square)](https://scrutinizer-ci.com/g/php-translation/extractor)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/a5ae0cbe-f213-4ba6-94da-e9cffed86256/mini.png)](https://insight.sensiolabs.com/projects/a5ae0cbe-f213-4ba6-94da-e9cffed86256)
[![Total Downloads](https://img.shields.io/packagist/dt/php-translation/extractor.svg?style=flat-square)](https://packagist.org/packages/php-translation/extractor)

**Extract translation messages from source code**


## Install

Via Composer

``` bash
$ composer require php-translation/extractor
```

## Usage

```php
$extractor = new Extractor();

// Create extractor for PHP files
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

Is it something we do not extract? Please add it as a tests. Add a new file with your example code in
`tests/Resources/Github/Issue_XX.php` then edit the `AllExtractorsTest` to make sure the translation
key is found. 

```php

// ...
$this->translationExists($sc, 'trans.issue_xx');

```
