<?php

namespace Jamosaur\Lastfm;

class Library extends Lastfm
{
    /**
     * Library constructor.
     *
     * @param string $apiKey
     * @param string $apiSecret
     * @param string $sessionKey
     */
    public function __construct($apiKey, $apiSecret, $sessionKey)
    {
        parent::__construct($apiKey, $apiSecret, $sessionKey);
        $this->__setSection('library');
    }

    /**
     * A paginated list of all the artists in a user's library, with play counts and tag counts.
     *
     * @param $user
     * @param int $limit
     * @param int $page
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getArtists($user, $limit = 50, $page = 1)
    {
        $this->__setCall('getArtists');

        return $this->__makeCall([
            'user'  => $user,
            'limit' => $limit,
            'page'  => $page,
        ]);
    }
}