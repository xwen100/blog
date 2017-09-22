<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Grid;
use Encore\Admin\Form;

use Encore\Admin\Widgets\Box;
use Encore\Admin\Layout\Row;

use App\Image as Image;
use App\Album as Album;


class ImageController extends Controller
{
    use ModelForm;

    public function index($id)
    {
    	return Admin::content(function(Content $content) use ($id){
    		$content->header('照片列表');
    		$content->row('<a href="/admin/image/'.$id.'/create" class="btn btn-primary" style="margin-bottom:10px; float: right;">添加照片</a>');
            $userId = Admin::user()->id;
            $imageList = Image::where('album_id', $id)
                            ->where('uploader_id', $userId)
                            ->orderBy('id', 'desc')
                            ->get()->toArray();
    		$content->row(function(Row $row) use ($id, $imageList) {
                foreach($imageList as $k => $v)
                {
                    $show = $v['show'] == 1 ? '显示' : '不显示';
                    $content = '<p><img src="/image/get/'.$v['id'].'" width="300"></p>';
                    $content .= '<p>'. $show .'</p>';
                    $content .= '<p style="text-align:right;"><a style="padding-right:10px;" href="/admin/image/'.$id.'/edit/'.$v['id'].'"><i class="fa fa-edit"></i></a><a style="padding-right:10px;" href="/admin/image/'.$id.'/destory/'.$v['id'].'"><i class="fa fa-trash"></i></a></p>';
                    $box = new Box($v['name'], $content);

                    $row->column(4, $box);
                }
    		});
    	});	
    }

    public function getImage($id)
    {

        $url = Image::where('id', $id)->value('url');
        $filename = public_path().$url;
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

    public function create($albumId)
    {
        $albumData = $this->getAlbumData($albumId);
    	return Admin::content(function(Content $content) use ($albumData) {
    		$content->header('添加照片');
    		$content->body($this->form($albumData));
    	});
    }

    private function form($albumData)
    {
    	return Admin::form(Image::class, function(Form $form) use ($albumData){
    		$form->setAction('/admin/image/save');
    		$form->hidden('album_id')->value($albumData['id']);
    		$form->html('<span style="padding-top:5px;display:inline-block;">'.$albumData['name'].'</span>', '所属相册');
    		$form->multipleImage('url', '上传照片')->uniqueName();
            $states = [
                        'on'=>['value'=>'1', 'text'=>'显示', 'color'=>'success'],
                        'off'=>['value'=>'0', 'text'=>'不显示', 'color'=>'danger']
                      ];
            $form->switch('show', '是否显示')->states($states)->default('1');
    	});
    }

    public function save()
    {
    	$param = request()->all();
    	$albumId = $param['album_id'];
        $show = $param['show'] == 'on' ? '1' : '0'; 
        $root = public_path('upload');
    	$path = '/image/'.$albumId;
		 if(!is_dir($root . $path))
		 {
		 	mkdir($root . $path, 0777);
		 }
		 foreach($_FILES['url']['tmp_name'] as $k => $v)
		 {
            $name = md5(uniqid()) . '.jpg';
		 	$filename = $root . $path . '/' . $name;
            $userId = Admin::user()->id;
		 	move_uploaded_file($v, $filename);
            $image = new Image();
            $image->url = '/upload'. $path . '/' . $name;
            $image->name = $name;
            $image->album_id = $albumId;
            $image->uploader_id = $userId;
            $image->show = $show;
            $image->save();
            $album = Album::find($albumId);
            $album->image_num = $album->image_num + 1;
            $album->save();
		 }
         return redirect('/admin/image/'.$albumId);
    }

    private function getAlbumData($albumId)
    {
    	$data = Album::where('id', $albumId)->first()->toArray();
    	return $data;
    }

    private function grid()
    {
    	return Admin::grid(Image::class, function(Grid $grid){

    	});
    }

    public function edit($albumId, $id)
    {
        $albumData = $this->getAlbumData($albumId);
        return Admin::content(function(Content $content) use ($albumData, $id){
            $content->header('添加照片');
            $content->body($this->editForm($id, $albumData));
        });
    }

    private function editForm($id, $albumData)
    {
        return Admin::form(Image::class, function(Form $form) use ($id, $albumData){
            $form->setAction('/admin/image/update/'.$id);
            $form->html('<span style="padding-top:5px;display:inline-block;">'.$albumData['name'].'</span>', '所属相册');
            $form->hidden('album_id')->value($albumData['id']);
            $imageData = Image::where('id', $id)->first()->toArray();
            $htmlStr = '<img src="/image/get/'.$id.'" width="300">';
            $form->html($htmlStr, '照片');
            $states = [
                        'on'=>['value'=>'1', 'text'=>'显示', 'color'=>'success'],
                        'off'=>['value'=>'0', 'text'=>'不显示', 'color'=>'danger']
                      ];
            $form->switch('show', '是否显示')->states($states)->default($imageData['show']);
            $form->display('name', '名称')->value($imageData['name']);
        });
    }

    public function update($id)
    {
        $param = request()->all();
        $show = $param['show'] == 'on' ? '1' : '0';
        $image = Image::find($id);
        $image->show = $show;
        $image->save();
        return redirect('/admin/image/'.$param['album_id']);
    }

    public function destory($albumId, $id)
    {
        $image = Image::find($id);
        if($image)
        {
            $url = public_path() . $image->url;
            $image->delete();
            unlink($url);
            $album = Album::find($albumId);
            $album->image_num = $album->image_num - 1;
            $album->save();
        }
        return redirect('/admin/image/'.$albumId);
    }
}
