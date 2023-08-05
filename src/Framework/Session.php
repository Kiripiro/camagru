<?php
class Session
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    public function addArray($key, $value)
    {
        if (isset($_SESSION[$key])) {
            $_SESSION[$key][] = $value;
        } else {
            $_SESSION[$key] = array($value);
        }
    }

    public function removeFromArray($key, $value)
    {
        if (isset($_SESSION[$key])) {
            $index = array_search($value, $_SESSION[$key]);
            if ($index !== false) {
                unset($_SESSION[$key][$index]);
                $_SESSION[$key] = array_values($_SESSION[$key]);
            }
        }
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public function get($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }

    public function destroy()
    {
        session_destroy();
    }

    public function isLoggedIn()
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        }
        return false;
    }
}