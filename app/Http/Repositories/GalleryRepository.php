<?php


namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository\Repository;
use App\Models\Gallery;


class GalleryRepository extends Repository
{

    public $gallery;

    public function __construct()
    {
        $this->setModel(new Gallery());
    }

    public function all()
    {
        $query = $this->getModel();
        $data = $query->with($this->getRelations())->latest()->paginate($this->getPaginate());
        return $data;
    }

    public function all_for_front()
    {
        $query = $this->getModel();
        $data = $query->with($this->getRelations())->paginate($this->getPaginate());
        return $data;
    }
}
