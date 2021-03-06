<?php

namespace Jamosaur\Lastfm;

use Httpful\Request;
use Jamosaur\Lastfm\Exceptions\InvalidMethodException;
use Jamosaur\Lastfm\Exceptions\InvalidServiceException;
use Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException;

class Lastfm
{
    public $lang = 'en';
    protected $apiURL = 'http://ws.audioscrobbler.com/2.0/';
    protected $authURL = 'http://www.last.fm/api/auth/';
    protected $section = null;
    protected $call = null;
    private $apiKey = null;
    private $apiSecret = null;
    private $sessionKey = null;

    /**
     * Lastfm constructor.
     *
     * @param string $apiKey
     * @param string $apiSecret
     * @param null $sessionKey
     */
    public function __construct($apiKey, $apiSecret, $sessionKey = null)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->sessionKey = $sessionKey;
    }

    /**
     * Sets the language that results are returned in.
     * Accepts ISO 639-1 codes, e.g. `en`, `sv`
     * https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
     *
     * @param $lang
     * @return $this
     */
    public function language($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get the language in which results are returned.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->lang;
    }

    /**
     * Get the session key
     *
     * @return null
     */
    public function getSessionKey()
    {
        return $this->sessionKey;
    }

    /**
     * This is the first part of authentication, this will return an authentication URL
     * that you will need to redirect to so that the user can log in to the last.fm
     * service. The callback URL is where the user will be redirected to after.
     *
     * @param $callbackURL
     * @return string
     */
    public function authGetURL($callbackURL)
    {
        return $this->authURL . '?' . http_build_query([
            'api_key' => $this->apiKey,
            'cb'      => $callbackURL,
        ]);
    }

    /**
     * This is the second part of authentication. This returns a session key which
     * can be stored in a database with the user information.
     *
     * @param $token
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function sessionKey($token)
    {
        $this->__setSection('auth');
        $this->__setCall('getSession');

        return $this->__makeCall([
            'token' => $token,
        ]);
    }

    /**
     * Sets the API Section to use.
     *
     * @param string $section
     */
    public function __setSection($section)
    {
        $this->section = $section;
    }

    /**
     * Sets which API call to make.
     *
     * @param string $call
     */
    public function __setCall($call)
    {
        $this->call = $call;
    }

    /**
     * Make an API call.
     *
     * @param array $args
     * @param bool $requiresAuth
     * @return array|object|string
     * @throws \Jamosaur\Lastfm\Exceptions\InvalidMethodException
     * @throws \Jamosaur\Lastfm\Exceptions\InvalidServiceException
     * @throws \Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException
     */
    public function __makeCall($args = [], $requiresAuth = false)
    {
        if ($requiresAuth && $this->sessionKey === null) {
            throw new RequiresSessionAuthException;
        }

        $query = array_merge([
            'method'  => $this->section . '.' . $this->call,
            'api_key' => $this->apiKey,
            'format'  => 'json',
        ], $args);

        if ($requiresAuth) {
            $signature = null;

            if ($this->sessionKey !== null) {
                $query['sk'] = $this->sessionKey;
            }

            $formatlessQuery = $query;
            unset($formatlessQuery['format']);
            ksort($formatlessQuery);
            foreach ($formatlessQuery as $k => $v) {
                $signature .= "$k$v";
            }
            $query['api_sig'] = md5($signature . $this->apiSecret);
        }

        $response = Request::get($this->apiURL . '?' . http_build_query($query))->send();

        if (isset($response->body->error)) {
            $this->handleError($response->body->id, $response);
        }

        return $response->body;
    }

    /**
     * @param $id
     * @throws \Exception
     * @throws \Jamosaur\Lastfm\Exceptions\InvalidMethodException
     * @throws \Jamosaur\Lastfm\Exceptions\InvalidServiceException
     */
    private function handleError($id)
    {
        switch ($id) {
            case "2":
                throw new InvalidServiceException;
            case "3":
                throw new InvalidMethodException;
            default:
                throw new \Exception;
        }
    }

    /**
     * Create a new album instance.
     *
     * @return \Jamosaur\Lastfm\Album
     */
    public function album()
    {
        return new Album($this->apiKey, $this->apiSecret, $this->sessionKey);
    }

    /**
     * Create a new Chart instance.
     *
     * @return \Jamosaur\Lastfm\Chart
     */
    public function chart()
    {
        return new Chart($this->apiKey, $this->apiSecret, $this->sessionKey);
    }

    /**
     * Create a new Geo instance.
     *
     * @return \Jamosaur\Lastfm\Geo
     */
    public function geo()
    {
        return new Geo($this->apiKey, $this->apiSecret, $this->sessionKey);
    }

    /**
     * Create a new Library instance.
     *
     * @return \Jamosaur\Lastfm\Library
     */
    public function library()
    {
        return new Library($this->apiKey, $this->apiSecret, $this->sessionKey);
    }

    /**
     * Create a new Tag instance.
     *
     * @return \Jamosaur\Lastfm\Tag
     */
    public function tags()
    {
        return new Tag($this->apiKey, $this->apiSecret, $this->sessionKey);
    }

    /**
     * Return a new Track instance.
     *
     * @return \Jamosaur\Lastfm\Track
     */
    public function track()
    {
        return new Track($this->apiKey, $this->apiSecret, $this->sessionKey);
    }

    /**
     * Create a new User instance.
     *
     * @return \Jamosaur\Lastfm\User
     */
    public function user()
    {
        return new User($this->apiKey, $this->apiSecret, $this->sessionKey);
    }
}
