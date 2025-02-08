<?php

namespace Core;

class ErrorHandler
{
    private bool $isDev;

    public function __construct(bool $isDev = false)
    {
        $this->isDev = $isDev;
    }

    public function register():void{
        set_error_handler([$this,'handleError']);
        set_exception_handler([$this,'handleException']);
        register_shutdown_function([$this,'handleShutdown']);
    }

    /**
     * @throws \ErrorException
     */
    public function handleError(int $errno, string $errstr, string $errfile, int $errline){
        throw new \ErrorException($errstr,0,$errno,$errfile,$errline);
    }

    public function handleException(\Throwable $e): void
    {
        error_log($this->formatLog($e));

        if ($this->isDev){
            $this->displayError($e);
        }else{
            http_response_code(500);
            echo "500 Internal Server Error";
        }
    }

    public function handleShutdown(){
        $error = error_get_last();
        if ($error !==null && in_array($error['type'],[E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)){
            $this->handleException(new \ErrorException($error['message'],0,$error['type'],$error['file'],$error['line']));
        }
    }

    private function formatLog(\Throwable $e){
        return sprintf("[%s] %s in %s:%d\nStack trace:\n%s\n",
            date('Y-m-d H:i:s'),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString());
    }

    private function displayError(\Throwable $e){
        echo "<h1>Error: " . htmlspecialchars($e->getMessage()) . "</h1>";
        echo "<p>File: " . htmlspecialchars($e->getFile()) . " at line " . $e->getLine() . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
}