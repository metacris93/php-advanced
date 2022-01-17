<?php

namespace App\Traits;

trait HasDefaultImage
{
  public function getImage() {
    if (!$this->image) {
        return "https://ui-avatars.com/api/?size=255";
    }
    return $_ENV['APP_URL'].'data'.DIRECTORY_SEPARATOR.$this->image;
  }
}