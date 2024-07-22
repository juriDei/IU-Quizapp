<?php
require_once dirname(__DIR__) . '/php/flashMessage.php';

class MessageHandlerController
{
    public static function addError($message)
    {
        set_flash_message('error', $message);
    }

    public static function addSuccess($message)
    {
        set_flash_message('success', $message);
    }

    public static function getError()
    {
        return get_flash_message('error');
    }

    public static function getSuccess()
    {
        return get_flash_message('success');
    }
}
?>