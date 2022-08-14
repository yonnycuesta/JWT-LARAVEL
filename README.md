# Documentacion
https://jwt-auth.readthedocs.io/en/develop/laravel-installation/

1. composer require tymon/jwt-auth:dev-develop --prefer-source
2. composer require tymon/jwt-auth
3. 
'providers' => [

    ...

    Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
]

4. php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

5. 

Generate secret key

php artisan jwt:secret