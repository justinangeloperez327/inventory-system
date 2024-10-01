<?php

namespace core;

class Redirect
{
    public static function to($url, $message = null)
    {
        if ($message) {
            self::setFlash($message);
        }
        header("Location: /$url");
        exit;
    }

    public static function back($message = null)
    {
        if ($message) {
            self::setFlash($message);
        }
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    private static function setFlash($message)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash_message'] = $message;
    }

    public static function getFlash()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            return $message;
        }
        return null;
    }
}
