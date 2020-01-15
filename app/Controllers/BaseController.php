<?php


namespace App\Controllers;


class BaseController
{
    protected $templateEngine;

    public function __construct() {
        $loader = new \Twig\Loader\FilesystemLoader('../views');
        $this->templateEngine = new \Twig\Environment($loader, [
            'debug' => true,
            'cache' => false,
        ]);
    }

    public function renderHTML($template, $data = []) {
        return $this->templateEngine->render($template, $data);
    }

}