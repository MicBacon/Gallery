<?php
    require_once 'business.php';
    require_once 'utilities.php';

    function login(&$model) {
        $model['login'] = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = getPostParam('login');
            $password = getPostParam('password');

            $model['login'] = $login;

            if (authenticate($login, $password)) {
                addErrorMessage('info', 'Zalogowano pomyślnie');
                return 'redirect:gallery';
            } else {
                addErrorMessage('error', 'Błędny login bądź hasło :(');
            }
        } else {
            unlog_user();
        }

        return 'login_view';
    }

    function registration(&$model) {
        $model['registration'] = array(
            'name' => '',
            'mail' => '',
            'login' => ''
        );
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = getPostParam('name', '');
            $mail = getPostParam('mail', '');
            $login = getPostParam('login', '');
            $password = getPostParam('password');
            $reppassword = getPostParam('reppassword');

            $model['registration'] = array(
                'name' => $name,
                'mail' => $mail,
                'login' => $login
            );

            if ($reppassword !== $password) {
                addErrorMessage('error', 'Podane hasła nie są identyczne');
                return 'registration_view';
            }

            $user = [
                'name' => $name,
                'mail' => $mail,
                'login' => $login,
                'password' => hash("sha256", $password)
            ];

            if (add_user($user)) {
                return 'redirect:login';
            }

            addErrorMessage('error', 'Login lub e-mail został już zajęty');
            return 'registration_view';
        }
        return 'registration_view';
    }

    function gallery(&$model) {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $next = $page + 1;
        $previous = $page - 1;
        $limit = 3;
        $skip = ($page-1) * $limit;
        $cursor = assign_cursor($skip, $limit);
        $total = count_photos();

        $model['pagination'] = array(
          'page' => $page,
          'next' => $next,
          'previous' => $previous,
          'limit' => $limit,
          'skip' => $skip,
          'cursor' => $cursor->toArray(),
          'total' => $total
        );

        $model['selectedPhotos'] = remember_photos($model['pagination']['cursor']);

        return 'gallery_view';
    }

    function favourites(&$model){
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $next = $page + 1;
        $previous = $page - 1;
        $limit = 3;
        $skip = ($page-1) * $limit;
        $cursor = get_favourites($skip, $limit);
        $total = get_count_favourites();

        $model['pagination'] = array(
            'page' => $page,
            'next' => $next,
            'previous' => $previous,
            'limit' => $limit,
            'skip' => $skip,
            'cursor' => $cursor,
            'total' => $total
        );

        return 'favourites_view';
    }

    function start() {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $max_size = 1024*1024;
            if(is_uploaded_file($_FILES['file']['tmp_name'])){
                if($_FILES['file']['size'] > $max_size){
                    addErrorMessage('error', "Rozmiar pliku nie może przekraczać 1MB");
                    return 'start_view';
                }else if($_FILES['file']['type'] == 'image/jpeg' ||
                    $_FILES['file']['type'] == 'image/png' ||
                    $_FILES['file']['type'] == 'image/jpg'){
                    switch ($_FILES['file']['type']){
                        case 'image/png':
                            $wmImage = imagecreatefrompng($_FILES['file']['tmp_name']);
                            break;
                        default:
                            $wmImage = imagecreatefromjpeg($_FILES['file']['tmp_name']);
                            break;
                    }

                    $photo = [
                        'name' => pathinfo($_FILES['file']['name'], PATHINFO_FILENAME),
                        'type' => pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION),
                        'author' => $_POST['author'],
                        'title' => $_POST['title']
                    ];

                    insert_photo($photo);

                    $width = imagesx($wmImage);
                    $height = imagesy($wmImage);

                    $wmText = $_POST['watermark'];
                    $wmColor = imagecolorallocate($wmImage, 255, 255, 255);
                    $font = "static/fonts/font.ttf";

                    $text_size = imagettfbbox(50, 45, $font, $wmText);
                    $text_width = max([$text_size[2], $text_size[2]]) - min([$text_size[0], $text_size[6]]);
                    $text_height = max([$text_size[5], $text_size[7]]) - min([$text_size[1], $text_size[3]]);

                    $centerX = CEIL(($width - $text_width) / 2);
                    $centerY = CEIL(($height - $text_height) / 2);

                    $centerX = $centerX<0 ? 0 : $centerX;
                    $centerY = $centerY<0 ? 0 : $centerY;

                    $thImage = imagecreatetruecolor(200, 125);
                    imagecopyresized($thImage, $wmImage, 0, 0, 0, 0,
                        200, 125, $width, $height);

                    imagettftext($wmImage, 50, 45, $centerX, $centerY,
                        $wmColor, $font, $wmText);

                    switch ($_FILES['file']['type']){
                        case 'image/png':
                            imagepng($wmImage, "images/".pathinfo($_FILES['file']['name'], PATHINFO_FILENAME)."-wm.png");
                            imagepng($thImage, "images/".pathinfo($_FILES['file']['name'], PATHINFO_FILENAME)."-th.png");
                            break;
                        default:
                            imagejpeg($wmImage, "images/".pathinfo($_FILES['file']['name'], PATHINFO_FILENAME)."-wm.jpg", 100);
                            imagejpeg($thImage, "images/".pathinfo($_FILES['file']['name'], PATHINFO_FILENAME)."-th.jpg", 100);
                            break;
                    }

                    move_uploaded_file($_FILES['file']['tmp_name'],
                        $_SERVER['DOCUMENT_ROOT'].'/images/'.$_FILES['file']['name']);

                    addErrorMessage('info', "Plik został prawidłowo dodany");
                    return 'redirect:gallery';
                }else{
                    addErrorMessage('error', "Możliwe formaty plików: JPG lub PNG");
                    return 'start_view';
                }
            }else {
                addErrorMessage('error', "Błąd przesyłania pliku :(");
                return 'start_view';
            }
        }
        return 'start_view';
    }

    function mailing(){
        return 'mail_view';
    }

    function unlog() {
        unlog_user();
        return 'redirect:login';
    }

    function remember() {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $selectedPhotos = getPostParam('selectedPhotos', array());
            $photos = getPostParam('photos', array());

            $toAdd = array_diff($photos, $selectedPhotos);
            $toDelete = array_diff($selectedPhotos, $photos);

            if(count($toAdd)) {
                foreach ($toAdd as $photo) {
                    $_SESSION[$photo] = true;
                }
            }

            if(count($toDelete)) {
                foreach ($toDelete as $photo) {
                    if(isset($_SESSION[$photo])) {
                        unset($_SESSION[$photo]);
                    }
                }
            }
        }

        return 'redirect:gallery';
    }

    function remove_from_remember() {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $photos = getPostParam('photos', array());

            if(count($photos)) {
                foreach ($photos as $photo) {
                    if(isset($_SESSION[$photo])) {
                        unset($_SESSION[$photo]);
                    }
                }
            }
        }

        return 'redirect:favourites';
    }

    function send_mail(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            /*$mailingList = array(
                'boczon.michal@gmail.com'
                'ku.niewinski@gmail.com',
                'cooleshankanerc@gmail.com',
                'piechotkan@gmail.com',
                'roomiencenmn@gmail.com'
            );

            $title = $_POST['email-title'];
            $content = $_POST['email-content'];

            foreach($mailingList as $mail){
                mail($mail, $title, $content);
            }*/


            if(!mail("boczon.michal@gmail.com", "title", 'Hello', 'Michal')){
                throw new Exception("Cannot send an e-mail");
            }else{
                mail("boczon.michal@gmail.com", "title", 'Hello', 'Michal');
            }

        }

        return 'redirect:mailing';
    }