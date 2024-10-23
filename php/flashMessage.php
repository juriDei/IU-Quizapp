<?php
session_start();

// Funktion zum Setzen einer Flash-Nachricht
// Parameter: $name (Name der Nachricht), $message (Nachrichtentext)
function set_flash_message($name, $message)
{
    // Die Nachricht wird in die $_SESSION gespeichert, um sie temporär verfügbar zu machen
    $_SESSION[$name] = $message;
}

// Funktion zum Abrufen einer Flash-Nachricht
// Parameter: $name (Name der Nachricht)
// Gibt die Nachricht zurück und entfernt sie danach aus der $_SESSION
function get_flash_message($name)
{
    if (isset($_SESSION[$name])) {
        // Speichern der Nachricht in einer lokalen Variable
        $message = $_SESSION[$name];
        
        // Entfernen der Nachricht aus der Session, um sicherzustellen, dass sie nur einmal angezeigt wird
        unset($_SESSION[$name]);
        
        return $message;
    }
    // Rückgabe von null, falls keine Nachricht gesetzt ist
    return null;
}