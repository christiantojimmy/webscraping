<?php
error_reporting(E_ALL & ~E_WARNING);

require_once(dirname(__FILE__) . '/classes/Crawler.php');
require_once(dirname(__FILE__) . '/classes/Tiket.php');
require_once(dirname(__FILE__) . '/classes/Booking.php');

$start = microtime(true);

$crawler = new Crawler();
$tiket = new Tiket();
$booking = new Booking();

$responses = $crawler->multi_curl_resp($tiket->get_search_post_curl_opt(10));
foreach ( $responses as $resp ) {
    print_r($crawler->extract_data($resp, Tiket::XPATH_SEARCH_RESULT_HOTEL_NAME));
    print_r($crawler->extract_data($resp, Tiket::XPATH_SEARCH_RESULT_HOTEL_LINK));
    print_r($crawler->extract_data($resp, Tiket::XPATH_SEARCH_RESULT_HOTEL_PRICE));
}

$responses = $crawler->multi_curl_resp($booking->get_search_get_curl_opt(10));
foreach ( $responses as $resp ) {
    print_r($crawler->extract_data($resp, Booking::XPATH_SEARCH_RESULT_HOTEL_NAME));
    print_r($crawler->extract_data($resp, Booking::XPATH_SEARCH_RESULT_HOTEL_LINK));
    print_r($crawler->extract_data($resp, Booking::XPATH_SEARCH_RESULT_HOTEL_PRICE));
}

$time_elapsed_secs = microtime(true) - $start;
echo "Time elapsed $time_elapsed_secs s" . PHP_EOL;
