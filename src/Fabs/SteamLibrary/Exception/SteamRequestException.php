<?php


namespace Fabs\SteamLibrary\Exception;


use Psr\Http\Message\RequestInterface;

class SteamRequestException extends SteamLibraryException
{

    /** @var RequestInterface */
    private $request = null;
    /** @var RequestInterface */
    private $response = null;


    /**
     * SteamRequestException constructor.
     * @param RequestInterface $request
     * @param RequestInterface|null $response
     */
    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;

        parent::__construct('Steam request exception for request url ' . $request->getUri()->getPath());
    }


    /**
     * @return RequestInterface
     * @author necipallef <necipallef@gmail.com>
     */
    public function getRequest()
    {
        return $this->request;
    }


    /**
     * @return null|RequestInterface
     * @author necipallef <necipallef@gmail.com>
     */
    public function getResponse()
    {
        return $this->response;
    }
}