<?php

class Router
{
    private $url;

    public function __construct()
    {
        $this->url = explode('/', $_SERVER['REQUEST_URI']);
    }

    public static function render($template, $vars = NULL)
    {
        if (file_exists('app/template/' . $template . '.php')) {
            ob_start();
            include 'app/template/' . $template . '.php';
            $template = ob_get_clean();
            return $template;
        }
    }

    public function init()
    {
        $vars[] = (new Controller())->init();


        if ($this->url[1] === '' || $this->url[1] === 'page') {
            $vars['content'] = (new Index())->index();
        } elseif ($this->url[1] === 'error') {
            $vars['content'] = (new Error404())->index();
        } else {
            $vars['content'] = (new $this->url[1]())->index();
        }
        $this->view($vars);
    }

    private function view($vars)
    {
        $vars = $this->var($vars);
        include_once 'app/public/index.php';
    }

    private function var($vars)
    {
        foreach ($vars as $key => $var) {
            if (is_array($var)) {
                foreach ($var as $key2 => $var_one) {
                    $data[$key2] = $var_one;
                }
            } else {
                $data[$key] = $var;
            }
        }
        return $data;
    }
}