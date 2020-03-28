<?php

namespace App\Models;

use Cache;

class Link extends Model
{
    //
    protected $fillable=['title','link'];

    public  $cache_key='cache_link_key';
    protected $cache_expire_second=1440*60;
    public function getAllCached(){
        return Cache::remember($this->cache_key,$this->cache_expire_second,function (){
           return $this->all();
        });
    }
}
