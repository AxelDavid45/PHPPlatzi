<?php

namespace App\Controllers;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;

class BaseController
{
    protected $templateEngine;

    /*
     * Roll out all the setting for twig
     */
    public function __construct() {
        //Create the loader where the views are
        $loader = new \Twig\Loader\FilesystemLoader('../views');
        //Create an environment for twig
        $this->templateEngine = new \Twig\Environment($loader, [
            'debug' => true,
            'cache' => false,
        ]);
    }

    /*
     * Return a psr7 response with html
     * */
    public function renderHTML($template, $data = []) {
        return new HtmlResponse($this->templateEngine->render($template, $data));
    }

}