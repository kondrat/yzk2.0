<?php

/**
 * retriving data from api.direct.yandex.ru via ajax
 *
 * @author kondrat
 */
class getYnDataComponent extends Component {

    /**
     *
     * @param sting $method
     * @param array $params
     * @return json 
     */
    function getYnData($pathToCerts = NULL, $method= NULL, $params=NULL) {
        // create a new cURL resource
        $ch = curl_init();

        //@todo add opportinity to add more certs per each user

        //$pathToCerts = Configure::read('pathToCerts');
        
        

        $url = "https://soap.direct.yandex.ru/json-api/v4/";

        //@todo sinitize this
        //$method = ''; //$this->data['method'];
        //$params = '';
        //request for yandex in json.
        
        if($params == NULL){
            $jsonReq = json_encode(
                array(
                    "method" => $method
                )
            ); 
        } else {

            $jsonReq = json_encode(
                    array(
                        "method" => $method,
                        "param" => $params
                    )
            );
        }




            // set URL and other options
            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CAPATH, $pathToCerts);
            curl_setopt($ch, CURLOPT_CAINFO, $pathToCerts."/cacert.pem");
            curl_setopt($ch, CURLOPT_SSLCERT, $pathToCerts."/cert.crt");
            curl_setopt($ch, CURLOPT_SSLKEY, $pathToCerts."/private.key");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonReq);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $contents = curl_exec($ch);

            if (curl_errno($ch) != 0) {               
                $contents["error"] = ('CURL_error: ' . curl_errno($ch) . ', ' . curl_error($ch));
                $contents = json_encode($contents);            
            }

            // close the cURL resource and free the system resources
            curl_close($ch);
            
            return $contents;
    }


}

?>
