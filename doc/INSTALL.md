# Netgen Open Graph extension installation instructions

## Installation

### Unpack/unzip

Unpack the downloaded package into the `extension` directory of your eZ Publish installation.

### Activate extension

Activate the extension by using the admin interface ( Setup -> Extensions ) or by
prepending `ngopengraph` to `ActiveExtensions[]` in `settings/override/site.ini.append.php`:

    [ExtensionSettings]
    ActiveExtensions[]=ngopengraph

### Regenerate autoload array

Run the following from your eZ Publish root folder

    php bin/php/ezpgenerateautoloads.php --extension

Or go to Setup -> Extensions and click the "Regenerate autoload arrays" button

### Configure the extension

Copy `ngopengraph.ini` to your extension and configure the extension. Detailed instructions are within the ini file.

### Modify your templates

Edit your templates to include available template files from `extension/ngopengraph/design/standard/templates/parts`:

1) `opengraph_persistent.tpl` - edit your pagelayout.tpl and include the following lines between `<head>` and `</head>` tags:

    {include uri="design:parts/opengraph_persistent.tpl"}

This template is the variant of now deprecated `opengraph.tpl` and uses persistent variables to set Open Graph metadata.
When using this template you need to include `opengraph_set_persistent.tpl` template in each of your full view templates
of classes that display Open Graph metadata

    {include uri="design:parts/opengraph_set_persistent.tpl"}

If you wish to use the deprecated `opengraph.tpl` instead of persistent variant, include the following in your pagelayout:

    {if is_set( $module_result.node_id )}
        {include uri="design:parts/opengraph.tpl" node_id=$module_result.node_id}
    {/if}

In that case, you do not need `opengraph_set_persistent.tpl` in your full view template files.

2) `facebook_like.tpl` - edit one of your view templates and include the following lines:

    {include uri="design:parts/facebook_like.tpl" node=$node}

Be careful not to include this template in classes who don't have any Open Graph meta data.

### Clear caches

Clear all caches (from admin 'Setup' tab or from command line).
