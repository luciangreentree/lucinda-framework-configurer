<?php
/**
 * Error handler that prevents STDERR MVC FrontController handling its own errors. Developers may need to modify contents of handle method to give more or
 * less information about bug encountered.
 */
class EmergencyHandler implements \Lucinda\MVC\STDERR\ErrorHandler
{
    /**
     * {@inheritDoc}
     * @see \Lucinda\MVC\STDERR\ErrorHandler::handle()
     */
    public function handle($exception)
    {
        $xml = simplexml_load_file(dirname(dirname(__DIR__))."/stderr.xml");
        $displayErrors = $xml->application->display_errors->{ENVIRONMENT};
        echo json_encode(array("status"=>"error","body"=>($displayErrors?array(
            "message"=>$exception->getMessage(),
            "file"=>$exception->getFile(),
            "line"=>$exception->getLine(),
            "trace"=>$exception->getTraceAsString()
        ):"")));
        die();
    }
}
