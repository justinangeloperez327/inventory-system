<?php

namespace core;

class View
{
    protected static $sections = [];
    protected static $layout;

    // Start a section for content or scripts
    public static function startSection($name)
    {
        ob_start();
        self::$sections[$name] = ''; // Initialize the section
    }

    // End the section and store its contents
    public static function endSection()
    {
        $lastKey = array_key_last(self::$sections);
        self::$sections[$lastKey] = ob_get_clean();
    }

    // Yield a section inside the layout
    public static function renderSection($name)
    {
        return self::$sections[$name] ?? '';
    }

    // Set the layout file
    public static function layout($layout)
    {
        self::$layout = $layout;
    }

    // Render a view with a specific layout and data
    public static function render($view, $data = [])
    {
        extract($data); // Extract the variables for use in the views
        ob_start();
        include __DIR__ . '/../app/views/' . $view . '.php';
        $content = ob_get_clean();

        if (self::$layout) {
            // If a layout is set, include it and pass the content to it
            include __DIR__ . '/../app/views/layouts/' . self::$layout . '.php';
        } else {
            echo $content; // If no layout is set, just display the content
        }
    }
}
