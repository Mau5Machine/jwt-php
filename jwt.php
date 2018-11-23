<?php
/* This is a code snippet example of how to setup a JSON Web Token
 ** for Authentication.
 ** The token is produced with the assistance of Firebase JWT 
 ** Symofony HttpFoundation is used to generate the cookie to 
 ** store the jwt
 ** composer require firebase/jwt & symfony/httpfoundation
 */

// Creating the JWT
// 1. Set the expire time
$expTime = time() + 3600; // one hour

// 2. Setup the JWT here
// There are 3 parts to a web token
// Header, Payload, and Signature (Secret Key)
$jwt = Firebase\JWT\JWT::encode(
    [
        'iss' => request()->getBaseUrl(), //iss	Issuer	Your project's service account email address
        'sub' => "{$user['id']}", //sub	Subject	Your project's service account email address
        'exp' => $expTime, //exp Expiration time in seconds UNIX epoch, when the token expires.
        // Can be a max of 3600 seconds later that the iat.
        // This only controls when token itself expires. Once you sign user in, they will remain signed in
        // until they logout or end the session
        'iat' => time(), // iat	Issued-at time	The current time, in seconds since the UNIX epoch
        'nbf' => time() // nbf Not Before The token cannot be used before this time
    ],
    getenv("SECRET_KEY"), // Signature with private key
    // 64 random hexadecimal characters (0-9 and A-F):
    'HS256' // The algorithm
);

// Create the access token cookie
$accessToken = new Symfony\Component\HttpFoundation\Cookie('access_token', $jwt, $expTime, '/', getenv('COOKIE_DOMAIN'));

// redirect to home page as logged in
redirect('../index.php?loggedin=true', ['cookies' => [$accessToken]]);