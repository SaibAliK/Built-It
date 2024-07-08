<?php

use App\Http\Libraries\Uploader;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use App\Http\Libraries\ResponseBuilder;
use Illuminate\Pagination\LengthAwarePaginator;

function imageUrl($path, $width = NULL, $height = NULL, $quality = NULL, $crop = NULL)
{
    if (!$width && !$height) {
        return $url = env('IMAGE_URL') . $path;
    } else {
        if (is_string($path)) {
            if (str_contains($path, url('/'))) {
                $path = str_replace(url('/'), '', $path);
            }
            $url = env('APP_URL') . '/images/timthumb.php?src=' . $path; // for IMAGE_LIVE_PATH
            if (isset($width)) {
                $url .= '&w=' . $width;
            }
            if (isset($height) && $height > 0) {
                $url .= '&h=' . $height;
            }
            if (isset($crop)) {
                $url .= "&zc=" . $crop;
            } else {
                $url .= "&zc=1";
            }
            if (isset($quality)) {
                $url .= '&q=' . $quality . '&s=0';
            } else {
                $url .= '&q=95&s=0';
            }
            return $url;
        }
    }
}

function translate(array $data)
{
    $locale = app()->getLocale();
    if (isset($data['en']) && isset($data['ar'])) {
        if ($locale == 'ar' && $data['ar'] == '') {
            return $data['en'];
        } else {
            return $data[$locale];
        }
    } else {
        return 'Error! data not correctly set';
    }
}

function convertDateFormat($format, $value)
{
    return date($format, $value);
}

function deleteImage($path = '')
{
    $fullPath = env('PUBLIC_PATH') . $path;
    if (File::exists($fullPath)) {
        unlink($fullPath);
    } else {
        return false;
    }
}

function getDiscountedAmount($price, $discount, $maxDiscount = 0)
{
    $discountedAmount = $price * ($discount / 100);
    if ($maxDiscount > 0 && $discountedAmount > $maxDiscount) {
        $discountedAmount = $maxDiscount;
    }
    return $discount = $price - $discountedAmount;
}

function responseBuilder()
{
    $responseBuilder = new ResponseBuilder();
    return $responseBuilder;
}

/**
 * @throws Exception
 */
