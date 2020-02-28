<?php

namespace App\Controllers;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
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
        $loader = new FilesystemLoader('../views');
        //Create an environment for twig
        $this->templateEngine = new Environment($loader, [
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