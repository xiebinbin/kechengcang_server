<?php

namespace App\Console\Commands;

use App\Models\Subject;
use App\Services\Admin\UploadService;
use App\Services\Base\ApplicationService;
use App\Services\Base\CategoryService;
use App\Services\MerchantService;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Orhanerday\OpenAi\OpenAi;
use Ufree\LaravelDogeCloud\DogeCloud;
use Vinkla\Hashids\Facades\Hashids;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:cmd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {

        $openAi = new OpenAi('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');
        $complete = $openAi->chat([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    "role" => "system",
                    "content" => "你是一个情感大师."
                ],
                [
                    "role" => "user",
                    "content" => "你好"
                ]
            ],
            'temperature' => 1.0,
            'max_tokens' => 4000,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
        ]);
        if(!empty($complete)){
            $rep = json_decode(rtrim($complete),true);

            dd($rep['choices'][0]['message']['content']);
        }
        dd("服务器响应失败，请重试!");
        return self::SUCCESS;
    }
}
