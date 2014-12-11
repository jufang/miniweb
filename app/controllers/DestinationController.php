<?php

class DestinationController extends ApiController
{

    public function getCityCodeAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

    public function getCitiesAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

}

