<?php
    function get_db()
    {
        $mongo = new MongoDB\Client(
            "mongodb://localhost:27017/wai",
            [
                'username' => 'wai_web',
                'password' => 'w@i_w3b',
            ]);

        $db = $mongo->wai;
        return $db;
    }

    function authenticate($login, $password) {
        $user = verify_user($login, $password);

        if($user != null) {
            unset($user['password']);
            $_SESSION['profile'] = array(
                'id' => $user['_id'],
                'login' => $user['login'],
                'name' => $user['name']
            );

            $_SESSION['user_id'] = $user['_id'];

            return true;
        }

        return false;
    }

    function verify_user($login, $password){
        $db = get_db();
        $user = $db->users->findOne(['login' => $login]);

        if($user != null && $user['password'] === hash("sha256", $password)){
            return $user;
        }

        return null;
    }

    function add_user($user){
        $db = get_db();
        $users = $db->users->find();
        foreach ($users as $actual){
            if($user['login'] === $actual['login'] || $user['mail'] === $actual['mail']){
                return false;
            }
        }
        $db->users->insertOne($user);

        return true;
    }

    function insert_photo($photo){
        $db = get_db();
        $db->photos->insertOne($photo);
    }

    function count_photos(){
        $db = get_db();
        $photos = $db->photos;
        return $photos->count();
    }

    function get_count_favourites(){
        $db = get_db();
        $photos = $db->photos->find();
        $counter = 0;
        foreach ($photos as $photo){
            if(isset($_SESSION[(string)$photo['_id']])){
                $counter++;
            }
        }
        return $counter;
    }

    function get_favourites($skip, $limit){
        $db = get_db();
        $photos = $db->photos->find();
        $favourites = array();

        foreach ($photos as $photo){
            if(isset($_SESSION[(string)$photo['_id']])){
                array_push($favourites, $photo);
            }
        }

        return array_slice($favourites, $skip, $limit);
    }

    function assign_cursor($skip, $limit) {
        $db = get_db();
        $photos = $db->photos;
        return $photos->find([], ['skip' => $skip, 'limit' => $limit]);
    }

    function remember_photos($photos) {
        $selectedPhotos = array();

        foreach ($photos as $photo) {
            $id = (string)($photo['_id']);

            if(isset($_SESSION[$id])) {
                array_push($selectedPhotos, $id);
            }
        }

        return $selectedPhotos;

    }