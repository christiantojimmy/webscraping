<?php

namespace crawl;

class Crawler
{
    /*
     * Use for multiple curl request
     * Params: $curl_opt (array)
     */
    function multi_curl_resp($curl_opt) {
        $curly = array();
        $result = array();
        $mh = curl_multi_init();

        for($i=0; $i<count($curl_opt); $i++) {
            $curly[$i] = curl_init();
            curl_setopt_array($curly[$i], $curl_opt[$i]);
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

    /*
     * Use for single curl request
     * Params: $curl_opt (singleton)
     */
    function curl_resp($curl_opt)
    {
        $curl = curl_init();
        curl_setopt_array($curl, $curl_opt);
        $resp = curl_exec($curl);

        if(!$resp) {
            return false;
        }else{
            curl_close($curl);
            return $resp;
        }
    }

    /*
     * Use for extract data from curl response
     * Parameter: curl_response, xpath_expression:string
     * Result: array of string
     */
    function extract_data($curl_response, $xpath_expression)
    {
        $doc = new \DOMDocument();
        $doc->loadHTML($curl_response);
        $xpath = new \DOMXPath($doc);
        $elements = $xpath->query($xpath_expression);

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

    /*
     * Use for fill data from extracted data by method extract_data
     * Parameter: class_name:string, arr_attribute_name:arr[string], arr_extracted_data:arr[string]
     * Result: array of class_name
     */
    function fill_data($class_name, $arr_attribute_name, $arr_extracted_data)
    {
        $valid = true;
        if(count($arr_extracted_data) != count($arr_attribute_name)) {
            $valid = false;
        }else{
            $arr_length = count($arr_extracted_data[0]);

            foreach($arr_extracted_data as $extracted_data) {
                if($arr_length != count($extracted_data)) {
                    $valid = false;
                }
            }
        }

        if($valid) {
            $result = array();

            for($i=0; $i<count($arr_extracted_data[0]); $i++) {
                $class    = new \ReflectionClass($class_name);
                $instance = $class->newInstanceArgs(array());

                for($j=0; $j<count($arr_attribute_name); $j++) {
                    $attribute_name = $arr_attribute_name[$j];
                    $data           = $arr_extracted_data[$j][$i];
                    $instance->setAttribute($attribute_name, $data);
                }

                $result[] = $instance;
            }

            return $result;
        }else{
            return null;
        }
    }

}