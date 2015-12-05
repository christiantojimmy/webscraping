<?php
error_reporting(E_ALL & ~E_WARNING);

require_once(dirname(__FILE__) . '/classes/Crawler.php');
require_once(dirname(__FILE__) . '/classes/Tiket.php');

$start = microtime(true);

$crawler = new Crawler();
$tiket = new Tiket();

$responses = $crawler->multi_curl_resp($tiket->get_search_post_curl_opt(10));
foreach ( $responses as $resp ) {
    $doc = new DOMDocument();
    $doc->loadHTML($resp);
    $xpath = new DOMXPath($doc);
    print_r(get_arr_value($xpath->query("//ul/div/div/li/div/h3/a")));
    print_r(get_arr_value($xpath->query("//ul/div/div/li/div/h4/span/@rel")));
}

$time_elapsed_secs = microtime(true) - $start;
echo "Time elapsed $time_elapsed_secs s" . PHP_EOL;


function get_arr_value($elements)
{
    $arr = [];
    if (!is_null($elements)) {
        foreach ($elements as $element) {
            $nodes = $element->childNodes;
            foreach ($nodes as $node) {
                array_push($arr, trim($node->nodeValue));
            }
        }
    }

    return $arr;
}