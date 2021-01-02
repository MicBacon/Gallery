<?php

class GalleryController
{
    public function listAction() {

    }

    public function viewAction() {
        $id = $this->getRequestParam('id');

        $item = $db->gallery->get($id);
        $this->view->item = $item;
    }

    public function addAction() {
        $requestParams = [];
        $request = [];

        $form = '';

        if ($form->isValid($request)) {
            // dodaj galerie
            // otrzymujesz id
            // przekieruj na view /gallery/13
         }
    }

    public function editAction() {

    }

    public function render()
    {
        echo 'Gallery render';
    }
}