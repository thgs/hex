# Changelog

Format based on [Keep a changelog](https://keepachangelog.com/).

Project uses [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.0.11] - 2020-08-02

### Added

- Separate Formatter
- Separate Display output, + Stream Display Output
- Separate I/O into `IOStream`
- Operate On copy
- Custom Exceptions (`FileNotFound`, `StreamNotInitialised`, `UnableToCopyFile` all extending `Hexception`)
- composer.lock on .gitignore
- Multiple random comments
- This changelog

### Changed

- `Dump()` method signature

### Fixed

- `Dump()` to actually move into starting position


## [0.0.1] - 2020-07-31

### Added

- Draft first implementation


[unreleased]: https://github.com/thgs/hex/compare/v0.0.11...HEAD
[0.0.11]: https://github.com/thgs/hex/compare/v0.0.11...v0.0.1
[0.0.1]: https://github.com/thgs/hex/releases/tag/v0.0.1