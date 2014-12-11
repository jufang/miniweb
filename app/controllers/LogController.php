<?php

class LogController extends ApiController
{

    public function uploadLocationAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

}

