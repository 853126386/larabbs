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


    public function link($params = []){
        return route('topics.show',array_merge([$this->id,$this->slug],$params));
    }


    /**
     * 分类关联模型
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

    /**用户关联模型
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    /**
     * 回复关联模型
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

}
