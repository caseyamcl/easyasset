# Change Log
All notable changes to this project are documented in this file.

## 0.3.2 - 2017-08-29
### Added
 - License information in COMPOSER.json
### Changed
 - Moved `LICENSE` to `LICENSE.md`

## 0.3.1 - 2015-02-24
### Fixed
 - Fixed major typo in Silex `AssetServiceProvider`

## 0.3 - 2015-02-24
### Added
 - Created `AssetControllerInterface`
### Fixed
 - Fixed class detection in Silex `AssetServiceProvider`

## 0.2 - 2015-02-24
### Changed
- `JsCompiledAsset` class now uses JSqueeze version ~2.0
- Added `getMimeTypes()` method to `AssetController` base class so that MIMEs can be modified at runtime
- Add `assets.writer` and `assets.command` services to Silex provider
- Allow Silex provider to accept multiple paths for assets
- Added `write_on_compile` option for Silex provider
- Finished README
- Added `.gitattributes`
- Cleaned up code comments
- Added additional unit tests


## 0.1 - 2014-12-30
### Added
- This CHANGELOG file to track notable changes
- Initial release
