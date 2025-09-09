<?php namespace App\Controllers;

use Config\Services;
use Firebase\JWT\JWT;
use CodeIgniter\RESTful\ResourceController;

class Auth extends ResourceController {

  protected $format = 'json';

  public function create() {

    $usuario = $this->request->getPost('usuario');
    $clave   = $this->request->getPost('clave');

    // print_r($this->request->getPost());

   
    if (($usuario === "puntoblue" && $clave === "pb380380$#") || ($usuario === "aquaia" && $clave === "aquaia1234") ) {
      $time    = time();
      $key     = Services::getSecretKey();
      $payload = [
       'iat' => $time,
       'exp' => $time + 10,
       // 'data' => ["usuario" => "puntoblue", "name"],
      ];

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