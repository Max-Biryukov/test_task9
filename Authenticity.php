<?php

namespace App;

class Authenticity
{
    const INN_LEN = 10;
    const MESSAGE_NOT_FOUND = 'По заданным критериям поиска сведений не найдено.';
    const MESSAGE_INVALID_INN = 'Наличие признака недостоверности.';
    const MESSAGE_INCORRECT_INN = 'Неверный формат ИНН.';
    const MESSAGE_INCORRECT_RESPONSE = 'Ошибка при запросе.';

    const URL_FOR_CHECK_INN = 'https://pb.nalog.ru/search-proc.json';

    const KEY_IN_RESPONSE = 'cmp';

    private $_message;

    public function get( $inn )
    {
        return $this->_processInn( $inn );
    }

    private function _processInn( $inn )
    {
        $this->_message = '';
        $result = [
            'inn' => $inn,
            'message' => '',
            'authenticity' => false,
        ];

        if( $this->_isCorrectInn($inn) ) {
            $result[ 'authenticity' ] = $this->_checkInn( $inn );
        } else {
            $this->_message = self::MESSAGE_INCORRECT_INN;
        }

        $result[ 'message' ] = $this->_message;
        return $result;
    }

    private function _isCorrectInn( $inn )
    {
        return is_numeric( $inn ) && strlen( $inn ) == self::INN_LEN;
    }

    private function _checkInn( $inn )
    {
        $ch = curl_init();

        $headers = [
            'Content-type: application/x-www-form-urlencoded',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win61; x64; rv:65.0) Gecko/20100101 Firefox/65.0',
        ];

        curl_setopt( $ch, CURLOPT_URL,self::URL_FOR_CHECK_INN );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS,'mode=quick&query=' . $inn );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

        $response = curl_exec( $ch );
        curl_close( $ch );

        return $response !== false ? $this->_processResponse( $response ) : [];
    }

    private function _processResponse( $response )
    {

        try{
            $data = json_decode( $response );
        } catch( \Exception $e ){
            echo $e->getCode() . ' ' . $e->getMessage();
            die();
        }

        $key = self::KEY_IN_RESPONSE;
        if(
            !empty( $data->$key ) &&
            !empty( $data->$key->data ) &&
            is_array( $data->$key->data )
        ){
            $blockData = array_shift( $data->$key->data );

            if( !empty($blockData->invalid) ){
                $this->_message = self::MESSAGE_INVALID_INN;
                return false;
            } else {
                $this->_message = self::MESSAGE_NOT_FOUND;
                return true;
            }

        }

        $this->_message = self::MESSAGE_INCORRECT_RESPONSE;
        return false;
    }


}