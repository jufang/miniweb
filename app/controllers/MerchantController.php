<?php

class MerchantController extends ApiController
{

    public function getDetailAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

}