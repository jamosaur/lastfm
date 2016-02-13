<?php

namespace Jamosaur\Lastfm;

use Jamosaur\Lastfm\Exceptions\InvalidPeriodException;

class User extends Lastfm
{
    protected $user = null;

    /**
     * User constructor.
     *
     * @param string $apiKey
     * @param string $apiSecret
     */
    public function __construct($apiKey, $apiSecret)
    {
        parent::__construct($apiKey, $apiSecret);
        $this->__setSection('user');
    }

    /**
     * Set the user.
     *
     * @param string $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get a list of tracks by a given artist scrobbled by this user, including scrobble time.
     * Can be limited to specific timeranges, defaults to all time.
     *
     * @param string $artist
     * @param int $page
     * @param int $start
     * @param int $end
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getArtistTracks($artist, $page = 1, $start = null, $end = null)
    {
        $this->__setCall('getArtistTracks');
        return $this->__makeCall([
            'user'              => $this->user,
            'artist'            => $artist,
            'startTimestamp'    => $start,
            'page'              => $page,
            'endTimestamp'      => $end,
        ]);
    }

    public function getFriends($recentTracks = false, $limit = 50, $page = 1)
    {
        $this->__setCall('getFriends');
        return $this->__makeCall([
            'user'          => $this->user,
            'recenttracks'  => $recentTracks,
            'limit'         => $limit,
            'page'          => $page,
        ]);
    }

    /**
     * Get information about a user profile.
     *
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getInfo()
    {
        $this->__setCall('getInfo');
        return $this->__makeCall([
            'user'  => $this->user,
        ]);
    }

    /**
     * Get the last 50 tracks loved by a user.
     *
     * @param int $limit
     * @param int $page
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getLovedTracks($limit = 50, $page = 1)
    {
        $this->__setCall('getLovedTracks');
        return $this->__makeCall([
            'user'  => $this->user,
            'limit' => $limit,
            'page'  => $page,
        ]);
    }

    /**
     * Get the user's personal tags
     *
     * @param string $tag
     * @param string artist|album|track $taggingType
     * @param int $limit
     * @param int $page
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getPersonalTags($tag, $taggingType, $limit = 50, $page = 1)
    {
        $this->__setCall('getPersonalTags');
        return $this->__makeCall([
            'user'          => $this->user,
            'tag'           => $tag,
            'taggingtype'   => $taggingType,
            'limit'         => $limit,
            'page'          => $page,
        ]);
    }

    /**
     * Get a list of the recent tracks listened to by this user.
     * Also includes the currently playing track with the
     * nowplaying="true" attribute if the user is currently listening.
     *
     * @param int 0|1 $extended
     * @param int $from
     * @param int $to
     * @param int $limit
     * @param int $page
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getRecentTracks($extended = 0, $from = null, $to = null, $limit = 50, $page = 1)
    {
        $this->__setCall('getRecentTracks');
        return $this->__makeCall([
            'user' => $this->user,
            'extended' => $extended,
            'from' => $from,
            'to' => $to,
            'limit' => $limit,
            'page' => $page,
        ]);
    }

    /**
     * Get the top albums listened to by a user. You can stipulate a time period. Sends the overall chart by default.
     *
     * @param string $period
     * @param int $limit
     * @param int $page
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\InvalidPeriodException
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getTopAlbums($period = 'overall', $limit = 50, $page = 1)
    {
        $this->checkTimePeriod($period);
        $this->__setCall('getTopAlbums');
        return $this->__makeCall([
            'user'      => $this->user,
            'period'    => $period,
            'limit'     => $limit,
            'page'      => $page
        ]);
    }

    /**
     * Get the top artists listened to by a user. You can stipulate a time period. Sends the overall chart by default.
     *
     * @param string $period
     * @param int $limit
     * @param int $page
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\InvalidPeriodException
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getTopArtists($period = 'overall', $limit = 50, $page = 1)
    {
        $this->checkTimePeriod($period);
        $this->__setCall('getTopArtists');
        return $this->__makeCall([
            'user'      => $this->user,
            'period'    => $period,
            'limit'     => $limit,
            'page'      => $page
        ]);
    }

    /**
     *  Get the top tags used by this user.
     *
     * @param int $limit
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getTopTags($limit = 50)
    {
        $this->__setCall('getTopTags');
        return $this->__makeCall([
            'user'  => $this->user,
            'limit' => $limit,
        ]);
    }

    public function getTopTracks($period = 'overall', $limit = 50, $page = 1)
    {
        $this->checkTimePeriod($period);
        $this->__setCall('getTopTracks');
        return $this->__makeCall([
            'user'      => $this->user,
            'period'    => $period,
            'limit'     => $limit,
            'page'      => $page,
        ]);
    }

    /**
     * Get an album chart for a user profile, for a given date range.
     * If no date range is supplied, it will return the most recent album chart for this user.
     *
     * @param int $from
     * @param int $to
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getWeeklyAlbumCharts($from = null, $to = null)
    {
        $this->__setCall('getWeeklyAlbumCharts');
        return $this->__makeCall([
            'user'  => $this->user,
            'from'  => $from,
            'to'    => $to,
        ]);
    }

    /**
     * Get an artist chart for a user profile, for a given date range.
     * If no date range is supplied, it will return the most recent artist chart for this user.
     *
     * @param null $from
     * @param null $to
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getWeeklyArtistCharts($from = null, $to = null)
    {
        $this->__setCall('getWeeklyArtistCharts');
        return $this->__makeCall([
            'user'  => $this->user,
            'from'  => $from,
            'to'    => $to,
        ]);
    }

    /**
     *  Get a list of available charts for this user, expressed as date ranges which can be sent to the chart services.
     *
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getWeeklyChartList()
    {
        $this->__setCall('getWeeklyChartList');
        return $this->__makeCall([
            'user'  => $this->user,
        ]);
    }

    /**
     * Get a track chart for a user profile, for a given date range.
     * If no date range is supplied, it will return the most recent track chart for this user.
     *
     * @param int $from
     * @param int $to
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function getWeeklyTrackChart($from = null, $to = null)
    {
        $this->__setCall('getWeeklyTrackChart');
        return $this->__makeCall([
            'user'  => $this->user,
            'from'  => $from,
            'to'    => $to,
        ]);
    }

    /**
     * Check that a given time period is in the acceptable list.
     *
     * @param string $period
     * @return bool
     * @throws \Jamosaur\Lastfm\Exceptions\InvalidPeriodException
     */
    private function checkTimePeriod($period)
    {
        $validPeriods = [
            'overall',
            '7day',
            '1month',
            '3month',
            '6month',
            '12month'
        ];
        if (!in_array($period, $validPeriods, true)) {
            throw new InvalidPeriodException;
        }
        return false;
    }
}
