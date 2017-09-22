<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Album as Album;
use App\Image as Image;

use App\User as User;

use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
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

    public function index()
    {
        $adminId = $this->getUserAdminId();
        $list = Album::where('user_id', $adminId)->get()->toArray();
    	return view('image/index', ['list'=>$list]);
    	
    }

    public function getImageList($albumId)
    {
        $adminId = $this->getUserAdminId();
        $albumData = Album::where('user_id', $adminId)->find($albumId)->toArray();
        $imageList = Image::where('show', '1')
                            ->where('uploader_id', $adminId)
                            ->where('album_id', $albumId)->get()->toArray();
        return view('image/getImageList', ['albumData'=>$albumData, 'imageList'=>$imageList]);
    }

    public function getAlbumImage($id)
    {
        $adminId = $this->getUserAdminId();
        $data = Album::where('id', $id)
                    ->where('user_id', $adminId)
                    ->first()->toArray();
        $filename = public_path().'/upload/'.$data['cover_url'];
        $this->readImage($filename);
    }

    public function getImage($id)
    {
        $adminId = $this->getUserAdminId();
        $data = Image::where('id', $id)
                    ->where('uploader_id', $adminId)
                    ->first()->toArray();
        $filename = public_path().$data['url'];
        $this->readImage($filename);
    }

    private function readImage($filename)
    {
        $fh = fopen($filename, 'r');
        $content = fread($fh, filesize($filename));
        $info = getimagesize($filename);
        $mime = $info['mime'];
        header('content-type:'.$mime);
        echo $content;
    }

}
