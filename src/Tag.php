<?php

namespace Jamosaur\Lastfm;

class Tag extends Lastfm
{
    /**
     * Tag constructor.
     *
     * @param $apiKey
     * @param $apiSecret
     * @param $sessionKey
     */
    public function __construct($apiKey, $apiSecret, $sessionKey)
    {
        parent::__construct($apiKey, $apiSecret, $sessionKey);
        $this->__setSection('tag');
    }

    /**
     * Get the metadata for a tag
     *
     * @param string $tag
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getInfo($tag)
    {
        $this->__setCall('getInfo');

        return $this->__makeCall([
            'tag'  => $tag,
            'lang' => $this->getLanguage(),
        ]);
    }

    /**
     * Search for tags similar to this one. Returns tags ranked by similarity, based on listening data.
     *
     * @param string $tag
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getSimilar($tag)
    {
        $this->__setCall('getSimilar');

        return $this->__makeCall([
            'tag' => $tag,
        ]);
    }

    /**
     * Get the top albums tagged by this tag, ordered by tag count.
     *
     * @param string $tag
     * @param int $limit
     * @param int $page
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getTopAlbums($tag, $limit = 50, $page = 1)
    {
        $this->__setCall('getTopAlbums');

        return $this->__makeCall([
            'tag'   => $tag,
            'limit' => $limit,
            'page'  => $page,
        ]);
    }

    /**
     * Get the top artists tagged by this tag, ordered by tag count.
     *
     * @param string $tag
     * @param int $limit
     * @param int $page
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getTopArtists($tag, $limit = 50, $page = 1)
    {
        $this->__setCall('getTopArtists');

        return $this->__makeCall([
            'tag'   => $tag,
            'limit' => $limit,
            'page'  => $page,
        ]);
    }

    /**
     * Fetches the top global tags on Last.fm, sorted by popularity (number of times used)
     *
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getTopTags()
    {
        $this->__setCall('getTopTags');

        return $this->__makeCall();
    }

    /**
     * Get the top tracks tagged by this tag, ordered by tag count.
     *
     * @param string $tag
     * @param int $limit
     * @param int $page
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getTopTracks($tag, $limit = 50, $page = 1)
    {
        $this->__setCall('getTopTracks');

        return $this->__makeCall([
            'tag'   => $tag,
            'limit' => $limit,
            'page'  => $page,
        ]);
    }

    /**
     * Get a list of available charts for this tag, expressed as date ranges which can be sent to the chart services.
     *
     * @param string $tag
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getWeeklyChartList($tag)
    {
        $this->__setCall('getWeeklyChartList');

        return $this->__makeCall([
            'tag' => $tag,
        ]);
    }
}
