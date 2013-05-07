<?php

namespace Slim\Extra;

class File
{
    private $files;

    public function __construct() {
        $this->files = (object) array();

        foreach ($_FILES as $key => $values) {
            $this->files->$key = (object) $values;
        }
    }

    public function __get($name) {
        return $this->files->$name;
    }

    public function __isset($name) {
        return isset($this->files->$name);
    }
}
