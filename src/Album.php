<?php

namespace Jamosaur\Lastfm;

use Jamosaur\Lastfm\Exceptions\RequiredParameterMissingException;
use Jamosaur\Lastfm\Exceptions\TooManyTagsException;
use Jamosaur\Lastfm\Exceptions\UserRequiredUnlessAuthException;

class Album extends Lastfm
{
    /**
     * Album constructor.
     *
     * @param string $apiKey
     * @param string $apiSecret
     * @param string $sessionKey
     */
    public function __construct($apiKey, $apiSecret, $sessionKey)
    {
        parent::__construct($apiKey, $apiSecret, $sessionKey);
        $this->__setSection('album');
    }

    /**
     * Add up to 10 tags to an album.
     * Requires authentication.
     *
     * @param string $artist        | Artist Name
     * @param string $album         | Album Name
     * @param array|string $tags    | Tags, maximum of 10
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     * @throws \Jamosaur\Lastfm\Exceptions\TooManyTagsException
     * @throws \Jamosaur\Lastfm\Exceptions\RequiredParameterMissingException
     */
    public function addTags($artist, $album, $tags)
    {
        $this->__setCall('addTags');
        if (is_null($artist) || is_null($album) || is_null($tags)) {
            throw new RequiredParameterMissingException;
        }
        if (!is_array($tags) && count(explode(',', $tags)) > 10 || is_array($tags) && count($tags) > 10) {
            throw new TooManyTagsException;
        }
        if (is_array($tags)) {
            $tags = implode(',', $tags);
        }
        return $this->__makeCall([
            'artist'    => $artist,
            'album'     => $album,
            'tags'      => $tags,
        ], true);
    }

    /**
     * Get album info.
     *
     * @param string $artist          | Artist Name | Required unless mbid is provided
     * @param string $album           | Album Name | Required unless mbid is provided
     * @param string $mbid            | musicbrainz ID
     * @param boolean $autocorrect  | Corrects artist names
     * @param string $username        | returns user's placount for album if provided
     * @param string $lang            | Language to return biography in, uses ISO-639 alpha-2
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     * @throws \Jamosaur\Lastfm\Exceptions\RequiredParameterMissingException
     */
    public function getInfo(
        $artist = null,
        $album = null,
        $mbid = null,
        $autocorrect = false,
        $username = null,
        $lang = null
    ) {
        $this->requiredUnlessMBID($artist, $album, $mbid);
        $this->__setCall('getInfo');
        return $this->__makeCall([
            'artist'        => $artist,
            'album'         => $album,
            'mbid'          => $mbid,
            'autocorrect'   => $autocorrect,
            'username'      => $username,
            'lang'          => (parent::getLanguage()) ? parent::getLanguage() : $lang,
        ]);
    }

    /**
     * Get tags for an album.
     * User is required if authentication hasn't occurred
     *
     * @param string $artist
     * @param string $album
     * @param int $mbid
     * @param bool $autocorrect
     * @param string $user
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiredParameterMissingException
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     * @throws \Jamosaur\Lastfm\Exceptions\UserRequiredUnlessAuthException
     */
    public function getTags($artist = null, $album = null, $mbid = null, $autocorrect = false, $user = null)
    {
        $this->requiredUnlessMBID($artist, $album, $mbid);
        if ($this->getSessionKey() === null && $user === null) {
            throw new UserRequiredUnlessAuthException;
        }
        $this->__setCall('getTags');
        return $this->__makeCall([
            'artist'        => $artist,
            'album'         => $album,
            'mbid'          => $mbid,
            'autocorrect'   => $autocorrect,
            'user'          => $user,
        ]);
    }

    /**
     * Get the top tags for an album.
     *
     * @param string $artist
     * @param string $album
     * @param bool $autocorrect
     * @param int $mbid
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiredParameterMissingException
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getTopTags($artist = null, $album = null, $autocorrect = false, $mbid = null)
    {
        $this->requiredUnlessMBID($artist, $album, $mbid);
        $this->__setCall('getTopTags');
        return $this->__makeCall([
            'artist'        => $artist,
            'album'         => $album,
            'autocorrect'   => $autocorrect,
            'mibd'          => $mbid,
        ]);
    }

    /**
     * Remove a tag from an album.
     * Requires authentication.
     *
     * @param string $artist
     * @param string $album
     * @param string $tag
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function removeTag($artist, $album, $tag)
    {
        $this->__setCall('removeTag');
        return $this->__makeCall([
            'album'     => $album,
            'artist'    => $artist,
            'tag'       => $tag,
        ], true);
    }

    /**
     * Search for an album.
     *
     * @param string $album
     * @param int $limit
     * @param int $page
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function albumSearch($album, $limit = 30, $page = 1)
    {
        $this->__setCall('albumSearch');
        return $this->__makeCall([
            'limit' => $limit,
            'page'  => $page,
            'album' => $album,
        ]);
    }

    /**
     * Throws RequiredParameterMissingException if artist/album is null as well as mbid
     *
     * @param string $artist
     * @param string $album
     * @param int $mbid
     * @throws \Jamosaur\Lastfm\Exceptions\RequiredParameterMissingException
     */
    private function requiredUnlessMBID($artist, $album, $mbid)
    {
        if ($artist == null && $mbid == null || $album == null && $mbid == null) {
            throw new RequiredParameterMissingException;
        }
    }
}
