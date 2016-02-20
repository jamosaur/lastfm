<?php

namespace Jamosaur\Lastfm;

class Chart extends Lastfm
{
    /**
     * Chart constructor.
     *
     * @param string $apiKey
     * @param string $apiSecret
     * @param string $sessionKey
     */
    public function __construct($apiKey, $apiSecret, $sessionKey)
    {
        parent::__construct($apiKey, $apiSecret, $sessionKey);
        $this->__setSection('chart');
    }

    /**
     * Get the top artists chart
     *
     * @param int $page
     * @param int $limit
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getTopArtists($page = 1, $limit = 50)
    {
        $this->__setCall('getTopArtists');
        $this->__makeCall([
            'page'  => $page,
            'limit' => $limit,
        ]);
    }

    /**
     * Get the top artists chart
     *
     * @param int $page
     * @param int $limit
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getTopTags($page = 1, $limit = 50)
    {
        $this->__setCall('getTopTags');
        $this->__makeCall([
            'page'  => $page,
            'limit' => $limit,
        ]);
    }

    /**
     * Get the top tracks chart
     *
     * @param int $page
     * @param int $limit
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getTopTracks($page = 1, $limit = 50)
    {
        $this->__setCall('getTopTracks');
        $this->__makeCall([
            'page'  => $page,
            'limit' => $limit,
        ]);
    }
}
