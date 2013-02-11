{def $opengraph_metadata = $pagedata.persistent_variable.opengraph}

{if count( $opengraph_metadata )}
    {foreach $opengraph_metadata as $key => $value}
        {if is_array( $value )}
            {foreach $value as $item}
                <meta property="{$key|wash}" content="{$item|wash}" />
            {/foreach}
        {else}
            <meta property="{$key|wash}" content="{$value|wash}" />
        {/if}
    {/foreach}
{/if}
