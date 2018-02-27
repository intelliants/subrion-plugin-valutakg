<?php

if (iaView::REQUEST_HTML == $iaView->getRequestType() && $iaView->blockExists('valutakg_rates')) {
    $iaXml = $iaCore->factory('xml');
    $service_url = 'https://valuta.kg/api/rate/';

    if ('nbkr' == $iaCore->get('valutakg_type')){
        $service_url .= 'nbkr.json';
    }

    if('average' == $iaCore->get('valutakg_type')){
        $service_url .= 'average.json';
    }

    $curl_response = iaUtil::getPageContent($service_url);
    $result = json_decode($curl_response, true);

    //$rates = array();

    if ('nbkr' == $iaCore->get('valutakg_type')) {
        foreach ($result['data']['rates'] as $key => $value) {
            $rates[] = array(
                'title' => $key,
                'rate' => $value[0],
                'direction' => $value[1]
            );
        }
        $rates['updated']['date']=$result['data']['last_update'];
    }
    else{
        foreach ($result['data'] as $key=> $value){
            $rates[] = array(
                'title' => $value['title'],
                'buy' => $value['rates']['buy_rate'],
                'sell' => $value['rates']['sell_rate'],
                'date' => $value['rates']['date_start']
            );
        }
        $rates['updated']['date']=$value['rates']['date_start'];
    }

   $iaView->assign('rates', $rates);
}