<?php
error_reporting(E_ALL & ~E_WARNING);

require_once(dirname(__FILE__) . '/classes/crawl/Crawler.php');
require_once(dirname(__FILE__) . '/classes/crawl/Tiket.php');
require_once(dirname(__FILE__) . '/classes/crawl/Booking.php');
require_once(dirname(__FILE__) . '/classes/data/DataSearchHotelResult.php');

$start = microtime(true);

$crawler     = new crawl\Crawler();
$tiket       = new crawl\Tiket();
$booking     = new crawl\Booking();
$dataTiket   = array();
$dataBooking = array();
$dataMerge   = array();

$responses = $crawler->multi_curl_resp($tiket->get_search_post_curl_opt(10));
foreach ( $responses as $resp ) {
    $arrHotelName  = $crawler->extract_data($resp, crawl\Tiket::XPATH_SEARCH_RESULT_HOTEL_NAME);
    $arrHotelLink  = $crawler->extract_data($resp, crawl\Tiket::XPATH_SEARCH_RESULT_HOTEL_LINK);
    $arrHotelPrice = $crawler->extract_data($resp, crawl\Tiket::XPATH_SEARCH_RESULT_HOTEL_PRICE);
    $data          = $crawler->fill_data('data\DataSearchHotelResult', array("name", "link", "price"), array($arrHotelName, $arrHotelLink, $arrHotelPrice));
    $dataTiket     = array_merge($dataTiket, $data);
}

$responses = $crawler->multi_curl_resp($booking->get_search_get_curl_opt(10));
foreach ( $responses as $resp ) {
    $arrHotelName  = $crawler->extract_data($resp, crawl\Booking::XPATH_SEARCH_RESULT_HOTEL_NAME);
    $arrHotelLink  = $crawler->extract_data($resp, crawl\Booking::XPATH_SEARCH_RESULT_HOTEL_LINK);
    $arrHotelPrice = $crawler->extract_data($resp, crawl\Booking::XPATH_SEARCH_RESULT_HOTEL_PRICE);
    $data          = $crawler->fill_data('data\DataSearchHotelResult', array("name", "link", "price"), array($arrHotelName, $arrHotelLink, $arrHotelPrice));
    $dataBooking   = array_merge($dataBooking, $data);
}

$dataMerge = array_merge($dataTiket, $dataBooking);
$time_elapsed_secs = microtime(true) - $start;
echo "Time elapsed $time_elapsed_secs s" . PHP_EOL;
?>

<html>
<body>
<table>
    <thead>
        <tr>
            <th>Ota</th>
            <th>Property Name</th>
            <th>Price</th>
            <th>Property Link</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($dataMerge as $output) { ?>
            <tr>
                <td><?= $output->getOta() ?></td>
                <td><?= $output->getName() ?></td>
                <td><?= $output->getPrice() ?></td>
                <td><a href="<?= $output->getLink() ?>">Click here</a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
</body>
</html>
