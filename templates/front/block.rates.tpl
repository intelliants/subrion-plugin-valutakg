
{if $rates.rates}
	<table class="table table-striped table-condensed">
		{foreach $rates.rates as $currency}
			<tr>
				<td>{$currency.title}</td>
				{if 'nbkr' == $core.config.valutakg_type}
					<td class="text-right">{$currency.rate}</td>
					<td class="text-center"><i class="icon-arrow-{$currency.direction}"></i></td>
					<td class="text-center">{if $currency.direction == 'down'}<i class="i	fa fa-angle-{$currency.direction}" style="color:red" {else} <i class="i	fa fa-angle-{$currency.direction}" style="color:green"{/if} ></i></td>
				{else}
					<td class="text-right">{$currency.buy}</td>
					<td class="text-right">{$currency.sell}</td>
				{/if}
			</tr>
		{/foreach}
	</table>

	<p class="text-center text-small muted">
		Курсы установлены {$rates.updated.date}.<br>
		Статистика с сайта <a href="http://www.valuta.kg" target="_blank">valuta.kg</a>
	</p>
{else}
	{lang key='valutakg_no_rates'}
{/if}