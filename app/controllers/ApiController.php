<?php

class ApiController extends ControllerBase
{

    protected $_apiProvider;

    protected $_apiKey;

    protected $_config;

    public function initialize()
    {
        $this->_config = new \Phalcon\Config\Adapter\Ini(CONFIG_PATH . 'config.ini');

        $this->_apiKey = $this->_config->api->key;

        $this->_apiProvider  = \Phalcon\Http\Client\Request::getProvider(); // get available provider Curl or Stream
        
        $this->_apiProvider->setBaseUri($this->_config->api->baseUri);
        $this->_apiProvider->header->set('Accept', 'application/json');
    }

    protected static function signature($params, $signKey)
    {
        $map = array();
        if (is_array($params)) {
            $map = & $params;
        } else if (is_object($params)) {
            $map = get_object_vars($params);
        }
        $content = '';
        foreach ($map as $key => $value) {
            if($key == '_url' || $key == 'device_id' || $key == 'sign' || $key == 'auth_token' || $key == 'image') {
                continue;
            }
            $content .= $key . $value;
        }
        $content .= $signKey;
        return sha1(urlencode($content));      
    }

    protected function execute($uri='', $data=array())
    {
        $method = strtoupper($this->request->getMethod());
        if($uri == ''){
            $uri = substr($this->router->getRewriteUri(), 1);
        }

        $request = $data ?: $this->getRequest($method);
        $params  = $this->convert($request);

        $response = $this->getResponse($uri, $params, $method);

        return $response->body;
    }

    protected function sendResponse($response)
    {
        $this->view->disable();

        $this->response->setContent($response);

        return $this->response->send();
    }

    protected function convert($params)
    {
        $ipAddress = $this->request->getClientAddress();
        $params['device_id'] = $ipAddress;

        $sign = self::signature($params, $this->_apiKey);
        $params['sign'] = $sign;

        return $params;
    }

    private function getRequest($method='GET')
    {
        switch ($method) {
            case 'POST':
            case 'DELETE':
                $request = $this->request->getPost();
                break;

            case 'PUT':
                $request = $this->request->getPut();
                break;

            case 'GET':
                $request = $this->request->get();
                break;

            default:
                throw new Exception('unknown request method!');
                break;
        }
        return $request;
    }

    private function getResponse($uri, $params, $method='GET')
    {
        switch ($method) {
            case 'POST':
                $response = $this->_apiProvider->post($uri, $params);
                break;
            case 'PUT':
                $response = $this->_apiProvider->put($uri, $params);
                break;
            case 'DELETE':
                $response = $this->_apiProvider->delete($uri, $params);
                break;

            case 'GET':
                $response = $this->_apiProvider->get($uri, $params);
                break;

            default:
                throw new Exception('unknown response method!');
                break;
        }
        return $response;
    }

}