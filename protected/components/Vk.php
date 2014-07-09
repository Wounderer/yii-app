<?php

class Vk
{
    private $version = '5.22';

    private $clientId = '4452192';

    private $clientSecret = 'O4mSoHIH6QzTx5I1CDXo';

    private $redirectUri = '';

    public function __construct()
    {
        $this->redirectUri = Yii::app()->getBaseUrl(true);
    }

    /**
     * @return bool
     */
    public function hasToken()
    {
        return $this->getToken() ? true : false;
    }


    public function clearToken()
    {
        Yii::app()->user->setState('vkToken', null);
    }

    /**
     * @return null|array
     */
    private function getToken()
    {
        $token = Yii::app()->user->getState('vkToken');
        $date = new DateTime();
        if ($token['expiresDateTime'] < $date) {
            $this->clearToken();

            return null;
        }

        return $token;
    }

    /**
     * @return null|string
     */
    public function getAccessToken()
    {
        $token = $this->getToken();

        return !empty($token['access_token']) ? $token['access_token'] : null;
    }

    /**
     * @return null|string
     */
    public function getUserId()
    {
        $token = $this->getToken();

        return !empty($token['user_id']) ? $token['user_id'] : null;
    }

    /**
     * @return string
     */
    public function authGetLink()
    {
        $linkParams = array(
            'client_id' => $this->clientId,
            'scope' => 'photos',
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'v' => $this->version,
        );

        return 'https://oauth.vk.com/authorize?' . urldecode(http_build_query($linkParams));
    }

    /**
     * @param $code
     */
    public function authGetToken($code)
    {
        $params = array(
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
        );

        // Get token using specified code
        $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);
        $date = new DateTime();
        $date->add(new DateInterval('PT' . $token['expires_in'] . 'S'));

        // Add info about token lifetime into the session
        $token['expiresDateTime'] = $date;
        Yii::app()->user->setState('vkToken', $token);
    }

    /**
     * Возвращает расширенную информацию о пользователях.
     *
     * @param string $userIds Перечисленные через запятую идентификаторы пользователей или их короткие имена (screen_name).
     *                          По умолчанию — идентификатор текущего пользователя.
     *                          Список строк, разделенных через запятую, количество элементов должно составлять не более 1000
     * @param string $fields Список дополнительных полей, которые необходимо вернуть.
     *                          Доступные значения: sex, bdate, city, country, photo_50, photo_100, photo_200_orig, photo_200,
     *                          photo_400_orig, photo_max, photo_max_orig, online, online_mobile, lists, domain, has_mobile,
     *                          contacts, connections, site, education, universities, schools, can_post, can_see_all_posts,
     *                          can_see_audio, can_write_private_message, status, last_seen, common_count, relation, relatives,
     *                          counters, screen_name, timezone, occupation
     *                          Список строк, разделенных через запятую.
     * @param string $nameCase Падеж для склонения имени и фамилии пользователя. Возможные значения: именительный – nom,
     *                          родительный – gen, дательный – dat, винительный – acc, творительный – ins, предложный – abl.
     *                          По умолчанию nom.
     *
     * @return array
     */
    public function usersGet($userIds = '', $fields = '', $nameCase = '')
    {
        return $this->query('users.get', [
            'user_ids' => $userIds,
            'fields' => $fields,
            'name_case' => $nameCase,
        ]);
    }

    /**
     * Создает пустой альбом для фотографий.
     * Для вызова этого метода Ваше приложение должно иметь права: photos.
     *
     * @param string $title Название альбома.
     * @param int $groupId Идентификатор сообщества, в котором создаётся альбом. Для группы privacy и comment_privacy
     *                              могут принимать два значения: 0 — доступ для всех пользователей, 1 — доступ только для участников группы.
     * @param string $description Текст описания альбома.
     * @param int $commentPrivacy Уровень доступа к комментированию альбома. Возможные значения: 0 — все пользователи,
     *                              1 — только друзья, 2 — друзья и друзья друзей, 3 — только я.
     * @param int $privacy Уровень доступа к альбому. Возможные значения: 0 — все пользователи, 1 — только друзья,
     *                              2 — друзья и друзья друзей, 3 — только я.
     * @return array
     */
    public function photosCreateAlbum($title, $groupId = 0, $description = '', $commentPrivacy = 0, $privacy = 0)
    {
        return $this->query('photos.createAlbum', [
            'title' => $title,
            'group_id' => $groupId,
            'description' => $description,
            'comment_privacy' => $commentPrivacy,
            'privacy' => $privacy,
        ]);
    }

    /**
     * Возвращает адрес сервера для загрузки фотографий.
     * После успешной загрузки фотография может быть сохранена с помощью метода photos.save.
     *
     * @param int $albumId Идентификатор альбома
     * @param int $groupId Идентификатор сообщества, которому принадлежит альбом (если необходимо загрузить фотографию в альбом сообщества)
     * @return array
     */
    public function photosGetUploadServer($albumId, $groupId = 0)
    {
        return $this->query('photos.getUploadServer', [
            'album_id' => $albumId,
            'group_id' => $groupId,
        ]);
    }

    /**
     * Сохраняет фотографии после успешной загрузки.
     * Для вызова этого метода Ваше приложение должно иметь права: photos.
     *
     * @param int $albumId Идентификатор альбома, в который необходимо сохранить фотографии.
     * @param string $server Параметр, возвращаемый в результате загрузки фотографий на сервер.
     * @param string $photosList Параметр, возвращаемый в результате загрузки фотографий на сервер.
     * @param string $hash Параметр, возвращаемый в результате загрузки фотографий на сервер.
     * @param int $groupId Идентификатор сообщества, в которое необходимо сохранить фотографии.
     * @param int $latitude Географическая широта, заданная в градусах (от -90 до 90).
     * @param int $longitude Географическая долгота, заданная в градусах (от -180 до 180).
     * @param string $caption Текст описания фотографии.
     * @param string $description Текст описания альбома.
     * @return array
     */
    public function photosSave($albumId, $server, $photosList, $hash, $groupId = 0, $latitude = 0, $longitude = 0, $caption = '', $description = '')
    {
        return $this->query('photos.save', [
            'album_id' => $albumId,
            'server' => $server,
            'photos_list' => $photosList,
            'hash' => $hash,
            'group_id' => $groupId,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'caption' => $caption,
            'description' => $description,
        ]);
    }

    public function photosUpload($uploadUrl, $photos)
    {
        $filesParams = [];
        $i = 1;
        foreach ($photos as $filename) {
            $filesParams['file' . $i++] = '@' . $filename;
        }

        $curl = curl_init($uploadUrl);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $filesParams);
        $data = curl_exec($curl);
        curl_close($curl);

        return json_decode($data, true);
    }

    /**
     * @param $method
     * @param $params
     * @return array
     * @throws CHttpException
     */
    private function query($method, $params)
    {
        $params = array_filter($params);
        $params = array_merge($params, ['access_token' => $this->getAccessToken()]);
        $result = json_decode(file_get_contents('https://api.vk.com/method/' . $method . '?' . urldecode(http_build_query($params))), true);
        if (isset($result['error']['error_msg'])) {
            throw new CHttpException($result['error']['error_code'], $result['error']['error_msg']);
        }

        return $result['response'];
    }
} 