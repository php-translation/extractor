# Change Log

The change log describes what is "Added", "Removed", "Changed" or "Fixed" between each release. 

## UNRELEASED

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


