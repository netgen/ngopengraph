# Netgen Open Graph extension version history

## Version 1.0 (19.07.2010)

- Implemented Open Graph protocol (official and Facebook compatible)

## Version 1.1 (25.02.2011)

- Added support for like button localization
- Added XML datatype handler

## Version 1.2 (14.06.2011)

- Various bugfixes

## Version 1.3 (11.02.2013)

- Single Open Graph metadata value can now be an array, it will generate multiple meta tags with the same key
- Added persistent variant of `opengraph.tpl`, which can be used to generate Open Graph metadata through eZ Publish persistent variables
- Added `ezobjectrelationlist` datatype support to be able to attach multiple images to Open Graph metadata (thanks Geoff Bentley)
- `ezobjectrelation` datatype can now be used to set an image to Open Graph metadata (thanks Geoff Bentley)
- Added an option to write some debug data into eZ Publish debug log
- Various bug fixes
