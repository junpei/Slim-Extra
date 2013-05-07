<?php

namespace Slim\Extra;

class Layout extends \Slim\View {
    private $extensions = array(
        'text/xml' => 'xml',
        'text/css' => 'css',
        'text/csv' => 'csv',
        'text/html' => 'php',
        'text/json' => 'json',
        'text/plain' => 'txt',
        'text/javascript' => 'js',
        'application/xml' => 'xml',
        'application/json' => 'json',
    );

    public function __call($name, $arguments) {
        $this->data[$name] = (count($arguments) === 1)
            ? array_shift($arguments) : $arguments;
        return $this;
    }

    public function setTemplate($template) {
        $app = \Slim\Slim::getInstance();

        if (preg_match('/\.[^\/\.]{2,4}$/', $template) === 0) {
            $template .= '.' . $this->getExtension();
        }

        return call_user_func(array('parent', 'setTemplate'), $template);
    }

    public function render($template) {
        $app = \Slim\Slim::getInstance();
        $layoutPath = $this->getLayoutPath();

        if ($app->request()->isAjax()
            || $this->getExtension() !== 'php'
            || file_exists($layoutPath) === false
        ) {
            return $this->partial($template);
        }

        $this->data['content'] = $this->partial($template);

        ob_start();
        extract($this->data);
        require $layoutPath;
        return  ob_get_clean();
    }

    public function partial($template) {
        ob_start();
        $this->data['paths'] = explode('/', $template);
        extract($this->data);
        $this->setTemplate($template);
        require $this->templatePath;
        return ob_get_clean();
    }

    protected function getLayoutPath() {
        $app = \Slim\Slim::getInstance();
        $path = $app->config('layouts.path');
        $layout = ltrim($app->config('layout') ?: 'default', '/');
        return $path . '/' . $layout . '.php';
    }

    protected function getExtension() {
        $app = \Slim\Slim::getInstance();
        $mediaType = $app->request()->getMediaType() ?: 'text/html';

        if (isset($this->extensions[$mediaType])) {
            $app->contentType($mediaType);
            return $this->extensions[$mediaType];
        }

        return 'php';
    }
}
