<?php

include_once('./vendor/autoload.php');

function exception_error_handler($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return;
    }
    throw new \ErrorException($message, 0, $severity, $file, $line);
}

set_error_handler('exception_error_handler');

$data = new \DateTime('now');

$System = new \App\System([
    'settings' => [
        'displayErrorDetails' => true,
        // Monolog
        'logger' => [
            'name' => 'api',
            'path' => 'logs/' . $data->format('Y-m-d') . '.log',
        ],
        'addContentLengthHeader' => false
    ],
]);

$System->run();
