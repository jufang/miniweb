<?php

class UserController extends ApiController
{

    public function loginAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

    public function registerAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

    public function getProfileAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

    public function editProfileAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

    public function changePasswordAction()
    {
        $response = $this->execute();
        
        return $this->sendResponse($response);
    }

    public function uploadImageAction()
    {
        
        // Check if the user has uploaded files
        if ($this->request->hasFiles() == true) {

            // Print the real file names and sizes
            foreach ($this->request->getUploadedFiles() as $file) {

                $data = $this->request->getPost();
                $data['image'] = curl_file_create($file->getPathname(), $file->getType(), $file->getName());
                $response = $this->sendImage('/user/uploadImage', $data);

                break;
            }
        }

        return $this->sendResponse($response->body);
    }

    private function sendImage($uri, $data)
    {
        $data = $this->convert($data);

        $ch = curl_init();
        $options = array(
            CURLOPT_URL            => $this->_config->api->baseUri . $uri,
            CURLOPT_RETURNTRANSFER => true,
            CURLINFO_HEADER_OUT    => true, //Request header
            CURLOPT_HEADER         => true, //Return header
            CURLOPT_SSL_VERIFYPEER => false, //Don't veryify server certificate
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $data
        );
         
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);

        if ($errno = curl_errno($ch)) {
            throw new HttpException(curl_error($ch), $errno);
        }

        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        $response = new Phalcon\Http\Client\Response();
        $response->header->parse(substr($content, 0, $headerSize));
        $response->body = substr($content, $headerSize);

        curl_close($ch);

        return $response;
    }

}

