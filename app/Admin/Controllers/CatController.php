<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Grid;
use Encore\Admin\Form;

use App\Cat as Cat;

class CatController extends Controller
{
    use ModelForm;

    public function index()
    {
    	return Admin::content(function(Content $content){
    		$content->header('分类列表');
    		$content->body($this->grid());
    	});
    }

    private function grid()
    {
    	return Admin::grid(Cat::class, function(Grid $grid){
    		$grid->disableExport();
    		$grid->disableFilter();
    		$grid->id('ID');
    		$grid->name('名称');
    		$grid->a_count('文章数量');
    	});
    }

    public function create()
    {
    	return Admin::content(function(Content $content){
    		$content->header('添加分类');
    		$content->body($this->form());
    	});
    }

    private function form()
    {
    	return Admin::form(Cat::class, function(Form $form){
    		$form->text('name', '名称');
    	});
    }

    public function edit($id)
    {
    	return Admin::content(function(Content $content) use ($id) {
    		$content->body($this->form()->edit($id));
    	});
    }

    public function destroy($id)
    {
        $idArr = explode(',', $id);
        $cat = Cat::whereIn('id', $idArr)->get();
        $cat->each(function($item, $key){
                if($item['a_count'] == 0)
                {
                	Cat::where('id', $item['id'])->delete();
                }
            });
    }
}
