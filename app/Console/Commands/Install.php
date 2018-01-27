<?php

namespace App\Console\Commands;

use App\Models\User;
use Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Webpatser\Uuid\Uuid;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初始化安装';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('开始数据库迁移');
        Artisan::call('migrate');
        $this->info('Done');

        $this->info('开始初始化权限管理系统');
        $this->initRolesAndPermissions();
        $this->info('Done');

        Artisan::call('key:generate');
        Artisan::call('jwt:secret');

        $this->info('开始填充网站设置');
        $this->initSettings();
        $this->info('Done');

        $this->info('创建默认管理员账号');
        $password = $this->randomPassword();
        $this->createAdminAccount($password);
        $this->info('Done');

        $this->line('----------------------------------------------------------');
        $this->line("安装完毕！");
        $this->line("管理员账号： admin@local.com");
        $this->line("管理员密码： {$password}");


    }

    public function initRolesAndPermissions()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $adminPermissions = ['manage users', 'manage orders', 'manage website settings', 'manage products'];
        foreach ($adminPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        $adminRole->givePermissionTo($adminPermissions);
    }

    public function initSettings()
    {

    }

    public function createAdminAccount($password = '123456')
    {
        $admin = User::create(['name' => 'admin',
            'email' => 'admin@local.com',
            'password' => bcrypt($password)]);

        $admin->assignRole('admin');

    }

    public function randomPassword(){
        return substr(bcrypt(time()),7,15 );
    }

}
