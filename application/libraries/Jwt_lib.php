<?php
defined('BASEPATH') or exit('No direct script access allowed');

// https://github.com/firebase/php-jwt

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;

class Jwt_lib
{
  // secret Key
  private $accessKey = ACCESS_KEY;
  private $issuer = ISSUER;
  private $audience = AUDIENCE;

  // 'iss' => '', // Issuer (Who issues the token.)
  // 'aud' => '', // Audience (For whom App,Webapp, Website the token is generated.)
  // 'sub' => '', // Subject (For which the token is generated.)
  // 'iat' => time(), // Issued At
  // 'exp' => time() + 3600  // Expiration Time

  public function generateToken($payload)
  {
    $payload['iss'] = $this->issuer;
    $payload['aud'] = $this->audience;
    return JWT::encode($payload, $this->accessKey, 'HS256');
  }

  public function decodeToken($token)
  {
    try {
      $decoded = JWT::decode($token, new Key($this->accessKey, 'HS256'));
      return (array) $decoded;
    } catch (SignatureInvalidException $e) {
      log_message('error', 'Invalid Token signature: ' . $e->getMessage());
      return ['error' => 'Invalid Access token signature.'];
    } catch (ExpiredException $e) {
      log_message('error', 'Token Expired: ' . $e->getMessage());
      return ['error' => 'Access Token has expired.'];
    } catch (Exception $e) {
      log_message('error', 'Exceptional Error: ' . $e->getMessage());
      return ['error' => 'An error occurred while decoding the Access token.'];
    }
  }
}
