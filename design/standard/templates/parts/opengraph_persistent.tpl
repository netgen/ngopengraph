{if and( is_set( $pagedata.persistent_variable.opengraph ), $pagedata.persistent_variable.opengraph|count )}
    {foreach $pagedata.persistent_variable.opengraph as $key => $value}
        {if is_array( $value )}
            {foreach $value as $item}
                <meta property="{$key|wash}" content="{$item|wash}" />
            {/foreach}
        {else}
            <meta property="{$key|wash}" content="{$value|wash}" />
        {/if}
    {/foreach}
{/if}
