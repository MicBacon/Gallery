<?php
    require_once 'business.php';

    function login(&$model){
        unset($_SESSION['regerror']);
        return 'login_view';
    }

    function registration(&$model){
        unset($_SESSION['error']);
        return 'registration_view';
    }

    function create_user(&$model){
        unset($_SESSION['regerror']);
        $name = getPostParam('name'); //$_POST['name'];
        $mail = $_POST['mail'];
        $login = $_POST['login'];
        $password = $_POST['password'];
        $reppassword = $_POST['reppassword'];

        if($reppassword !== $password){
            $_SESSION['regerror'] = '<span style="color:#8b0000">Podane hasła nie są identyczne</span><br/>';
            return 'registration_view';
        }

        $user = [
            'name' => $name,
            'mail' => $mail,
            'login' => $login,
            'password' => hash("sha256", $password)
        ];

        if(add_user($user)){
            return 'login_view';
        }

        $_SESSION['regerror'] = '<span style="color:#8b0000">Login lub e-mail został już zajęty</span><br/>';
        return 'registration_view';
    }

    /**
    * @param $name string
    * @return mixed|null
    */
    function getPostParam($name) {
        return (isset($_POST[$name])) ? $_POST[$name] : null;
    }

    function start(&$model){
        return 'gallery_view';
    }

    function gallery(&$model){
        $login = isset($_POST['login']) ? $_POST['login'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;

        $users = get_users();

        if(verify_user($users, $login, $password)){
            return 'gallery_view';
        }else {
            $_SESSION['error'] = '<span style="color:#8b0000">Błędny login lub hasło</span>';
            return 'login_view';
        }
    }

    function show(&$model){
        return 'show_view';
    }

    function photo_upload(&$model){
        $max_size = 1024*1024;
        unset($_SESSION['pictureerror']);

        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            if($_FILES['file']['size'] > $max_size){
                $_SESSION['pictureerror'] = "<span style='color:darkred'>Rozmiar pliku nie może przekraczać 1MB</span><br/>";
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

            }else{
                $_SESSION['pictureerror'] = "<span style='color:darkred'>Możliwe formaty plików: JPG lub PNG</span><br/>";
            }
        }else {
            $_SESSION['pictureerror'] = "<span style='color:darkred'>Błąd przesyłania pliku :(</span><br/>";
        }
        return 'gallery_view';
    }
