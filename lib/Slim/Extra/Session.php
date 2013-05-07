<?php

namespace Slim\Extra;

class Session
{
    public function __construct() {
    }

    public function __get($name) {
        return $_SESSION[$name];
    }

    public function __set($name, $value) {
        return $_SESSION[$name] = $value;
    }

    public function __isset($name) {
        return isset($_SESSION[$name]);
    }

    public function __unset($name) {
        unset($_SESSION[$name]);
    }


    public function id($id = null) {
        return session_id($id);
    }

    public function name() {
        return session_name();
    }

    public function start() {
        session_start();
        return $this;
    }

    public function destroy() {
        if ($this->id()) {
            session_destroy();
        }

        setcookie($this->name(), '', time() - 86400, '/');

        return $this;
    }

    public function regenerate($delete = null) {
        session_regenerate_id($delete);
        return $this;
    }

    public function status() {
        $status = (object) array(
            PHP_SESSION_DISABLED => 0,
            PHP_SESSION_NONE => 1,
            PHP_SESSION_ACTIVE => 2
        );

        if ($this->id()) {
            return $status->PHP_SESSION_ACTIVE;
        }

        else if ($_COOKIE[$this->name()]) {
            return $status->PHP_SESSION_NONE;
        }

        return $status->PHP_SESSION_DISABLED;
    }
}
