<?php
    $routing = [
        '/' => 'login',
        '/gallery' => 'gallery',
        '/registration' => 'registration',
        '/photo_upload' => 'photo_upload',
        '/show/' => 'show',
        '/start' => 'start',
        '/create_user' => 'create_user'
    ];

//http://domena/gallery
//modul: default
//controller: gallery
//action: list
//
//http://domena/gallery/13
//modul: default
//controller: gallery
//action: view
//params:
//    id: 13
//
//http://domena/gallery/add
//modul: default
//controller: gallery
//action: add
//
//http://domena/gallery/edit/13
//modul: default
//controller: gallery
//action: edit
//params:
//    id: 13