function uploadImage($file, $path = 'media', $input = 'image')
{
    try {
        $imageUploadedPath = '';
        $uploader = new Uploader();
        $uploader->setFile($file);
        if ($uploader->isValidFile()) {
            $uploader->upload($path, $uploader->fileName);
            if ($uploader->isUploaded()) {
                $imageUploadedPath = $uploader->getUploadedPath();
            }
        }
        if (!$uploader->isUploaded()) {
            throw new Exception(__('Something went wrong'));
        }
        $data['file_name'] = $imageUploadedPath;
        $data['file_path'] = $imageUploadedPath;
        $data['type'] = $uploader->fileextension;

        return $data;
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}

function DateToUnixformat($date)
{
    $dateTime = new DateTime($date);
    $timestamp = $dateTime->format('U');
    return $timestamp;
}

function unixTODateformate($date)
{
    return Carbon::parse(gmdate("Y-m-d H:i:s", $date))->addHours(24)->format('Y-m-d');
}


function getPriceForObject($price, $currency = 'SAR')
{
    if ($price instanceof stdClass) {
        $amount = $price->{strtolower($currency)}->amount;
    } else {
        $amount = $price[strtolower($currency)]['amount'];
    }
    return $amount . ' ' . $currency;
}


function multiImageUpload(array $params = [], $path = 'media')
{
    $notImage = false;
    $imgData = [];

    $imgArray = null;
    $carOriginal = null;
    if (isset($data['images']) && count($data['images']) > 0) {
        $uploader = new Uploader();
        foreach ($data['images'] as $key => $img) {
            if (is_object($img)) {
                $uploader->setFile($img);
                if ($uploader->isValidFile()) {
                    $uploader->upload($path, $uploader->fileName);
                    if (!$uploader->isUploaded()) {
                        return responseBuilder()->error(__('Something went Wrong'));

                        $imgArray[$key]['f_name'] = $uploader->fileName;
                        $imgArray[$key]['db_path'] = $uploader->getUploadedPath();
                        $imgArray[$key]['url'] = url($uploader->getUploadedPath());
                    }
                }
            }
        }
    }

    return $imgArray;
}

function getPrice($price, $currency, $discount = 0)
{
    $price = getPriceObject($price);
    if ($price instanceof stdClass) {
        $amount = $price->{strtolower($currency)}->amount;
        $symbol = $price->{strtolower($currency)}->symbol;
    } else {
        $amount = $price[strtolower($currency)]['amount'];
        $symbol = $price[strtolower($currency)]['symbol'];
    }

    if ($discount > 0) {
        $discountedAmount = $amount * ($discount / 100);
        $discount = $amount - $discountedAmount;
        return $symbol . ' ' . $discount;
    } else {
        return $symbol . ' ' . $amount;
    }
}

function getPriceObject($productPrice)
{
    if ($productPrice instanceof stdClass) {
        return $productPrice;
    } else {
        $rate = getConversionRate();
        $price = new \stdClass();

        $price->aed = new \stdClass();
        $price->aed->amount = number_format(($productPrice), 2, '.', '') + 0;
        $price->aed->currency = 'AED';
        $price->aed->symbol = 'AED';
        $price->usd = new \stdClass();
        $price->usd->amount = number_format(($productPrice * $rate), 2, '.', '') + 0;
        $price->usd->currency = 'USD';
        $price->usd->symbol = '$';

        return $price;
    }

}


function paginate($items, $url, $perPage = 10, $removeKeys = false)
{
    $page = LengthAwarePaginator::resolveCurrentPage();
    $productCollection = collect($items);
    if ($removeKeys) {
        $currentPageproducts = $productCollection->slice(($page * $perPage) - $perPage, $perPage)->values()->all();
    } else {
        $currentPageproducts = $productCollection->slice(($page * $perPage) - $perPage, $perPage)->all();
    }
    $paginatedproducts = new LengthAwarePaginator($currentPageproducts, count($productCollection), $perPage, $page);
    $paginatedproducts->setPath($url);
    return $paginatedproducts;
}

function getOrderStatus($status)
{
    if (auth()->user()->isUser() || auth()->user()->isSupplier()) {
        if ($status == "pending"):
            $status = "Pending";
        elseif ($status == "cancelled"):
            $status = "Cancelled";
        elseif ($status == "confirmed"):
            $status = "Accepted";
        elseif ($status == "in-progress"):
            $status = "On its Way";
        elseif ($status == "shipped"):
            $status = "On its Way";
        elseif ($status == "completed"):
            $status = "Completed";
        endif;
    } elseif (auth()->user()->isCompany()) {
        if ($status == "cancelled"):
            $status = "Cancelled";
        elseif ($status == "confirmed"):
            $status = "Pending";
        elseif ($status == "shipped"):
            $status = "Accepted";
        elseif ($status == "delivered" || $status == "completed"):
            $status = "Delivered";
        endif;
    } elseif (auth()->user()->isRider()) {
        if ($status == "shipped"):
            $status = "Pending";
        elseif ($status == "delivered" || $status == "completed"):
            $status = "Delivered";
        endif;
    }

    return $status;
}

function sendNotification($data)
{

    $receiver = \App\Models\User::where(['id' => $data['receiver_id']])->first();

    if (!is_null($receiver)) {
        $fcmTokens = $receiver->fcms();
        if ($receiver && $receiver->settings == 1) {
            $notification = \App\Models\Notification::create($data);
            if (!is_null($fcmTokens)) {
                sendFCM([
                    'fcm_token' => $fcmTokens->fcm_token ?? '123456',
                    'title' => $notification->title['en'],
                    'body' => $notification->description['en'],
                    'data' => $notification,
                ]);
            }
            event(new \App\Events\NewNotifications($notification));
        }
    }
}


function sendFCM($data)
{
    if (!empty($data['fcm_token'])) {
        logger('=========FCM RESULT Data============', [$data]);
        $fields = array(
            'to' => $data['fcm_token'],
            'content_available' => true,
            'priority' => "high",
            'notification' => array('title' => $data['title'], 'body' => $data['body'], 'sound' => 'default'),
            'data' => $data['data'],
        );
        pushFCMNotification($fields);
    }
}

function pushFCMNotification($fields)
{
    $headers = array(
        env('FCM_URL'),
        'Content-Type: application/json',
        'Authorization: key=' . env('FCM_SERVER_KEY')
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, env('FCM_URL'));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    // dd($result);
    logger('=========FCM RESULT============', [$result]);
    curl_close($ch);
    if ($result === FALSE) {
        return 0;
    }
    $res = json_decode($result);

    logger('=========FCM============', [$res]);
    if (isset($res->success)) {
        return $res->success;
    } else {
        return 0;
    }
}


function unixConversion($duration_type, $duration, $created_at)
{
    $hours = 24;
    $minutes = 60;
    $sec = 60;
    if ($duration_type == "years") {
        $days = 365;
        $total_days = $duration * $days;
        $total_hours_in_days = $hours * $total_days;
        $total_hours_in_min = $minutes * $total_hours_in_days;
        $total_hours_in_sec = $sec * $total_hours_in_min;
        $unixTime = $total_hours_in_sec + $created_at;
    } elseif ($duration_type == "months") {
        $months_of_Days = 30;
        $total_days = $duration * $months_of_Days;
        $total_hours_in_days = $hours * $total_days;
        $total_hours_in_min = $minutes * $total_hours_in_days;
        $total_hours_in_sec = $sec * $total_hours_in_min;
        $unixTime = $total_hours_in_sec + $created_at;
    } elseif ($duration_type == "days") {
        $total_days = $duration;
        $total_hours_in_days = $hours * $total_days;
        $total_hours_in_min = $minutes * $total_hours_in_days;
        $total_hours_in_sec = $sec * $total_hours_in_min;
        $unixTime = $total_hours_in_sec + $created_at;
    }
    return $unixTime;
}

function getUsdPrice($price)
{
    $price = $price * getConversionRate();
    return number_format($price, 2, '.', '');

}

function getConversionRate()
{
    return config('settings.aed_to_usd', 0.27229408);
}


function getStarRating($value)
{
    $star_rate = $value;
    if ($value > 0 && $value < 0.5) {
        $star_rate = 0.5;
    }
    if ($value > 0.5 && $value < 1) {
        $star_rate = 1.0;
    }
    if ($value > 1 && $value < 1.5) {
        $star_rate = 1.5;
    }
    if ($value > 1.5 && $value < 2) {
        $star_rate = 2.0;
    }
    if ($value > 2 && $value < 2.5) {
        $star_rate = 2.5;
    }
    if ($value > 2.5 && $value < 3) {
        $star_rate = 3.0;
    }
    if ($value > 3 && $value < 3.5) {
        $star_rate = 3.5;
    }
    if ($value > 3.5 && $value < 4) {
        $star_rate = 4;
    }
    if ($value > 4 && $value < 4.5) {
        $star_rate = 4.5;
    }
    if ($value > 4.5 && $value < 5) {


        $star_rate = 5.0;
    }
    if ($value > 5) {
        $star_rate = 5.0;
    }

    return $star_rate;
}
