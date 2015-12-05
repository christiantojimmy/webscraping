<?php

class Tiket
{
    /*
     * url: http://www.tiket.com/search/hotel
     * method: post
     */
    private $SEARCH_POST_URL = "http://www.tiket.com/search/hotel";
    private $SEARCH_POST_PARAMS = array(
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
        'page' => 0
    );
    private $SEARCH_POST_CURL_OPT = array(
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13',
        CURLOPT_HTTPHEADER => array("X-Requested-With: XMLHttpRequest"),
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_VERBOSE => FALSE,
        CURLOPT_POST => TRUE
    );

    public function get_search_post_curl_opt($num_of_request) {
        $opts = array();

        for($i=0; $i<$num_of_request; $i++) {
            $params = $this->SEARCH_POST_PARAMS;
            $params['page'] = $i + 1;
            $post_fields = http_build_query($params);
            $opt = $this->SEARCH_POST_CURL_OPT;
            $opt[CURLOPT_URL] = $this->SEARCH_POST_URL;
            $opt[CURLOPT_POSTFIELDS] = $post_fields;

            $opts[$i] = $opt;
        }

        return $opts;
    }
}