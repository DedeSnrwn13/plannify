<?php

if (!function_exists('flashMessage')) {
    function flashMessage($message, $type = 'message'): void
    {
        session()->flash('message', $message);
        session()->flash('type', $type);
    }
}
