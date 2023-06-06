<?php
(function () {
    $allowedOrigins = array(
        /** @lang RegExp */
        'https://([^.]+\.){0,}?reisinger\.pictures',
        /** @lang RegExp */
        'https?://localhost(:\d{1,})?'
    );
    if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] != '') {
        foreach ($allowedOrigins as $allowedOrigin) {
            if (preg_match('#' . $allowedOrigin . '#', $_SERVER['HTTP_ORIGIN'])) {
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
                header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
                header('Access-Control-Max-Age: 1000');
                header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, accesskey, email');
                break;
            }
        }
    }
    // Exit early so the page isn't fully loaded for options requests
    if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
        exit();
    }
})();

// Error handling
// https://phpdelusions.net/articles/error_reporting

function myExceptionHandler($e)
{
    error_log($e);
    header('HTTP/1.0 500 An internal server error has been occurred. Please try again later.');
    if (filter_var(ini_get('display_errors'), FILTER_VALIDATE_BOOLEAN)) {
        echo $e;
    } else {
        echo '<h1>500 Internal Server Error</h1>
              An internal server error has been occurred.<br>
              Please try again later.';
    }
}

set_exception_handler('myExceptionHandler');

set_error_handler(function ($level, $message, $file = '', $line = 0) {
    throw new ErrorException($message, 0, $level, $file, $line);
});

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null) {
        $e = new ErrorException(
            $error['message'], 0, $error['type'], $error['file'], $error['line']
        );
        myExceptionHandler($e);
    }
});
