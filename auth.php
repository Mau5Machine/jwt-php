<?php
/* A couple useful functions to check for authentication on pages
 ** Just include requireAuth() to only see if logged in */

// function to check if user is authenticated with jwt
function isAuthenticated()
{
    if (!request()->cookies->has('access_token')) {
        return false;
    }

    try {
        \Firebase\JWT\JWT::$leeway = 1;
        \Firebase\JWT\JWT::decode(
            request()->cookies->get('access_token'),
            getenv('SECRET_KEY'),
            ['HS256']
        );
        return true;
    } catch (\Exception $e) {
        return false;
    }
}

//  function to require authorization, if false, assign bad cookie
function requireAuth()
{
    if (!isAuthenticated()) {
        $accessToken = new \Symfony\Component\HttpFoundation\Cookie('access_token', 'Expired', time() - 3600, '/', getenv('COOKIE_DOMAIN'));
        redirect('login.php', ['cookies' => [$accessToken]]);
    }
}