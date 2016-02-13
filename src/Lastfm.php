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
    
    public function __construct($apiKey, $apiSecret, $sessionKey = null)
    {
        $this->apiKey       = $apiKey;
        $this->apiSecret    = $apiSecret;
        $this->sessionKey   = $sessionKey;
    }

    public function __setSection($section)
    {
        $this->section  = $section;
    }

    public function __setCall($call)
    {
        $this->call = $call;
    }

    public function __makeCall($args = [], $requiresAuth = null)
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
     * @param $lang
     * @return $this
     */
    public function language($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    public function getLanguage()
    {
        return $this->lang;
    }

    public function getSessionKey()
    {
        return $this->sessionKey;
    }

    public function album()
    {
        return new Album($this->apiKey, $this->apiSecret);
    }

    public function user()
    {
        return new User($this->apiKey, $this->apiSecret);
    }

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
