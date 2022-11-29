<?php

namespace App\Console\Commands;

use App\Enums\Fields\Role;
use App\Services\Admin\AdminUserService;
use Exception;
use Illuminate\Console\Command;

class CreateAdminUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-admin-user';

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
     * @throws Exception
     */
    public function handle(): int
    {
        $name = $this->ask('账户名:');
        $password = $this->ask('输入密码:');
        if (!empty($name) && !empty($password)) {
            AdminUserService::create(['name' => $name, 'role' => Role::SUPER, 'password' => $password]);
            $this->info('创建成功!');
        }
        return self::SUCCESS;
    }
}
