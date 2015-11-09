<?php
//##copyright##

if (iaView::REQUEST_HTML == $iaView->getRequestType() && $iaView->blockExists('valutakg_rates'))
{
	require_once IA_INCLUDES . 'phpfastcache' . IA_DS . 'phpfastcache.php';

	$iaCache = phpFastCache('auto', array('path' => IA_CACHEDIR));

	$rates = $iaCache->get('valutakg_rates');
	if (null == $rates)
	{
		$iaXml = $iaCore->factory('xml');

		$url = 'http://m.valuta.kg/api/';
		if ('nbkr' == $iaCore->get('valutakg_type'))
		{
			$url .= 'nbkr';
		}

		if ($result = $iaXml->parse_file($url))
		{
			if ('nbkr' == $iaCore->get('valutakg_type'))
			{
				foreach ($result->currency as $currency)
				{
					$rates['rates'][(string)$currency->title_alias] = array(
						'title' => (string)$currency->title,
						'rate' => (string)$currency->rate,
						'direction' => (string)$currency->direction
					);
				}
				$rates['date'] = (string)$result->date;
			}
			else
			{
				foreach ($result->currency as $currency)
				{
					$rates['rates'][(string)$currency->title_alias] = array(
						'title' => (string)$currency->title,
						'buy' => (string)$currency->rates->buy_rate,
						'sell' => (string)$currency->rates->sell_rate,
						'date' => (string)$currency->rates->date_start,
					);
				}
				$rates['date'] = (string)$currency->rates->date_start;
			}
		}

		$iaCache->set('valutakg_rates', $rates, (int)$iaCore->get('valutkg_cachetime') * 60);
	}

	$iaView->assign('rates', $rates);
}