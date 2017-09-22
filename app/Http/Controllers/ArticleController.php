<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Article as Article;
use App\Cat as Cat;
use App\User as User;

use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function getUserAdminId()
    {

        $user = User::find(Auth::id())->toArray();
        return $user['admin_id'];
    }

    public function getCat()
    {

        $adminId = $this->getUserAdminId();
    	$catList = Cat::where('user_id', $adminId)->get()->toArray();
    	return $catList;
    }

    public function read($id)
    {
    	$catList = $this->getCat();
        $adminId = $this->getUserAdminId();
    	$data = Article::from('article as a')
				->leftJoin('cat as c', 'a.cat_id', '=', 'c.id')
				->leftJoin('admin_users as u', 'a.user_id', '=', 'u.id')
                ->where('a.id', $id)
				->where('a.user_id', $adminId)
				->first(['a.title', 'a.id', 'a.content', 'u.username', 'a.created_at', 'a.des', 'c.name'])->toArray();
		$data['created_at'] = date('Y-m-d', strtotime($data['created_at']));

    	return view('article/read', ['data'=>$data, 'catList'=>$catList]);
    }

    public function index()
    {

    	$catList = $this->getCat();

    	$param = request()->all();

    	$catId = array_key_exists('cat', $param) ? $param['cat'] : 0;

    	$where = [];
    	if($catId > 0)
    	{
    		$where['cat_id'] = $catId;
    	}

        $adminId = $this->getUserAdminId();
        $where['a.user_id'] = $adminId;

    	$list = Article::from('article as a')
                ->leftJoin('cat as c', 'a.cat_id', '=', 'c.id')
				->leftJoin('admin_users as u', 'a.user_id', '=', 'u.id')
                ->select(['a.title', 'a.id', 'u.username', 'a.created_at', 'a.des', 'c.name'])
				->where($where)
                ->orderBy('id', 'desc')
                ->paginate(5)->toArray();
        foreach($list['data'] as $k => $v)
        {
            $list['data'][$k]['created_at'] = date('Y-m-d', strtotime($v['created_at']));
        }
    	return view('article/index',['list'=>$list, 'catList'=>$catList]);
    }
}
