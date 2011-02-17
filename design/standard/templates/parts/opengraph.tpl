{if and(is_set($node_id), $node_id|gt(0))}
	{def $opengraph_metadata = opengraph($node_id)}

	{if count($opengraph_metadata)}
		{foreach $opengraph_metadata as $key => $value}
			<meta property="{$key}" content="{$value}" />
		{/foreach}
	{/if}
{/if}
