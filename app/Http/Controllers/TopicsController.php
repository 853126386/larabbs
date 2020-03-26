<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\Category;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use Auth;
/**
 * 帖子控制器
 * Class TopicsController
 * @package App\Http\Controllers
 */
class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * 帖子列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function index(Request $request,User $user)
	{

//		$topics = Topic::with('user','category')->paginate(10);//使用预加载找出列表数据
		$topics = Topic::withOrder($request->order)->paginate(10);//使用预加载找出列表数据
        $active_users=$user->getActiveUsers();
//        dd($active_users);
		return view('topics.index', compact('topics','active_users'));
	}

    /**
     * 帖子详情
     * @param Topic $topic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Topic $topic,Request $request)
    {
        if($topic->slug&&$topic->slug!=$request->slug){
            return redirect($topic->link(),301);
        }
        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{
	    $categories=Category::all();
		return view('topics.create_and_edit', compact('topic','categories'));
	}

	public function store(TopicRequest $request,Topic $topic)
	{
        $topic->fill($request->all());
        $topic->user_id=Auth::id();
		$topic->save();
		return redirect()->to($topic->link())->with('success', '帖子创建成功！');
	}

	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
        $categories=Category::all();
		return view('topics.create_and_edit', compact('topic','categories'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->to($topic->link())->with('success', '修改成功');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('success', '删除成功');
	}


    public function uploadImage(Request $request,ImageUploadHandler $imageUploadHandler)
    {
        $data=[
            'success'=>false,
            'msg'=>'上传失败',
            'file_path'=>''
        ];
        if($file=$request->upload_file){
            $result=$imageUploadHandler->save($request->upload_file,'topics',Auth::id(),1024);
            if($result){
                $data=[
                    'success'=>true,
                    'msg'=>'上传成功',
                    'file_path'=>$result['path']
                ];
            }

        }
        return $data;
	}
}
