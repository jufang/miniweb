<?php

class TicketController extends ApiController
{

    public function getRecomendTicketsAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

    public function getDetailAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

    public function getTicketsAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

    public function getUserTicketsAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

    public function getMyTicketDetailAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

}

