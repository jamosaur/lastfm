<?php

namespace Jamosaur\Lastfm;

use Httpful\Request;
use Jamosaur\Lastfm\Exceptions\InvalidMethodException;
use Jamosaur\Lastfm\Exceptions\InvalidServiceException;
use Jamosaur\Lastfm\Exceptions\RequiresSessionAuthException;

class Lastfm
{
    protected $apiURL       = 'http://ws.audioscrobbler.com/2.0/';

    private $apiKey         = null;
    private $apiSecret      = null;
    private $sessionKey     = null;

    public $lang            = 'en';
    protected $section      = null;
    protected $call         = null;

    /**
     * Lastfm constructor.
     *
     * @param $apiKey
     * @param $apiSecret
     * @param null $sessionKey
     */
    public function __construct($apiKey, $apiSecret, $sessionKey = null)
    {
        $this->apiKey       = $apiKey;
        $this->apiSecret    = $apiSecret;
        $this->sessionKey   = $sessionKey;
    }

    /**
     * Sets the API Section to use.
     *
     * @param $section
     */
    public function __setSection($section)
    {
        $this->section  = $section;
    }

    /**
     * Sets which API call to make.
     *
     * @param $call
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
            'method'    => $this->section.'.'.$this->call,
            'api_key'   => $this->apiKey,
            'format'    => 'json',
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
            $query['api_sig'] = md5($signature.$this->apiSecret);
        }

        $response = Request::get($this->apiURL.'?'.http_build_query($query))->send();

        if (isset($response->body->error)) {
            $this->handleError($response->body->id, $response);
        }
        return $response->body;
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
     * @return \Jamosaur\Lastfm\Album
     */
    public function album()
    {
        return new Album($this->apiKey, $this->apiSecret);
    }

    /**
     * @return \Jamosaur\Lastfm\User
     */
    public function user()
    {
        return new User($this->apiKey, $this->apiSecret);
    }

    /**
     * @param $id
     * @param $response
     * @throws \Jamosaur\Lastfm\Exceptions\InvalidMethodException
     * @throws \Jamosaur\Lastfm\Exceptions\InvalidServiceException
     */
    private function handleError($id, $response)
    {
        switch ($id) {
            case "2":
                throw new InvalidServiceException;
            break;
            case "3":
                throw new InvalidMethodException;
            break;
            default:
                die($response->body->message);
//                throw new \Exception;
        }
    }
}
