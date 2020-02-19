<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = [
        'title',
        'body',
        'category_id',
        'excerpt',
        'slug'
    ];

    /**
     * 本地作用域
     * @param $query
     * @param $order
     * @return mixed
     */
    public function scopeWithOrder($query,$order)
    {
        if($order=='recent'){
            $query->recent();
        }else{
            $query->recentReplied();
        }
        $query->with('user','category');
        return $query;
    }

    /**
     * 更新时间排序本地作用域
     * @param $query
     * @return mixed
     */
    public function scopeRecentReplied($query){
        return $query->orderBy('updated_at','desc');
    }

    /**
     * 创建时间排序本地作用域
     * @param $query
     * @return mixed
     */
    public function scopeRecent($query){
        return $query->orderBy('created_at','desc');
    }


    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
