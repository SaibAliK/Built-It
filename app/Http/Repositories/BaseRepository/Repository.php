<?php


namespace App\Http\Repositories\BaseRepository;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Auth;
use Tymon\JWTAuth\JWTAuth;

class Repository
{
    private object $model;

    protected array $relations = [];
    protected array $select = ['*'];

    protected bool $fromWeb = false;

    protected object $user;

    protected int $paginate = 10;

    protected bool $fromAdmin = false;


    public function getQuery()
    {
        return $this->getModel()->query();
    }

    public function getModel(): object
    {
        return $this->model;
    }
    public function setModel(object $model)
    {
        $this->model = $model;
    }

    public function setRelations($relations)
    {
        $this->relations = $relations;
    }

    public function getRelations(): array
    {
        return $this->relations;
    }

    public function setFromWeb($bool)
    {
        $this->fromWeb = $bool;
    }
    public function setFromAdmin($bool)
    {
        $this->fromAdmin = $bool;
    }
    public function getFromAdmin(): bool
    {
       return $this->fromAdmin;
    }

    public function getFromWeb():bool
    {
        return $this->fromWeb;
    }

    public function getUser($fromSession=false): object
    {
     
        if($fromSession){
            $user = session()->get('USER_DATA');
        }else{
            $user = auth()->user();
        }

        if(!isset($user->id)){
            $user = null;
        }
        $this->user = $user;
        return $this->user;
    }

    public function setPaginate($int)
    {
        $this->paginate = $int;
    }

    public function getPaginate()
    {
        return $this->paginate;
    }

    public function setSelect(array $select){
        $this->select = $select;
    }

    public function getSelect(){
       return $this->select;
    }


}
