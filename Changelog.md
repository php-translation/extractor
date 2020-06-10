# Change Log

The change log describes what is "Added", "Removed", "Changed" or "Fixed" between each release.

## UNRELEASED

## 2.0.2

### Fixed

- Update image used in github actions to fix CS errors #153
- Fixed error when using variable in transChoice() #151

### Added

- Knp menu extractors #152

## 2.0.1

### Fixed

- Avoid exception when calling `trans` with a variable.

### Added

- Added extractor for form field titles

## 2.0.0

- Add support of Symfony ^5.0
- Add strict type hinting
- Added `PHPFileExtractor::supportsExtension(): bool`
- Removed `PHPFileExtractor::getType()`
- Remove support of Twig 1.x (`Twig2Visitor` and `TwigVisitorFactory`)
- Remove support of PHP < 7.2
- Remove support of Symfony < 3.4

## 1.7.1

### Fixed

- Error when getting caller name with the `FlashMessage` extractor.

## 1.7.0

### Added

- Support for `@translate` annotation.
- Better handle `@ignore` annotation on FormTypeChoices

## 1.6.0

### Added

- Support for Symfony form help.

### Fixed

- Fixed issue where using the `@ignore` annotation ignored the wrong property.
- Do not run the Twig worker if we are not extracting.

## 1.5.2

### Fixed

- Fixed Fatal Error in FormTypeImplicit when using method call from variable

## 1.5.1

### Fixed

- Fixed bug where form option key `attr` is not an array.

## 1.5.0

### Added

- Support for `nikic/php-parser:^4`
- Support for `array_*` callback in `SourceLocation::createHere`
- Support for global 'translation_domain' in forms
- Support for `@Ignore` annotation in $builder->add to prevent implicit label

### Changes

- Make sure we do not extract implicit labels form HiddenType

### Fixed

- Added missing `sprintf` in `ValidationAnnotaion`
- Do not generate an error on "placeholder=>false"

## 1.4.0

### Added

- Support for `translation_domain` and `choice_translation_domain`

### Fixed

- Respect `"label" => false`
- Form type extractors will only operate on form type classes.

## 1.3.1

### Added

- Symfony 2.7 support

## 1.3.0

### Added

- Support for passing choice as variable
- Support for Symfony 4
- Support for `desc` annotation and twig filter
- Support for .phtml

## 1.2.0

### Added

- Support for PHPUnit6
- Extract translation from form's "empty_value"
- Extract translation from Validation messages

### Changed

- Added TwigVisitorFactory to create a TwigVisitor. TwigVisitor::create has been deprecated.

## 1.1.2

### Fixed

- Do not stop visiting a file when not class is not *Type.

### Added

- More test to prove correctness.

## 1.1.1

### Fixed

- Make sure we test with the lowest version on Travis
- Fixed minor bugs for Twig 1.x.

## 1.1.0

### Added

- Support for Twig 2.0.
- Support for reporting errors and silence errors with `@Ignore`.

### Deprecated

- `Twig\TranslationBlock` and `Twig\TranslationFilter`. Use `Twig\Twig1Visitor` instead.

## 1.0.0

## Added

- Extractor for classes implementing `TranslationSourceLocationContainer`
- Made classes final

## 0.1.1

### Added

- Blade file type extractor
- Placeholder extractor

## 0.1.0

Init release

