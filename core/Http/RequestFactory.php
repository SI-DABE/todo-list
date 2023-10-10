<?php

namespace Core\Http;

class RequestFactory
{
    public static function createRequest()
    {
        $request = new Request();
        $request->setQerryStrings($_GET);
        $request->setBody(file_get_contents('php://input'));
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headers[explode('HTTP_', $key)[1]] = $value;
            }
        }
        $request->setHeaders($headers);
        if (isset($_REQUEST['_method'])) {
            if (~array_search($_REQUEST['_method'], Request::ALLOWED_METHODS)) {
                $request->setMethod($_REQUEST['_method']);
            }
        } else {
            $request->setMethod($_SERVER['REQUEST_METHOD']);
        }
        $request->setPath(strtok($_SERVER['REQUEST_URI'], '?'));
        $request->setHostName($_SERVER['SERVER_NAME']);
        $splitedPath = explode('/', $request->getPath());
        array_shift($splitedPath);
        if (str_ends_with($request->getPath(), '/')) {
            array_pop($splitedPath);
        }
        $request->setParams($splitedPath + $_GET + $_POST);
        return $request;
    }
}
