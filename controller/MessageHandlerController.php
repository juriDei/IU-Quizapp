<?php

// Flash-Nachrichten Datei einbinden, um Benachrichtigungen zu handhaben
require_once dirname(__DIR__) . '/php/flashMessage.php';

// Controller-Klasse zur Verwaltung von Fehlermeldungen und Erfolgsnachrichten
class MessageHandlerController
{
    // Methode zum Hinzufügen einer Fehlermeldung zur Sitzung
    public static function addError($message)
    {
        // Fehlermeldung mit dem Typ 'error' speichern
        set_flash_message('error', $message);
    }

    // Methode zum Hinzufügen einer Erfolgsnachricht zur Sitzung
    public static function addSuccess($message)
    {
        // Erfolgsnachricht mit dem Typ 'success' speichern
        set_flash_message('success', $message);
    }

    // Methode zum Abrufen der gespeicherten Fehlermeldung aus der Sitzung
    public static function getError()
    {
        // Fehlermeldung mit dem Typ 'error' abrufen
        return get_flash_message('error');
    }

    // Methode zum Abrufen der gespeicherten Erfolgsnachricht aus der Sitzung
    public static function getSuccess()
    {
        // Erfolgsnachricht mit dem Typ 'success' abrufen
        return get_flash_message('success');
    }
}
?>
