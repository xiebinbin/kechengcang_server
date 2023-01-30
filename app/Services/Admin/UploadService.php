<?php

namespace App\Services\Admin;

use App\Enums\Fields\ApplicationStatus;
use App\Enums\Fields\CallbackStatus;
use App\Enums\Fields\CheckStatus;
use App\Models\Application;
use App\Services\Admin\AdminUserService;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

class UploadService
{
    /**
     * @param UploadedFile $file
     * @return string
     * @throws Exception
     */
    #[ArrayShape(['url' => "false|string"])]
    public static function store(UploadedFile $file): string
    {
        self::refreshDogeCloudToken();
        return $file->store('tmp/' . now()->format('Y/m/d'), ['disk' => 'doge']);
    }

    /**
     * @throws Exception
     */
    public static function refreshDogeCloudToken(): void
    {
        $token = Cache::get('doge.s3.token');
        if (empty($token)) {
            $api = UploadService::dogeCloudApi('/auth/tmp_token.json', array(
                "channel" => "OSS_FULL",
                "scopes" => array("*")
            ), true);
            if ($api && $api['code'] == 200) {
                $credentials = $api['data']['Credentials'];
                Cache::put('doge.s3.token', $credentials['sessionToken'], now()->addHours(2));
                Cache::put('doge.s3.key', $credentials['accessKeyId'], now()->addHours(2));
                Cache::put('doge.s3.secret', $credentials['secretAccessKey'], now()->addHours(2));
            } else {
                // 失败
                throw new Exception('doge token error');
            }
        }
        $token = Cache::get('doge.s3.token');
        $key = strval(Cache::get('doge.s3.key'));
        $secret = strval(Cache::get('doge.s3.secret'));
        Config::set('filesystems.disks.doge.token', $token);
        Config::set('filesystems.disks.doge.key', $key);
        Config::set('filesystems.disks.doge.secret', $secret);

    }

    public static function dogeCloudApi($apiPath, $data = array(), $jsonMode = false)
    {
        $accessKey = env('DOGE_ACCESS_KEY_ID');
        $secretKey = env('DOGE_SECRET_ACCESS_KEY');

        $body = $jsonMode ? json_encode($data) : http_build_query($data);
        $signStr = $apiPath . "\n" . $body;
        $sign = hash_hmac('sha1', $signStr, $secretKey);
        $Authorization = "TOKEN " . $accessKey . ":" . $sign;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.dogecloud.com" . $apiPath);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); // 如果是本地调试，或者根本不在乎中间人攻击，可以把这里的 1 和 2 修改为 0，就可以避免报错
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 建议实际使用环境下 cURL 还是配置好本地证书
        if (isset($data) && $data) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: ' . ($jsonMode ? 'application/json' : 'application/x-www-form-urlencoded'),
                'Authorization: ' . $Authorization
            ));
        }
        $ret = curl_exec($ch);
        curl_close($ch);
        return json_decode($ret, true);
    }
}
