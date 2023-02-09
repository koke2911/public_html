<?php namespace App\Controllers;

use Config\Services;
use Firebase\JWT\JWT;
use CodeIgniter\RESTful\ResourceController;

class Auth extends ResourceController {

  protected $format = 'json';

  public function create() {
    /**
     * JWT claim types
     * https://auth0.com/docs/tokens/concepts/jwt-claims#reserved-claims
     */

    $usuario = $this->request->getPost('usuario');
    $clave   = $this->request->getPost('clave');

    // add code to fetch through db and check they are valid
    // sending no email and password also works here because both are empty
    if ($usuario === "puntoblue" && $clave === "pb380380$#") {
      $time    = time();
      $key     = Services::getSecretKey();
      $payload = [
       'iat' => $time,
       'exp' => $time + 10,
       // 'data' => ["usuario" => "puntoblue", "name"],
      ];

      /**
       * IMPORTANT:
       * You must specify supported algorithms for your application. See
       * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
       * for a list of spec-compliant algorithms.
       */
      $jwt = JWT::encode($payload, $key);

      return $this->respond(['token' => $jwt], 200);
    }

    return $this->respond(['message' => 'Invalid login details'], 401);
  }

  public function validateToken($token) {
    try {
      $key = Services::getSecretKey();

      return JWT::decode($token, $key, ['HS256']);
    } catch (\Exception $e) {
      return FALSE;
    }
  }

  public function verifyToken() {
    $key   = Services::getSecretKey();
    $token = $this->request->getPost("token");

    if ($this->validateToken($token) === FALSE) {
      return $this->respond(["message" => "Token Inválido"], 401);
    } else {
      $data = JWT::decode($token, $key, ['HS256']);

      return $this->respond(["data" => $data], 200);
    }
  }
}