<?php

namespace Jamosaur\Lastfm;

class Geo extends Lastfm
{
    /**
     * Geo constructor.
     *
     * @param string $apiKey
     * @param string $apiSecret
     */
    public function __construct($apiKey, $apiSecret)
    {
        parent::__construct($apiKey, $apiSecret);
        $this->__setSection('geo');
    }

    /**
     * Get the most popular artists on Last.fm by country
     *
     * @param string $country
     * @param int $limit
     * @param int $page
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getTopArtists($country, $limit = 50, $page = 1)
    {
        $this->__setCall('getTopArtists');
        $this->__makeCall([
            'country'   => $country,
            'limit'     => $limit,
            'page'      => $page,
        ]);
    }

    /**
     * Get the most popular tracks on Last.fm last week by country
     *
     * @param $country
     * @param int $limit
     * @param int $page
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getTopTracks($country, $limit = 50, $page = 1)
    {
        $this->__setCall('getTopTracks');
        $this->__makeCall([
            'country'   => $country,
            'limit'     => $limit,
            'page'      => $page,
        ]);
    }
}