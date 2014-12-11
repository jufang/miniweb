<?php

class PaymentController extends ApiController
{

    public function updateStatusAction($payType)
    {
        $status = $this->getStatus($payType);

        $data = array(
                    'auth_token' => $this->session->get('auth_token'),
                    'order_id'   => $this->session->get('order_id'), 
                    'status'     => $status
                );

        $response = $this->execute('payment/updateStatus', $data);

        $resp = json_decode($response);
        $returnCode = $resp->return_code;

        if($returnCode != '0000') {
            throw new Exception($resp->msg);
        }

        if($status == 1){
            return $this->response->redirect('/payment_success.html', true, 301);
        }else {
            return $this->response->redirect('/payment_fail.html', true, 301);
        }
        
        // return $this->sendResponse($response);
    }

    private function getStatus($payType)
    {
        switch ($payType) {
            case 'alipay':
                $status = strtolower($this->request->get('result')) == 'success' ? 1 : 2;
                break;

            case 'upmp':
                $status = $this->request->get('status') == 0 ? 1 : 2;
                break;
            
            default:
                $status = 3;
                break;
        }
        return $status;
    }

}

