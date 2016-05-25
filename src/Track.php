<?php

namespace Jamosaur\Lastfm;

use Jamosaur\Lastfm\Exceptions\RequiredParameterMissingException;
use Jamosaur\Lastfm\Exceptions\TooManyTagsException;

class Track extends Lastfm
{
    /**
     * Track constructor.
     *
     * @param string $apiKey
     * @param string $apiSecret
     * @param null $sessionKey
     */
    public function __construct($apiKey, $apiSecret, $sessionKey)
    {
        parent::__construct($apiKey, $apiSecret, $sessionKey);
        $this->__setSection('track');
    }

    /**
     * Tag an album using a list of user supplied tags.
     *
     * @param $artist
     * @param $track
     * @param $tags
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiredParameterMissingException
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     * @throws \Jamosaur\Lastfm\Exceptions\TooManyTagsException
     */
    public function addTags($artist, $track, $tags)
    {
        $this->__setCall('addTags');
        if (is_null($artist) || is_null($track) || is_null($tags)) {
            throw new RequiredParameterMissingException;
        }
        if (!is_array($tags) && count(explode(',', $tags)) > 10 || is_array($tags) && count($tags) > 10) {
            throw new TooManyTagsException;
        }
        if (is_array($tags)) {
            $tags = implode(',', $tags);
        }

        return $this->__makeCall([
            'artist' => $artist,
            'track'  => $track,
            'tags'   => $tags,
        ], true);
    }

    /**
     * Use the last.fm corrections data to check whether the supplied track has a correction to a canonical track
     *
     * @param string $artist
     * @param string $track
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getCorrection($artist, $track)
    {
        $this->__setCall('getCorrection');

        return $this->__makeCall([
            'artist' => $artist,
            'track'  => $track,
        ]);
    }

    /**
     * @param null $artist
     * @param null $track
     * @param null $mbid
     * @param null $username
     * @param int $autocorrect
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiredParameterMissingException
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getInfo($artist = null, $track = null, $mbid = null, $username = null, $autocorrect = 0)
    {
        $this->requiredUnlessMBID($artist, $track, $mbid);
        $this->__setCall('getInfo');

        return $this->__makeCall([
            'artist'      => $artist,
            'track'       => $track,
            'mbit'        => $mbid,
            'username'    => $username,
            'autocorrect' => $autocorrect,
        ]);
    }

    /**
     * Throws RequiredParameterMissingException if artist/track is null as well as mbid
     *
     * @param string $artist
     * @param string $track
     * @param int $mbid
     * @throws \Jamosaur\Lastfm\Exceptions\RequiredParameterMissingException
     */
    private function requiredUnlessMBID($artist, $track, $mbid)
    {
        if ($artist == null && $mbid == null || $track == null && $mbid == null) {
            throw new RequiredParameterMissingException;
        }
    }

    /**
     * @param null $artist
     * @param null $track
     * @param null $mbid
     * @param null $username
     * @param int $autocorrect
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiredParameterMissingException
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getSimilar($artist = null, $track = null, $mbid = null, $username = null, $autocorrect = 0)
    {
        $this->requiredUnlessMBID($artist, $track, $mbid);
        $this->__setCall('getSimilar');

        return $this->__makeCall([
            'artist'      => $artist,
            'track'       => $track,
            'mbit'        => $mbid,
            'username'    => $username,
            'autocorrect' => $autocorrect,
        ]);
    }

    /**
     * @param null $artist
     * @param null $track
     * @param null $mbid
     * @param null $username
     * @param int $autocorrect
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiredParameterMissingException
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getTags($artist = null, $track = null, $mbid = null, $username = null, $autocorrect = 0)
    {
        $this->requiredUnlessMBID($artist, $track, $mbid);
        $this->__setCall('getTags');

        return $this->__makeCall([
            'artist'      => $artist,
            'track'       => $track,
            'mbit'        => $mbid,
            'user'        => $username,
            'autocorrect' => $autocorrect,
        ]);
    }

    /**
     * @param null $artist
     * @param null $track
     * @param null $mbid
     * @param int $autocorrect
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiredParameterMissingException
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getTopTags($artist = null, $track = null, $mbid = null, $autocorrect = 0)
    {
        $this->requiredUnlessMBID($artist, $track, $mbid);
        $this->__setCall('getTopTags');

        return $this->__makeCall([
            'artist'      => $artist,
            'track'       => $track,
            'mbit'        => $mbid,
            'autocorrect' => $autocorrect,
        ]);
    }

    /**
     * @param null $artist
     * @param null $track
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function love($artist = null, $track = null)
    {
        $this->__setCall('love');

        return $this->__makeCall([
            'artist' => $artist,
            'track'  => $track,
        ], true);
    }

    /**
     * @param $artist
     * @param $track
     * @param $tag
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function removeTag($artist, $track, $tag)
    {
        $this->__setCall('removeTag');

        return $this->__makeCall([
            'artist' => $artist,
            'track'  => $track,
            'tag'    => $tag,
        ], true);
    }

    /**
     * @param $track
     * @param null $artist
     * @param int $limit
     * @param int $page
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function search($track, $artist = null, $limit = 50, $page = 1)
    {
        $this->__setCall('search');

        return $this->__makeCall([
            'track'  => $track,
            'artist' => $artist,
            'limit'  => $limit,
            'page'   => $page,
        ]);
    }

    /**
     * @param $track
     * @param $artist
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function unlove($track, $artist)
    {
        $this->__setCall('unlove');

        return $this->__makeCall([
            'track'  => $track,
            'artist' => $artist,
        ], true);
    }
}
