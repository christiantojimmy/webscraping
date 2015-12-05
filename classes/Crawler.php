<?php

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
}