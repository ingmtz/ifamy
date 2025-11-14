<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Qrcodegenerator {
    public function __construct() {
        // Incluye manualmente la clase principal y sus dependencias
        require_once APPPATH . 'libraries/qrcode/qrcode.class.php';
        // Puedes incluir otras clases necesarias aquí
        // require_once APPPATH . 'libraries/qrcode/otherclass1.php';
        // require_once APPPATH . 'libraries/qrcode/otherclass2.php';

        // Opcional: Puedes inicializar la clase principal aquí si es necesario
        // $this->qrcode = new \QrcodeClass();
    }

    // Opcional: Métodos para acceder a las funcionalidades de la librería
    // public function generate($params) {
    //     return $this->qrcode->generate($params);
    // }
}