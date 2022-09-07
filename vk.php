<?php

require_once 'config.php';

class VK
{
    public $data;

    public function __construct() {
        $this->data = json_decode(file_get_contents('php://input'));
        if ($this->data->type == 'confirmation') exit(Config::KEY);
        else print('ok');
    }

    // Отправка JSON
    public function call($method, $params = []) {
        $params['access_token'] = Config::TOKEN;
        $params['v'] = Config::VERSION;
        $url = 'https://api.vk.com/method/'.$method.'?'.http_build_query($params);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($curl);
        curl_close($curl);
        return json_decode($json);
    }

    // Методы ВК
    public function messages_send($peer_id, $message, $attachment, $keyboard) {
        return VK::call('messages.send', [
            'random_id' => rand(),
            'peer_id' => $peer_id,
            'message' => $message,
            'attachment' => $attachment,
            'keyboard' => $keyboard
        ]);
    }

    // Единный метод загрузки изображений на сервер
    public function photos_upload_server($url, $image_path) {
        $params['photo'] = new CURLFile($image_path);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        $json = curl_exec($curl);
        curl_close($curl);
        return json_decode($json);
    }

    public function photos_get_messages_upload_server($peer_id) {
        return VK::call('photos.getMessagesUploadServer', [
            'peer_id' => $peer_id
        ]);
    }

    public function photos_save_messages_photo($photo, $server, $hash) {
        return VK::call('photos.saveMessagesPhoto', [
            'photo' => $photo,
            'server' => $server,
            'hash' => $hash
        ]);
    }
}