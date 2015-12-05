<?php
error_reporting(E_ALL & ~E_WARNING);

$file = "https://www.tokopedia.com/p";
$doc = new DOMDocument();
$doc->loadHTMLFile($file);
$xpath = new DOMXPath($doc);

echo '<pre>';
foreach(get_arr_value($xpath->query("//div[2]/div/div/div/div[2]/div[2]/div/ul/li/a")) as $key=>$category) {
    $elIndex = $key + 1;
    echo $category . PHP_EOL;

    foreach(get_arr_value($xpath->query("//div[2]/div/div/div/div[2]/div[2]/div/ul[$elIndex]/li/ul/li/a")) as $subcategory) {
        echo '-' . $subcategory . PHP_EOL;
    }

    echo PHP_EOL;
}

//print_r($xpath->query("//div[2]/div/div/div/div[2]/div[2]/div/ul[2]/li/ul/li/a"));
//print_r($xpath->query("//div[2]/div/div/div/div[2]/div[2]/div/ul")->length);
//print_r(get_arr_value($xpath->query("//div[2]/div/div/div/div[2]/div[2]/div/ul[1]/li/ul/li/a")));
//print_r($xpath->query("//div[2]/div/div/div/div[2]/div[2]/div/ul[1]/li/ul/li/a/@href"));
echo '</pre>';



function get_arr_value($elements)
{
    $arr = [];
    if (!is_null($elements)) {
        foreach ($elements as $element) {
            $nodes = $element->childNodes;
            foreach ($nodes as $node) {
               array_push($arr, $node->nodeValue);
            }
        }
    }

    return $arr;
}