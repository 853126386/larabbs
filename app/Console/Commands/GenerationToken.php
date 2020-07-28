<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
class GenerationToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larabbs:generate-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '快速生成token';

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
        $userId=$this->ask('输入用户id');

        $user=User::find($userId);


        if(!$user){
            $this->error('用户不存在');
        }

        $ttl=365*3600*24;

        $this->info(\Auth::guard('api')->setTTl($ttl)->fromUser($user));

    }
}
