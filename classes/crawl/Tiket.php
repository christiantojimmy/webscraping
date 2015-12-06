<?php

namespace crawl;

class Tiket
{
    /*
     * Static XPath Variable
     */
    const XPATH_SEARCH_RESULT_HOTEL_NAME    = "//ul/div/div/li/div/h3/a";
    const XPATH_SEARCH_RESULT_HOTEL_LINK    = "//ul/div/div/li/div/h3/a/@href";
    const XPATH_SEARCH_RESULT_HOTEL_PRICE   = "//ul/div/div/li/div/h4/span/@rel";


    /*
     * url: http://www.tiket.com/search/hotel
     * method: post
     */
    private $SEARCH_POST_URL    = "http://www.tiket.com/search/hotel";
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

    public function get_search_post_curl_opt($num_of_pages) {
        $opts = array();

        for($i=0; $i<$num_of_pages; $i++) {
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