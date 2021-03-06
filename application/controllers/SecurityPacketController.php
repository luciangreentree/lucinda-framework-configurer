<?php
/**
 * STDERR MVC controller running whenever a Lucinda\Framework\SecurityPacket is thrown during STDOUT phase.
 * Class is open for modification if, for example, developers want to simply redirect to callback page when HTML response format is required
 * instead of showing 401/403/404 views.
 */
class SecurityPacketController extends \Lucinda\MVC\STDERR\Controller
{
    const REDIRECT = true;

    /**
     * {@inheritDoc}
     * @see \Lucinda\MVC\STDERR\Controller::run()
     */
    public function run()
    {
        $this->setResponseStatus();
        $this->setResponseBody();
    }
    
    /**
     * Sets response HTTP status code according to outcome of security validation
     */
    private function setResponseStatus()
    {
        switch ($this->request->getException()->getStatus()) {
            case "unauthorized":
                $this->response->setStatus(401);
                break;
            case "forbidden":
                $this->response->setStatus(403);
                break;
            case "not_found":
                $this->response->setStatus(404);
                break;
            default:
                $this->response->setStatus(200);
                break;
        }
    }
    
    /**
     * Sets response body from view file or stream.
     *
     * @throws Exception If content type of response is other than JSON or HTML.
     */
    private function setResponseBody()
    {
        // gets content type
        $contentType = $this->response->headers("Content-Type");
        
        // gets packet status
        $status = $this->request->getException()->getStatus();
        
        // gets wrapped exception
        $exception = $this->request->getException();
        
        // sets response content
        $this->response->attributes("status", $exception->getStatus());
        $this->response->attributes("callback", $exception->getCallback());
        $this->response->attributes("token", $exception->getAccessToken());
    }
}
