<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
class UsersController extends Controller
{
    //个人中心
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    //个人信息编辑页面
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * 个人信息编辑提交
     * @param UserRequest $request   表单请求验证
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->all());
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }
}