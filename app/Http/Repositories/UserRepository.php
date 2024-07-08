<?php


namespace App\Http\Repositories;

use App\Http\Dtos\SendEmailDto;
use App\Http\Dtos\UserDto;
use App\Http\Dtos\UserRegisterDto;
use App\Http\Libraries\DataTable;
use App\Http\Repositories\BaseRepository\Repository;
use App\Jobs\SendMail;
use App\Models\User;
use App\Traits\EMails;
use Carbon\Carbon;
use ErrorException;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\DataTransferObject\DataTransferObject;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class UserRepository extends Repository
{
    public FcmRepository $fcmRepository;

    public function __construct()
    {
        $this->setModel(new User());
        $this->fcmRepository = new FcmRepository();
    }

    public function getDeliveryCompany()
    {
        $user = $this->getModel()->where('user_type', 'company')->select($this->getSelect())->with($this->getRelations())->get();
        return $user;
    }

    public function save($params)
    {
        DB::beginTransaction();
        try {
            $data = $params->except('id', 'image', 'id_card_images', 'password', 'package_id', 'google_id', 'facebook_id', 'fcm_token')->toArray();
            if (!is_null($params->password)) {
                $data['password'] = bcrypt($params->password);
            }
            if (!is_null($params->image) || $params->user_type == 'user') {
                $data['image'] = $params->image;
            }
            if (isset($params->google_id) && !is_null($params->google_id)) {
                $data['google_id'] = $params->google_id;
            }
            if (isset($params->facebook_id) && !is_null($params->facebook_id)) {
                $data['facebook_id'] = $params->facebook_id;
            }
            if (!is_null($params->id_card_images)) {
                if ($params->id > 0) {
                    $user = $this->get($params->id);
                    if ($user->id_card_images != $params->id_card_images) {
                        $data['is_id_card_verified'] = false;
                    }
                }
                $data['id_card_images'] = $params->id_card_images;
            }
            $user = $this->getModel()->updateOrCreate(['id' => $params->id], $data);
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function sendEmailVerification($type = 'verification', $email = null)
    {
        try {
            if (is_null($email)) {
                $user = $this->getUser();
            } else {
                $user = $this->get(0, $email);
                if (is_null($user)) {
                    throw new Exception(__('Email does not exist'));
                }
            }
            $code = rand(1000, 9999);
            $data = collect([
                'receiver_name' => ($user->isSupplier() || $user->isCompany()) ? $user->supplier_name['en'] : $user->user_name,
                'receiver_email' => $user->email,
                'subject' => ($type == 'verification' ? __('Email verification code') : __('Password Reset Code')) . ' ' . $code,
                'view' => $type == 'verification' ? 'emails.user.email_verification' : 'emails.user.forgot_password',
                'sender_email' => config('settings.email'),
                'sender_name' => config('settings.company_name'),
                'code' => $code,
                'link' => $type == 'verification' ? route('front.auth.verification', ['code' => $code]) : route('front.auth.show.reset.form', ['code' => $code]),
            ]);

            $user->update(['verification_code' => $code]);
            $sendEmailDto = SendEmailDto::fromCollection($data);
            SendMail::dispatch($sendEmailDto);
            session(['code' => $code]);
            return $code;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function emailVerification($code)
    {
        $user = $this->getUser();
        if ($user->is_verified == '1') {
            throw new Exception(__('Email Already verified'));
        }

        if ($user->verification_code == $code) {
            $user->update(['verification_code' => '', 'is_verified' => 1]);
            if ($this->getFromWeb()) {
                $user->getFormattedModel(true, true);
                return $user;
            }
            $user->getFormattedModel(true, false);
            return $user;
        }
        throw new Exception(__('Verification code does not match'));
    }

    public function socialLogin($request, $fromWeb = false)
    {
        $token = NULL;
        $user = null;
        $customClaims = ['fcm_token' => $request->get('fcm_token', '')];
        $requestData = $request->only(['email', 'password', 'google_id', 'facebook_id']);

        if (!empty($requestData['email']) && !empty($requestData['password'])) {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return responseBuilder()->error(__("User doesn't exist"));
            }
            $token = $user->token ? $user->token : JWTAuth::fromUser($user);
        } else {
            // login with google / facebook
            $socialLoginColumn = 'google_id';
            if (!empty($requestData['facebook_id'])) {
                $socialLoginColumn = 'facebook_id';
            }

            // check if user exists
            $userExists = $this->getModel()->where([$socialLoginColumn => $requestData[$socialLoginColumn]])->get();

            if (count($userExists) > 0) {
                $user = $userExists->first();
                $token = $user->token ? $user->token : JWTAuth::fromUser($user);
            } else {
                // try to find with email
                $userExists = null;
                if (isset($requestData['email']) && $requestData['email'] !== '') {
                    $userExists = User::where(['email' => $requestData['email']])->first();
                }
                if (isset($userExists)) {
                    $userExists->$socialLoginColumn = $requestData[$socialLoginColumn];
                    $userExists->save();
                    $user = $userExists;
                    $token = $user->token ? $user->token : JWTAuth::fromUser($user);
                }
            }
        }

        if ($token) {
            if ($request->has('fcm_token') && $user->settings) {
                $user->update(['fcm_token' => $request->get('fcm_token')]);
                $tokenExists = $this->fcmRepository->get($user->id, $request->get('fcm_token'));
                if (is_null($tokenExists)) {
                    $this->fcmRepository->save($request->get('fcm_token'), $user->id, 0);
                }
            }
            $customClaims['user_id'] = $user->id;
            $userData = $this->userData($user, $token, $fromAdmin = false);
            return $userData->getFormattedModel(true)->toArray();
            //  return responseBuilder()->setAuthorization($token)->success(__('Login successful'), $userData->getFormattedModel(true)->toArray());
        } else {
            throw new Exception(__('Credentials does not match our records'));
            //  return responseBuilder()->error(__('Credentials does not match our records'));
        }
    }

    public function riders($id)
    {
        $query = $this->getModel()->query();
        $data = $query->where('user_type', 'rider')->where('company_id', $id)->select($this->getSelect())->with($this->getRelations())->latest()->paginate($this->getPaginate());
        return $data;
    }

    private function setWebData($userData)
    {
        session()->forget('USER_DATA');
        session()->put('USER_DATA', $userData);
        return $userData;
    }

    public function companyFilter()
    {

        $query = $this->getModel()->query();
        $query->where('is_active', 1);
        $query->where('is_verified', 1);
        $query->where('user_type', 'company');
        $data = $query->with($this->getRelations())->paginate($this->getPaginate());
        return $data;
    }


    public function storeFilter($storeId = null, $request = null)
    {
        $query = $this->getModel()->query();
        $query->where('is_id_card_verified', 1);
        $query->where('is_active', 1);
        $query->where('is_verified', 1);
        $query->where('user_type', 'supplier');
        $query->whereHas('storeSubscription', function ($q) {
            $q->where('is_expired', 0);
        });

        if (isset($request) && !is_null($request) && !empty($request)) {

            if ($request->has('latitude') && $request->latitude != '' && $request->has('longitude') && $request->longitude != '') {
                $lat = $request->get('latitude', 0);
                $long = $request->get('longitude', 0);
            }

            $lat = '';
            $long = '';

            $distance = config("settings.nearby_radius", 20); //km

            if ($request->has('user_location_data') && $request->user_location_data !== '') {
                $ss_data = $request->get('user_location_data');
                $query->whereHas('areas', function ($q) use ($ss_data) {
                    $q->where('cities.id', $ss_data['area_id']);
                });
            }

            if ($request->has('category_id') && $request->category_id !== '') {
                $checkCategories = function ($query) use ($request) {
                    $query->where('category_id', $request->category_id);
                };
                $query->whereHas('products', function ($q) use ($checkCategories) {
                    $q->whereHas('categories', $checkCategories);
                });
            }

            if ($request->has('keyword') && $request->keyword != '') {
                $query->where('supplier_name', 'like', '%' . $request->keyword . '%');
            }

            if ($request->has('address') && $request->address != '') {
                $query->where('address', 'like', '%' . $request->address . '%');
            }

            if ($request->has('supplier') && $request->supplier != '') {
                $query->where('user_type', $request->supplier);
            } else {
                $query->where('user_type', '!=', 'user');
            }

            if ($request->has('rating') && $request->rating != '') {
                $query->whereBetween('rating', [(int) $request->rating, (int) $request->rating + 0.99]);
            }

            if ($request->sort != "near_to_far" && $lat != "" && $long != "") {
                $haversine = '( 6367 * acos( cos( radians(' . $lat . ') )* cos( radians( latitude ) ) *cos( radians( longitude ) - radians(' . $long . ') ) + sin( radians(' . $lat . ') )* sin( radians( latitude ) ) ) )';
                $query->select('*')->selectRaw("{$haversine} AS distance")->whereRaw("{$haversine} < ?", [$distance]);
            }

            if ($request->has('category') && $request->category !== '') {
                $category = $request->category;
                $query->whereHas('products', function ($q) use ($category) {
                    $q->whereHas('categories', function ($query) use ($category) {
                        $query->where('category_id', $category);
                    });
                });
            }


            if (isset($request['area_id']) && $request['area_id'] !== '' && $request['area_id'] != null) {
                $ss_data = $request['area_id'];
                $query->whereHas('coveredAreas', function ($q) use ($ss_data) {
                    $q->where('area_id', $ss_data);
                });

            }


            if ($request->has('product_type') && $request->product_type !== '') {
                $product_type = $request->product_type;
                $query->whereHas('products', function ($q) use ($product_type) {
                    $q->where(['product_type' => $product_type, 'approval_status' => 'approved'])->wherehas('categories');
                });
            }

            if ($request->has('sort') && $request->sort != '') {

                if ($request->sort == "latest") {
                    $query->orderBy('created_at', 'desc');
                }

                if ($request->sort == "rating") {
                    $query->orderBy('rating', 'DESC');
                }
                if ($lat != "" && $long != "") {
                    if ($request->sort == "near_to_far") {
                        $haversine = '( 6367 * acos( cos( radians(' . $lat . ') )* cos( radians( latitude ) ) *cos( radians( longitude ) - radians(' . $long . ') ) + sin( radians(' . $lat . ') )* sin( radians( latitude ) ) ) )';
                        $query->selectRaw(" *,{$haversine} AS distance")->orderBy('distance');
                    }
                }
            }
        }

        $data = $query->with($this->getRelations())->paginate($this->getPaginate());
        return $data;
    }

    public function typeUsers($type = 'supplier', $keyword = null)
    {
        $query = $this->getModel();
        if ($type == 'supplier') {
            $query = $query->whereHas('deliveryAreas', function ($q) {
                $q->where('area_id', session('area_id'));
            });
        }

        $user = $query->where('user_type', $type)->select($this->getSelect())->with($this->getRelations());
        if (auth()->user()->isCompany()) {
            $user = $user->where('company_id', auth()->user()->id);
        }
        if (isset($keyword) && $keyword != NULL) {
            $user = $user->where('supplier_name', 'like', '%' . Str::lower($keyword) . '%');
        }

        $user = $user->paginate($this->getPaginate())->withQueryString();
        return $user;
    }


    public function userData($user, $token = null, $fromAdmin = false)
    {
        $userData = $user;

        if ($this->getFromWeb()) {
            if ($fromAdmin) {
                return $userData;
            } else {
                return $this->setWebData($userData);
            }
        }
        foreach ($userData as $key => $value) {
            if (is_null($value)) {
                if ((strpos($key, '_id') !== FALSE) && ($key != 'facebook_id' || $key != 'google_id')) {
                    $userData[$key] = '';
                } else {
                    $userData[$key] = '';
                }
            }
            if ($key == 'verification_code') {
                $userData[$key] = '';
            }

            if ($key == 'is_verified') {
                $userData[$key] = 1;
            }

            if ($key == 'latitude' || $key == 'longitude') {
                $userData[$key] = round($userData[$key], 2);
            }
        }
        $userData['token'] = $token;
        return $userData;
    }

    public function get($id = 0, $email = null)
    {
        $user = new User();
        if ($id > 0) {
            $user = $this->getModel()->select($this->getSelect())->with($this->getRelations())->find($id);
        }
        if (!is_null($email)) {
            $user = $this->getModel()->where('email', $email)->first();
        }
        if (!is_null($user)) {
            if (is_null($user->supplier_name)) {
                $user->supplier_name = ['en' => '', 'ar' => ''];
            }
            if (is_null($user->about)) {
                $user->about = ['en' => '', 'ar' => ''];
            }
        }
        return $user;
    }

    public function resetPassword($code, $email, $password)
    {
        $user = $this->getModel()->where([
            'verification_code' => $code,
            // 'email' => $email
        ])->first();
        if ($user !== null) {
            $user->update(['password' => bcrypt($password)]);
            return true;
        } else {
            throw new Exception(__('Invalid code'));
        }
    }

    public function adminDataTable($columns, $type = 'user')
    {
        DataTable::init(new User(), $columns);
        DataTable::where('user_type', '=', $type);
        $email = \request('datatable.query.email', '');
        $emailStatus = \request('datatable.query.emailStatus', '');
        $trashedItems = \request('datatable.query.trashedItems', NULL);
        $createdAt = \request('datatable.query.createdAt', '');
        $updatedAt = \request('datatable.query.updatedAt', '');
        $deletedAt = \request('datatable.query.deletedAt', '');
        $rating = \request('datatable.query.rating', '');
        $title = \request('datatable.query.name', '');
        $activeStatus = \request('datatable.query.activeStatus', '');
        $cityId = \request('datatable.query.city_id', '');


        if ($type == 'rider') {
            DataTable::with('deliveryCompany');
        }

        if ($cityId != "") {
            DataTable::where('city_id', '=', $cityId);
        }
        if ($emailStatus != "") {
            DataTable::where('is_verified', '=', $emailStatus);
        }
        if ($activeStatus != "") {
            DataTable::where('is_active', '=', $activeStatus);
        }
        if ($rating != "") {
            DataTable::orderBy('rating', $rating);
        }
        if (!empty($title)) {
            if ($type == 'supplier' || $type == 'company' || $type == 'rider') {
                DataTable::where('supplier_name->en', 'LIKE', '%' . $title . '%');
            }
            if ($type == 'user') {
                DataTable::where('user_name', 'LIKE', '%' . $title . '%');
            }
        }
        if (!empty($trashedItems)) {
            DataTable::getOnlyTrashed();
        }

        if ($email != '') {
            DataTable::where('email', 'like', '%' . addslashes($email) . '%');
        }

        if ($createdAt != '') {
            $createdAt = Carbon::createFromFormat('m/d/Y', $createdAt);
            $cBetween = [$createdAt->hour(0)->minute(0)->second(0)->timestamp, $createdAt->hour(23)->minute(59)->second(59)->timestamp];
            DataTable::whereBetween('created_at', $cBetween);
        }
        if ($updatedAt != '') {
            $updatedAt = Carbon::createFromFormat('m/d/Y', $updatedAt);
            $uBetween = [$updatedAt->hour(0)->minute(0)->second(0)->timestamp, $updatedAt->hour(23)->minute(59)->second(59)->timestamp];
            DataTable::whereBetween('updated_at', $uBetween);
        }

        if ($type == 'supplier' || $type == 'company' || $type == 'rider') {
            DataTable::whereHas('city');
            DataTable::with('city');
        }
        $user = DataTable::get();
        $dateFormat = config('settings.date-format');
        $start = 1;
        if ($user['meta']['start'] > 0 && $user['meta']['page'] > 1) {
            $start = $user['meta']['start'] + 1;
        }
        $count = $start;

        if (sizeof($user['data']) > 0) {
            foreach ($user['data'] as $key => $data) {
                $user['data'][$key]['id'] = $count++;
                if ($type == 'user') {
                    $user['data'][$key]['name'] = $data['user_name'];
                    $user['data'][$key]['actions'] = '<a href="' . route('admin.dashboard.users.edit', $data['id']) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-fw fa-edit"></i></a>' .
                        '<a class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete-record-button" href="javascript:{};" data-url="' . route('admin.dashboard.users.destroy', $data['id']) . '" title="Delete"><i class="fa fa-fw fa-trash-o"></i></a>';
                }
                if ($type == "supplier" || $type == "company") {
                    $user['data'][$key]['name'] = $data['supplier_name']['en'];
                    $user['data'][$key]['rating'] = getStarRating($data['rating']);
                    $user['data'][$key]['city'] = '';
                    if (!empty($data['city']['name'])) {
                        $user['data'][$key]['city'] = $data['city']['name']['en'];
                    }
                    $user['data'][$key]['is_id_card_verified'] = $data['is_id_card_verified'] ? 'Yes' : 'No';
                    if ($type == "supplier") {
                        $user['data'][$key]['actions'] = '<a href="' . route('admin.dashboard.suppliers.edit', $data['id']) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-fw fa-edit"></i></a>' .
                            '<a class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete-record-button" href="javascript:{};" data-url="' . route('admin.dashboard.suppliers.destroy', $data['id']) . '" title="Delete"><i class="fa fa-fw fa-trash-o"></i></a>'
                            . ($data['is_id_card_verified'] == 1 ? '' : '<a href="' . route('admin.dashboard.id.card.verify', $data['id']) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Verify trade license"><i class="fa fa-check-square"></i></a>');
                    } elseif ($type == "company") {
                        $user['data'][$key]['actions'] = '<a href="' . route('admin.dashboard.delivery-companies.edit', $data['id']) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-fw fa-edit"></i></a>' .
                            '<a class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete-record-button" href="javascript:{};" data-url="' . route('admin.dashboard.delivery-companies.destroy', $data['id']) . '" title="Delete"><i class="fa fa-fw fa-trash-o"></i></a>'
                            . ($data['is_id_card_verified'] == 1 ? '' : '<a href="' . route('admin.dashboard.id.card.verify', $data['id']) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Verify trade license"><i class="fa fa-check-square"></i></a>');
                    }
                }

                if ($type == 'rider') {
                    $user['data'][$key]['name'] = $data['supplier_name']['en'];
                    $user['data'][$key]['DeliveryCompanyName'] = $data['deliveryCompany']['supplier_name']['en'];

                    $user['data'][$key]['actions'] = '<a href="' . route('admin.dashboard.riders.edit', $data['id']) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-fw fa-edit"></i></a>';
                }
                if ($type == "delivery_company") {
                    $user['data'][$key]['name'] = $data['supplier_name']['en'];
                    $user['data'][$key]['actions'] = '<a href="' . route('admin.dashboard.delivery-companies.edit', $data['id']) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-fw fa-edit"></i></a>' .
                        '<a class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete-record-button" href="javascript:{};" data-url="' . route('admin.dashboard.delivery-companies.destroy', $data['id']) . '" title="Delete"><i class="fa fa-fw fa-trash-o"></i></a>';
                }

            }
        }

        return $user;
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = $this->get($id);
            if ($user->delete()) {
                $user->update(['email' => '']);
                if (!is_null($user->getOriginal('image'))) {
                    deleteImage($user->getOriginal('image'));
                }
                DB::commit();
                return true;
            } else {
                throw new Exception('Unable to delete user');
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function updatePaymentInfo($request, $fromAdmin = false, $id = 0)
    {
        $user = $this->getUser();
        $user->update([
            'client_id' => $request->client_id,
            'secret_id' => $request->secret_id,
        ]);
        return $user;
    }

    public function setUserSettings()
    {
        $user = $this->getUser();
        if ($user->settings) {
            $settings = 0;
        } else if (!$user->settings) {
            $settings = 1;
        }
        $user->update(['settings' => $settings]);
        return $settings;
    }

    public function changeUserPassword($params)
    {
        $user = $this->getUser();
        if (Hash::check($params->current_password, $user->password)) {
            $user->update(['password' => bcrypt($params->password)]);
            $user = $user->getFormattedModel(true, true);
            return $user;
        }
        throw new Exception(__('Incorrect current password'));
    }

    public function verifyIdCard($id)
    {
        $user = $this->get($id);
        $user->update(['is_id_card_verified' => 1]);
        return $user;
    }
}
