<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * 页面控制器
 * Class PagesController
 * @package App\Http\Controllers
 */
class PagesController extends Controller
{
    //

    public function root()
    {
        return view('pages.root');
    }

}
