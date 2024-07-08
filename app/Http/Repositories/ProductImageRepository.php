<?php


namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository\Repository;
use App\Models\Product;
use App\Models\ProductImage;
use Exception;
use Tymon\JWTAuth\JWTAuth;

class ProductImageRepository extends Repository
{

    public function __construct()
    {
        $this->setModel(new ProductImage());
    }

    public function all($id = null)
    {
        if (is_null($id)) {
            return $this->getModel()->with($this->getRelations())->paginate(10);
        } else {
            return $this->getModel()->where('product_id', $id)->with($this->getRelations())->paginate(10);
        }
    }

    public function save($image, $id)
    {
        return $this->getModel()->create([
            'product_id' => $id,
            'file_path' => $image['file_path'],
            'file_type' => $image['file_type'],
            'file_default' => ($image['file_default'] == "1" || $image['file_default'] == true) ? 1 : 0,
        ]);
    }

    public function get($id)
    {
        if ($this->getFromWeb()) {
            $user = auth()->user();
        } else {
            $user = \request('jwt.user', new \stdClass());
        }
        return $this->getModel()->where('id', $id)->with($this->getRelations())->first();
    }

    public function delete($id)
    {

    }

    public function getRepository()
    {
        return $this;
    }

}
