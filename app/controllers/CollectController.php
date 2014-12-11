<?php

class CollectController extends ApiController
{

    public function cancelCollectAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

    public function addCollectAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

    public function myCollectAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

}

