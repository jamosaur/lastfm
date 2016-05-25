# lastfm

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

LastFM wrapper.

## Install

Via Composer

``` bash
$ composer require jamosaur/lastfm
```

## Usage

#### Standard Methods

``` php
$lastfm = new Jamosaur\Lastfm\Lastfm('API_KEY', 'API_SECRET');

$lastfm->user()->setUser('jaaaaaaaaaam')->getWeeklyTrackChart();
```

#### Authenticated methods

The first step is to authenticate the user with last.fm

``` php
    Route::get('/auth/connect', function () {
        return redirect()->url($lastFm->authGetURL(route('lfm.callback')));
    });
    
    Route::get('/auth/connect', [
        'as'    => 'lfm.callback',
        'uses'  => 'LastFMController@callback'
    ]);
```

_LastFMController.php_
``` php
    use Jamosaur\Lastfm\Lastfm;
    ...
    
    public function callback(Request $request)
    {
        $lfm = new Lastfm('API_KEY', 'API_SECRET');
        $session = $lfm->sessionKey($request->get('token'));
        
        // Add the session key to the user record
        $user->lfm_session = $session->session->key;
    }
    
    public function requiresAuth()
    {
        $lfm = Lastfm($user->lfmAPI, $user->lfmSecret, $user->lfm_session);
        $tags = $lfm->artist()->addTags('Pendulum', ['sell-outs', 'used-to-be-good']);
    }
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email j.wallen.jones@googlemail.com instead of using the issue tracker.

## Credits

- [James Wallen-Jones][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/jamosaur/lastfm.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/jamosaur/lastfm/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/jamosaur/lastfm.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/jamosaur/lastfm.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/jamosaur/lastfm.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/jamosaur/lastfm
[link-travis]: https://travis-ci.org/jamosaur/lastfm
[link-scrutinizer]: https://scrutinizer-ci.com/g/jamosaur/lastfm/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/jamosaur/lastfm
[link-downloads]: https://packagist.org/packages/jamosaur/lastfm
[link-author]: https://github.com/jamosaur
[link-contributors]: ../../contributors
