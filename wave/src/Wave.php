<?php
namespace Wave;

class Wave
{
    public static function routes()
    {
        require __DIR__ . '/../routes/web.php';
    }

    public function api()
    {
        require __DIR__ . '/../routes/api.php';
    }
}
