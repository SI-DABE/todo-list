<?php

namespace App\Services;

class ProfileAvatar
{

    private $image;

    public function __construct(
        private $model
    ) {
    }

    public function path()
    {
        if ($this->model->getAvatarName())
            return $this->baseDir() . $this->model->getAvatarName();

        return '/assets/defaults/users/avatar.png';
    }

    public function update($image)
    {
        $this->image = $image;

        if (!empty($this->getTmpFilePath())) {
            $this->model->update(['avatar_name' => $this->getFileName()]);
            move_uploaded_file($this->getTmpFilePath(), $this->getAbsoluteFilePath());
        }
    }

    public function getTmpFilePath()
    {
        return $this->image['tmp_name'];
    }

    public function getAbsoluteFilePath()
    {
        return $this->storeDir() . $this->getFileName();
    }

    private function getFileName()
    {
        $file_name_splitted  = explode('.', $this->image['name']);
        $file_extension = end($file_name_splitted);
        return 'avatar.' . $file_extension;
    }

    private function baseDir()
    {
        return '/assets/uploads/users/' . $this->model->getId() . '/';
    }

    private function storeDir()
    {
        $path = ROOT_PATH . '/public' . $this->baseDir();
        if (!is_dir($path)) mkdir($path, 0777, true);

        return $path;
    }
}
