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

    function verify_user($users, $login, $password){
        foreach ($users as $user){
            if($user['login'] === $login && $user['password'] === hash("sha256", $password)){
                unset($_SESSION['error']);
                $_SESSION['name'] = $user['name'];
                $_SESSION['user_id'] = $user['_id'];
                return true;
            }
        }
        return false;
    }


    function add_user($user){
        $db = get_db();
        $users = $db->users->find();
        foreach ($users as $actual){
            if($user['login'] == $actual['login'] || $user['mail'] == $actual['mail']){
                return false;
            }
        }
        $db->users->insertOne($user);
        return true;
    }

    function get_users(){
        $db = get_db();
        return $db->users->find();
    }

    function insert_photo($photo){
        $db = get_db();
        $db->photos->insertOne($photo);
    }

    function get_photos(){
        $db = get_db();
        return $db->photos->find();
    }

    function count_photos(){
        $count = 0;
        $photos = get_photos();
        foreach ($photos as $photo) {
            $count++;
        }
        return $count;
    }

    function show_gallery(){
        $db = get_db();
        $photos = $db -> photos;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $next = $page + 1;
        $previous = $page - 1;
        $limit = 3;
        $skip = ($page-1) * $limit;
        $cursor = $photos->find([], ['skip' => $skip, 'limit' => $limit]);
        $total = $photos->count();

        echo '<div style="display: flex; justify-content: space-between; text-align:center;">';
        foreach ($cursor as $photo){
            echo '<div>';
            echo '<a href="http://192.168.56.10:8080/images/' . $photo['name'] . '-wm.' . $photo['type'] .'">';
            echo '<img src="images/' . $photo['name'] . '-th.' . $photo['type'] . '">' . '<a/><br/>';
            echo 'Tytuł: ' . $photo['title'] . '<br/>';
            echo 'Autor: ' . $photo['author'] . '<br/>';
            echo '<input type="checkbox" id="$photo[_id]">';
            echo '</div>';
        }
        echo '</div>';
        echo '<br/>';

        if($page > 1){
            echo '<a href="?page=' . $previous . '">Poprzednia strona</a>';
            if($page * $limit < $total) {
                echo ' <a href="?page=' . $next . '">Następna strona</a>';
            }
        } else {
            if($page * $limit < $total) {
                echo ' <a href="?page=' . $next . '">Następna strona</a>';
            }
        }
    }
