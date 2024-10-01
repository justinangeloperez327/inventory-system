<?php

// Global helper to set the layout
function layout($layout)
{
    \core\View::layout($layout);
}

// Global helper to start a section
function section($name)
{
    \core\View::startSection($name);
}

// Global helper to end a section
function endSection()
{
    \core\View::endSection();
}

// Global helper to yield a section
function renderSection($name)
{
    echo \core\View::renderSection($name);
}

function authenticated()
{
    return \core\Session::get('authenticated');
}

function user()
{
    return \core\Session::get('user');
}

function userName()
{
    return \core\Session::get('user_name');
}

function userRole()
{
    return \core\Session::get('user_role');
}

function admin (): bool
{
    return userRole() === 'admin';
}

function userId()
{
    return \core\Session::get('user_id');
}

function flash()
{
    $flash = \core\Redirect::getFlash();

    if ($flash) {
        return $flash;
    }
}

