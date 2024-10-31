<?php

/*
Plugin Name: RK currency rates
Description: Информация о курсах обмена валюты, установленных Национальный банк РК (www.nationalbank.kz). Все данные берет с сайта www.nationalbank.kz (XML)
Version: 1.0
Author: Abu Bakr
Author URI: http://batas.kz/
*/

// Использование шорткодов в тексте виджета
add_filter('widget_text', 'do_shortcode');

// Курсы валют
add_shortcode('rk-currency-rates', 'rk_currency_rates');
function rk_currency_rates($atts) {
	$url = 'http://www.nationalbank.kz/rss/rates_all.xml';
	$str = '';
    if (@file_get_contents($url) !== false) {
		$rates = array(
			'AUD' => array('code' => 'au', 'title' => 'Австралийский доллар'),
			'GBP' => array('code' => 'gb', 'title' => 'Фунт стерлингов Великобритании'),
			'DKK' => array('code' => 'dk', 'title' => 'Датская крона'),
			'AED' => array('code' => 'ae', 'title' => 'Дирхам ОАЭ'),
			'USD' => array('code' => 'us', 'title' => 'Доллар США'),
			'EUR' => array('code' => 'eu', 'title' => 'Евро'),
			'CAD' => array('code' => 'ca', 'title' => 'Канадский доллар'),
			'CNY' => array('code' => 'cn', 'title' => 'Китайский юань женьминьби'),
			'KWD' => array('code' => 'kw', 'title' => 'Кувейтский динар'),
			'KGS' => array('code' => 'kg', 'title' => 'Киргизский сом'),
			'LVL' => array('code' => 'lv', 'title' => 'Латвийский лат'),
			'MDL' => array('code' => 'md', 'title' => 'Молдавский лей'),
			'NOK' => array('code' => 'no', 'title' => 'Норвежская крона'),
			'SAR' => array('code' => 'sa', 'title' => 'Саудовский риял'),
			'RUB' => array('code' => 'ru', 'title' => 'Российский рубль'),
			'XDR' => array('code' => 'mp', 'title' => 'СДР (СПЗ)'),
			'SGD' => array('code' => 'sg', 'title' => 'Сингапурский доллар'),
			'TRL' => array('code' => 'tr', 'title' => 'Турецкая лира'),
			'UZS' => array('code' => 'uz', 'title' => 'Узбекский сум'),
			'UAH' => array('code' => 'ua', 'title' => 'Украинская гривна'),
			'SEK' => array('code' => 'se', 'title' => 'Шведская крона'),
			'CHF' => array('code' => 'ch', 'title' => 'Швейцарский франк'),
			'EEK' => array('code' => 'ee', 'title' => 'Эстонская крона'),
			'KRW' => array('code' => 'kr', 'title' => 'Южно-корейская вона (Корея)'),
			'JPY' => array('code' => 'jp', 'title' => 'Японская йена'),
			'BYN' => array('code' => 'by', 'title' => 'Белорусский рубль'),
			'PLN' => array('code' => 'pl', 'title' => 'Польский злотый'),
			'ZAR' => array('code' => 'za', 'title' => 'Южно-африканский рэнд'),
			'TRY' => array('code' => 'tr', 'title' => 'Новая турецкая лира'),
			'HUF' => array('code' => 'hu', 'title' => 'Венгерский форинт'),
			'CZK' => array('code' => 'gz', 'title' => 'Чешская крона'),
			'TJS' => array('code' => 'tj', 'title' => 'Таджикский сомони'),
			'HKD' => array('code' => 'hk', 'title' => 'Гонконгский доллар'),
			'BRL' => array('code' => 'br', 'title' => 'Бразильский реал'),
			'MYR' => array('code' => 'my', 'title' => 'Малайзийский ринггит'),
			'AZN' => array('code' => 'az', 'title' => 'Азербайджанский манат'),
			'INR' => array('code' => 'in', 'title' => 'Индийская рупия'),
			'THB' => array('code' => 'th', 'title' => 'Таиландский бат'),
			'AMD' => array('code' => 'am', 'title' => 'Армянский драм'),
			'GEL' => array('code' => 'ge', 'title' => 'Грузинский лари'),
			'IRR' => array('code' => 'ir', 'title' => 'Иранский риал'),
			'MXN' => array('code' => 'mx', 'title' => 'Мексиканский песо'),
		);
		$xml = simplexml_load_file($url);
		$str .= '<div class="batasCurrencyRate"><h4><a href="http://'. $xml->channel->link .'" target="_blank">Национальный Банк РК</a></h4>';
		$str .= '<p>Дата обновления: '.$xml->channel->item->pubDate.'</p><table style="font-size:'.$atts['font'].'">';
		foreach ($xml->channel->item as $item) {
			$title = trim($item->title);
			if ($atts['all'] != 'true' && $title != 'RUB' && $title != 'EUR' && $title != 'USD') {
				continue;
			}
			if ($item->change > 0) {
				$change = '<span class="icon up"></span>';
			} elseif ($item->change >= 0) {
				$change = '';
			} else {
				$change = '<span class="icon down"></span>';
			}
			$str .= '<tr title="'.$rates[$title]['title'].'"><td>';
			$str .= '<img src="'.plugins_url('/img/blank.gif', __FILE__).'" class="flag flag-'.$rates[$title]['code'].'" alt="'.$title.'">';
			$str .= ' '.$title.'</td>';
			$str .= '<td>'.$item->description.' тг</td>';
			$str .= '<td>'.$change.' '.$item->change.'</td>';
			if ($atts['all'] == 'true') {
				$str .= '<td>'.$rates[$title]['title'].'</td></tr>';
			}
		}
		$str .= '</table>';
		if ($atts['all'] != 'true') {
			$str .= '<div class="more"><a href="'.$atts['link'].'">Подробнее &raquo;</a></div>';
		}
		$str .= '</div>';
	} else {
		$str .= 'Сервис временно недоступен. Сервер временно не может обработать ваш запрос из-за внутренних ошибок. Пожалуйста, попробуйте позднее.';
	}
	return $str;
}

// Подключить стили
function rk_currency_rates_style() {
    wp_enqueue_style('style', plugins_url('/css/style.css', __FILE__));
    wp_enqueue_style('flags', plugins_url('/css/flags.min.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'rk_currency_rates_style');
