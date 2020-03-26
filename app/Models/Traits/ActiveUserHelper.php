<?php
namespace App\Models\Traits;

use App\Models\Topic;
use App\Models\Reply;
use Carbon\Carbon;
use Cache;
use DB;
use Arr;
trait ActiveUserHelper {

    //用于存放临时数据
    protected $users=[];

    //配置信息
    protected $topic_weight=4; //话题权重
    protected $reply_weight=1; //回复权重
    protected $pass_day=7;     //多少天发表过内容
    protected $user_number=6;  //取出多少用户
    //缓存相关配置
    protected $cache_key='larabbs_active_key';
    protected $cache_expire_in_seconds=65*60;


    public function getActiveUsers()
    {
        return Cache::remember($this->cache_key,$this->cache_expire_in_seconds,function (){
            $this->caclulateActiveUsers();
        });

    }

    public function calculateAndCacheActiveUsers(){

        $active_user=$this->caclulateActiveUsers();
        $this->cacheActiveUsers($active_user);
    }

    private function caclulateActiveUsers()
    {
        $this->cacluteTopicScore();
        $this->cacluteReplyScore();

        //数组按照得分排序
        $users=Arr::sort($this->users,function($user){
            return $user['score'];
        });

        $users=array_slice($this->users,0,$this->user_number,true);

        //新建空集合
        $active_users=collect();
        foreach ($users as $user_id => $user){
            //找寻是否可以找到用户
            $user=$this->find($user_id);

            //如果数据库有该用户的话
            if($user){
                //将该用户放入集合的末尾
                $active_users->push($user);
            }
        }
        return $active_users;
    }


    private function calculateTopicScore(){

        //从话题数据表里去除限定时间范围（$pass_day）内，有发表过话题的用户
        //并且同事取出用户此段时间内发布话题的数量

        $topic_users=Topic::query()->select(DB::raw('user_id,count(*) as topic_count'))
                                   ->where('create_at','>=',Carbon::now()->subDays($this->pass_day))
                                   ->groupBy('user_id')
                                   ->get();

        //根据话题数量计算得分
        foreach ($topic_users as $value){
            $this->users[$value->user_id]['score']=$value->topic_count*$this->topic_weight;
        }
    }

    private function calculateReplayScore(){

        // 从回复数据表里取出限定时间范围（$pass_days）内，有发表过回复的用户
        // 并且同时取出用户此段时间内发布回复的数量
        $reply_users = Reply::query()->select(DB::raw('user_id, count(*) as reply_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();

        //根据回复计算得分
        foreach ($reply_users as $value){
            $reply_score=$value->reply_count*$this->reply_weight;
            if(isset($this->users[$value->user_id])){
                $this->users[$value->user_id]['score']+=$reply_score;
            }else{
                $this->users[$value->user_id]['score']=$reply_score;
            }
        }
    }


    private function cacheActiveUsers($actice_users){
        //将数据放入缓存
        Cache::put($this->cache_key,$actice_users,$this->cache_expire_in_seconds);
    }
}


