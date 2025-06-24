<?php

namespace App\Services;

class GeoCoder
{
    private $endpoint = 'https://eu1.locationiq.com/v1/search.php?key=b35afe4d43dfee&postalcode=';
    private $results;
    private $postcode;
    private $status;

    public function __construct($postcode)
    {
        $this->postcode = urlencode($postcode);
        $this->doLookup();
    }


    public function getResults()
    {
        return $this->results;
    }

    public function storeResults($validated)
    {
        $validated['lat'] = $this->results[0]->lat;
        $validated['lng'] = $this->results[0]->lon;
        return $validated;
    }

    public function getStatus()
    {
        return $this->status;
    }

    private function doLookup()
    {
        $returnurl = $this->endpoint . $this->postcode . '&format=json';
        $ch1 = curl_init();
        if (!$ch1)
            die("Couldn't initialize a cURL handle");
        curl_setopt($ch1, CURLOPT_URL, $returnurl);
        curl_setopt($ch1, CURLOPT_HEADER, 0);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch1, CURLOPT_TIMEOUT, 1 * 60);
        $result = curl_exec($ch1);
        $this->results = json_decode($result);
        $this->status = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
        curl_close($ch1);
    }
}
