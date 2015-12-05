<?php
error_reporting(E_ALL & ~E_WARNING);

$url    = 'http://www.tiket.com/search/hotel';

$start = microtime(true);
/*
 * Using multi curl
 */
$responses = post_multi_curl_resp($url);

foreach ( $responses as $resp ) {
    $doc = new DOMDocument();
    $doc->loadHTML($resp);
    $xpath = new DOMXPath($doc);
    print_r(get_arr_value($xpath->query("//ul/div/div/li/div/h3/a")));
    print_r(get_arr_value($xpath->query("//ul/div/div/li/div/h4/span/@rel")));
}

/*
 * Using single curl
 */
//for($i=1; $i< 11; $i++) {
//    $params = array(
//        'newlayout' => 1,
//        'q' => 'DKI Jakarta, Indonesia',
//        'startdate' => '2015-12-06',
//        'night' => 1 ,
//        'enddate' => '2015-12-07' ,
//        'room' => 1 ,
//        'adult' => 2,
//        'uid' =>'province:13',
//        'hotelname' => '',
//        'minprice' => '',
//        'maxprice' => '',
//        'page' => $i
//    );
//
//    $resp = post_curl_resp($url, $params);
//    if($resp) {
//        $doc = new DOMDocument();
//        $doc->loadHTML($resp);
//        $xpath = new DOMXPath($doc);
//        print_r(get_arr_value($xpath->query("//ul/div/div/li/div/h3/a")));
//        print_r(get_arr_value($xpath->query("//ul/div/div/li/div/h4/span/@rel")));
//    }
//}

$time_elapsed_secs = microtime(true) - $start;
echo "Time elapsed $time_elapsed_secs s" . PHP_EOL;

function post_multi_curl_resp($url) {
    echo "Starting curl" . PHP_EOL;

    $curly = array();
    $result = array();
    $mh = curl_multi_init();

    for($i=0; $i< 10; $i++) {
        $curly[$i] = curl_init();
        $params = array(
            'newlayout' => 1,
            'q' => 'DKI Jakarta, Indonesia',
            'startdate' => '2015-12-06',
            'night' => 1 ,
            'enddate' => '2015-12-07' ,
            'room' => 1 ,
            'adult' => 2,
            'uid' =>'province:13',
            'hotelname' => '',
            'minprice' => '',
            'maxprice' => '',
            'page' => $i + 1
        );
        $post_fields = http_build_query($params);

        curl_setopt_array($curly[$i], array(
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13',
            CURLOPT_HTTPHEADER => array("X-Requested-With: XMLHttpRequest"),
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_VERBOSE => FALSE,
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $post_fields
        ));

        curl_multi_add_handle($mh, $curly[$i]);
    }

    $running = null;
    do {
        curl_multi_exec($mh, $running);
    } while ($running > 0);

    foreach($curly as $id => $c) {
        $result[$id] = curl_multi_getcontent($c);
        curl_multi_remove_handle($mh, $c);
    }

    curl_multi_close($mh);

    return $result;
}

function post_curl_resp($url, $params)
{
    echo "Starting curl" . PHP_EOL;

    $curl = curl_init();

    $post_fields = http_build_query($params);

    curl_setopt_array($curl, array(
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13',
        CURLOPT_HTTPHEADER => array("X-Requested-With: XMLHttpRequest"),
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_VERBOSE => FALSE,
        CURLOPT_POST => TRUE,
        CURLOPT_POSTFIELDS => $post_fields
    ));

    $resp = curl_exec($curl);

    if(!$resp) {
        return false;
    }else{
        curl_close($curl);
        return $resp;
    }
}

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