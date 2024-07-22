<?php
session_start();

function set_flash_message($name, $message)
{
    $_SESSION[$name] = $message;
}

function get_flash_message($name)
{
    if (isset($_SESSION[$name])) {
        $message = $_SESSION[$name];
        unset($_SESSION[$name]);
        return $message;
    }
    return null;
}
