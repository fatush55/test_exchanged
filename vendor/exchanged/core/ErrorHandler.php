<?php


namespace exchanged;


class ErrorHandler
{
    public function __construct()
    {
        if (DEBUG) {
            error_reporting(-1);
        } else {
            error_reporting(0);
        }

        set_error_handler([$this, 'errorHandler']);
        ob_start();
        register_shutdown_function([$this, 'fatalErrorHandler']);
        set_exception_handler([$this, 'exceptionHandler']);
    }

    public function errorHandler($errnum, $errstr, $errfile, $errline)
    {
        $this ->loadError($errnum, $errstr, $errfile, $errline);
        if (DEBUG || in_array($errnum, [E_USER_ERROR, E_RECOVERABLE_ERROR])){
            $this->displayError($errnum, $errstr, $errfile, $errline);
        }
        return true;
    }

    public function fatalErrorHandler()
    {
        $error = error_get_last();
        if (!empty($error) && $error['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)) {
            $this->loadError($error['type'], $error['message'], $error['file'], $error['line']);
            ob_end_flush();
            $this->displayError($error['type'], $error['message'], $error['file'], $error['line']);
        } else {
            ob_end_flush();
        }
    }

    public function exceptionHandler($e)
    {
        $this->loadError($e->getCode() ,$e->getMessage(), $e->getFile(), $e->getLine());
        $this->displayError('Exception', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());
    }

    protected function loadError($errnum, $massage = '', $file = '', $line = '')
    {
        $errnum = $this->errorType($errnum);
        error_log("[" . date('Y-m-d H:i:s') . "] => 
        Type: {$errnum} | Text: {$massage} | File: {$file}  | Lime: {$line }
         \n<===========================================>\n",
            3, ROOT . '/tmp/error.log');
    }

    protected function displayError($errnum, $errstr, $errfile, $errline, $response = 404)
    {
        $errnum = $this->errorType($errnum);
        http_response_code($response);
        if ($response === 404 && !DEBUG) {
            require WWW . '/errors/404.php';
            die;
        }

        if (DEBUG) {
            require WWW . '/errors/dev.php';
        } else {
            require WWW . '/errors/prod.php';
        }
        die;
    }

    protected function errorType($num)
    {
        switch ($num) {
            case 1 :
                return 'E_ERROR';
                break;
            case 2 :
                return 'E_WARNING';
                break;
            case 4 :
                return 'E_PARSE';
                break;
            case 8 :
                return 'E_NOTICE';
                break;
            case 16 :
                return 'E_CORE_ERROR';
                break;
            case 32 :
                return 'E_CORE_WARNING';
                break;
            case 64 :
                return 'E_COMPILE_ERROR';
                break;
            case 128 :
                return 'E_COMPILE_WARNING ';
                break;
            case 256 :
                return 'E_USER_ERROR';
                break;
            case 512 :
                return 'E_USER_WARNING';
                break;
            case 1024 :
                return 'E_USER_NOTICE';
                break;
            case 2048 :
                return 'E_STRICT ';
                break;
            case 4096 :
                return 'E_RECOVERABLE_ERROR';
                break;
            case 8192 :
                return 'E_DEPRECATED';
                break;
            case 16384 :
                return 'E_USER_DEPRECATED';
                break;
            case 32767 :
                return 'E_ALL';
                break;
            case 'Exception':
                return 'Exception';
                break;
        }
        return null;
    }
}