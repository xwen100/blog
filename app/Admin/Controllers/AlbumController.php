<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Grid;
use Encore\Admin\Form;

use App\Album as Album;
use App\Image as Image;

class AlbumController extends Controller
{
    use ModelForm;

    public function index()
    {
    	return Admin::content(function(Content $content){
    		$content->header('相册列表');
    		$content->body($this->grid());
    	});
    }

    private function grid()
    {
        $that = $this;
    	return Admin::grid(Album::class, function(Grid $grid) use ($that){
    		$grid->disableExport();
    		$grid->disableFilter();
    		$grid->id('ID');
    		$grid->name('名称');
    		$grid->cover_url('封面')->value(function($v) use ($that){
                $filename = public_path().'/upload/'.$v;
                $id = Album::where('cover_url', $v)->value('id');
    			return '<img src="image/album/get/'.$id.'" class="img img-thumbnail" width="80" >';
    		});
    		$grid->image_num('照片数量');
    		$grid->created_at('创建时间');
            $grid->actions(function($actions){
                $actions->append('<a href="'.url('/admin/image',['id'=>$actions->getkey()]).'"><i class="fa fa-file-image-o"></i></a>');
            });
    	});
    }

    public function getAlbumImage($id)
    {

        $coverUrl = Album::where('id', $id)->value('cover_url');
        $filename = public_path().'/upload/'.$coverUrl;
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


    public function create()
    {
    	return Admin::content(function(Content $content){
    		$content->header('添加相册');
    		$content->body($this->form());
    	});
    }

    private function form()
    {
    	return Admin::form(Album::class, function(Form $form){
    		$form->text('name', '名称');
    		$form->image('cover_url', '封面');
            $userId = Admin::user()->id;
            $form->hidden('user_id')->value($userId);
    	});
    }

    public function edit($id)
    {
        return Admin::content(function(Content $content) use ($id){
            $content->header('添加相册');
            $content->body($this->form()->edit($id));
        });
    }

    public function destroy($id)
    {
        $rs = Image::where('album_id', $id)->get()->toArray();
        if(empty($rs))
        {
            $album = Album::find($id);
            $url = public_path() . '/upload/' . $album->cover_url;
            $album->delete();
            @unlink($url);
        }
        return redirect('/admin/album');
    }
}
