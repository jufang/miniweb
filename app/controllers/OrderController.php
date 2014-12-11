<?php

class OrderController extends ApiController
{

    public function orderAction()
    {
        $response = $this->execute();

        $resp = json_decode($response);
        $returnCode = $resp->return_code;

        if($returnCode == '0000'){
            $payType   = $this->request->getPost('pay_type');
            $price     = $this->request->getPost('price');
            $num       = $this->request->getPost('num');
            $orderName = $this->request->getPost('order_name');

            $data             = $resp->data[0];
            $data->order_name = $orderName;
            $data->total_fee  = $price * $num;

            $resp->data[0] = array('pay_type' => $payType, 'pay_data' => $this->payment($payType, $data));
            $response = json_encode($resp);

            $this->session->set('auth_token', $this->request->getPost('auth_token'));
            $this->session->set('order_id', $data->order_id);
        }
        
        return $this->sendResponse($response);
    }

    private function payment($payType, &$data)
    {
        switch ($payType) {
            case 1:
                return $this->callAlipay($data);
                break;

            case 2:
                return $this->callUpmp($data);
                break;
            
            default:
                throw new Exception('unsupported payment type!');
                break;
        }
    }

    private function callAlipay(&$data)
    {
        $req_id = date('Ymdhis');
        $token = $this->requestAlipayToken($req_id, $data);
        $form = $this->requestAlipayForm($req_id, $token);
        
        return $form;
    }

    private function requestAlipayToken($req_id, &$data)
    {
        $config = $this->_config->alipay;

        $req_data  = '<direct_trade_create_req>';
        $req_data .= '<notify_url>'.$config->notify_url.'</notify_url>';
        $req_data .= '<call_back_url>'.$config->call_back_url.'</call_back_url>';
        $req_data .= '<seller_account_name>'.$config->seller_email.'</seller_account_name>';
        $req_data .= '<out_trade_no>'.$data->order_no.'</out_trade_no>';
        $req_data .= '<subject>'.$data->order_name.'</subject>';
        $req_data .= '<total_fee>'.$data->total_fee.'</total_fee>';
        $req_data .= '<merchant_url>'.$config->merchant_url.'</merchant_url>';
        $req_data .= '</direct_trade_create_req>';

        $params = array(
                "service"        => "alipay.wap.trade.create.direct",
                "partner"        => trim($config->partner),
                "sec_id"         => trim($config->sign_type),
                "format"         => $config->format,
                "v"              => $config->version,
                "req_id"         => $req_id,
                "req_data"       => $req_data,
                "_input_charset" => trim(strtolower($config->input_charset))
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($config);
        $html = $alipaySubmit->buildRequestHttp($params);

        //URLDECODE返回的信息
        $html = urldecode($html);

        //解析远程模拟提交后返回的信息
        $parseHtml = $alipaySubmit->parseResponse($html);

        //获取request_token
        $token = $parseHtml['request_token'];

        return $token;
    }

    private function requestAlipayForm($req_id, $token)
    {
        $config = $this->_config->alipay;

        $req_data = '<auth_and_execute_req><request_token>' . $token . '</request_token></auth_and_execute_req>';

        //构造要请求的参数数组，无需改动
        $parameter = array(
                "service" => "alipay.wap.auth.authAndExecute",
                "partner" => trim($config->partner),
                "sec_id" => trim($config->sign_type),
                "format"    => $config->format,
                "v" => $config->version,
                "req_id"    => $req_id,
                "req_data"  => $req_data,
                "_input_charset"    => trim(strtolower($config->input_charset))
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($config);
        $form = $alipaySubmit->buildRequestForm($parameter, 'get', '确认');

        return $form;
    }

    private function callUpmp(&$data)
    {
        $config = $this->_config->upmp;

        $tn = $data->pay_order_no;
        if(empty($tn)){
            throw new Exception('unknown trade number!');
        }

        $resultURL = urlencode($config->resultURL);
        $useTestMode = $config->useTestMode;

        $payDataStr = 'tn='.$tn.',resultURL='.$resultURL.',useTestMode='.$useTestMode;
        $payData = urlencode(base64_encode($payDataStr));

        return $payData;
    }

    public function getOrdersAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

    public function getDetailAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

    public function cancelOrderAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

    public function deleteOrderAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

}

