<?php


namespace App\Http\Repositories;


use App\Http\Repositories\BaseRepository\Repository;
use App\Models\Color;
use Exception;


class ColorRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(new Color());
    }
}
