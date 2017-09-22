<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Comment;

use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    //
    public function save()
    {
    	$param = request()->all();
    	if(!array_key_exists('content', $param))
    	{
    		return response()->json(['error_code'=>1001, 'error_msg'=>'参数有误']);
    	}
    	$comment = new Comment();
    	$comment->content = $param['content'];
    	$comment->aid = $param['aid'];
    	if(Auth::id()){
    		$comment->user_id = Auth::id();
    	}
    	$comment->save();
    	$data = [];
    	$data['id'] = $comment->id;
    	$data['content'] = $comment->content;
    	$data['name'] = Auth::user()->name;
    	$data['createtime'] = date('Y-m-d',strtotime($comment->created_at));
    	return response()->json($data);
    }

    public function index()
    {
    	$param = request()->all();
        $userId = Auth::id();
        $list = Comment::from('comment as c')
        			->leftJoin('users as u', 'c.user_id', '=', 'u.id')
        			->select(['c.*', 'u.name'])
        			->where('aid', $param['aid'])
                    ->orderBy('id', 'desc')
                    ->paginate(5)->toArray();
        
        foreach($list['data'] as $k => $v)
        {
            $list['data'][$k]['created_at'] = date('Y-m-d', strtotime($v['created_at']));
        }
    	return response()->json($list);
    }
}
