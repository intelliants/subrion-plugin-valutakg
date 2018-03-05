<?php
/******************************************************************************
 *
 * Subrion - open source content management system
 * Copyright (C) 2018 Intelliants, LLC <https://intelliants.com>
 *
 * This file is part of Subrion.
 *
 * Subrion is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Subrion is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Subrion. If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @link https://subrion.org/
 *
 ******************************************************************************/

if (iaView::REQUEST_HTML == $iaView->getRequestType() && $iaView->blockExists('valutakg_rates'))
{
    $url = 'https://valuta.kg/api/rate/';

    $rates = $this->iaCache->get('valutakg_rates', 3600, true);

    if(false === $rates){

        if ('nbkr' == $iaCore->get('valutakg_type')){
            $url .= 'nbkr.json';
        }
        if('average' == $iaCore->get('valutakg_type')){
            $url .= 'average.json';
        }

        $curl_response = iaUtil::getPageContent($url);
        $result = json_decode($curl_response, true);

        if ('nbkr' == $iaCore->get('valutakg_type'))
        {
            foreach ($result['data']['rates'] as $key => $value) {
                $rates['rates'][$key] = array(
                    'title' => $key,
                    'rate' => $value[0],
                    'direction' => $value[1]
                );
            }
            $rates['updated']['date']=$result['data']['last_update'];
        }
        else
        {
            foreach ($result['data'] as $key=> $value){
                $rates['rates'][$key] = array(
                    'title' => $value['title'],
                    'buy' => $value['rates']['buy_rate'],
                    'sell' => $value['rates']['sell_rate'],
                    'date' => $value['rates']['date_start']
                );
            }
            $rates['updated']['date']=$value['rates']['date_start'];
        }

        $this->iaCache->write('valutakg_rates', $rates);
    }

	$iaView->assign('rates', $rates);
}