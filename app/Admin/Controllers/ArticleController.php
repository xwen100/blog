<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

use App\Article as Article;
use App\Cat as Cat;

use DB;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Grid;
use Encore\Admin\Form;

class ArticleController extends Controller
{
    use ModelForm;

    public function index()
    {
		return Admin::content(function (Content $content) {

            $content->header('文章列表');

            $content->body($this->grid());
        });
    }

    protected function grid()
    {
    	return Admin::grid(Article::class, function(Grid $grid){
    		$grid->disableExport();
    		$grid->disableFilter();
			$grid->id('ID');
			$grid->title('标题');
            $grid->cat_id('分类')->display(function($cat_id){
                return Cat::find($cat_id)->name;
            });
			$grid->public('是否公开')->value(function($v){
				switch ($v) {
					case 0:
						return '否';
					case 1:
						return '是';
				}
			});
			$grid->created_at('发布时间');
    		$grid->updated_at('更新时间');
		});
    }

    public function create()
    {
    	return Admin::content(function (Content $content) {

            $content->header('添加文章');

            $content->body($this->form());
        });
    }

    public function edit($id)
    {
    	return Admin::content(function (Content $content) use ($id) {

            $content->header('编辑文章');

            $content->body($this->form()->edit($id));
        });
    }

    protected function form()
    {
    	return Admin::form(Article::class, function(Form $form){
    		$form->text('title', '标题');
            $form->textarea('des', '简介');
    		$cats = Cat::all();
    		$form->select('cat_id', '分类')->options(
				array_combine(
					$cats->pluck('id')->toArray(), 
					$cats->pluck('name')->toArray())
			);
            $states = [
                'on'  => ['value' => 1, 'text' => '打开', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '关闭', 'color' => 'danger'],
            ];
    		$form->switch('public', '是否公开')->states($states);
    		$form->keditor('content', "内容");
    		$form->hidden('user_id')->value(Admin::user()->id);
            $form->saving(function(Form $form){
                if(request()->method() == 'PUT')
                {
                    $urlArr = explode('/', request()->url());
                    $id = $urlArr[count($urlArr)-1];
                    $art = Article::find($id);
                    $cat = Cat::find($art->cat_id);
                    $cat->a_count = $cat->a_count - 1; 
                    $cat->save();
                }
            });
            $form->saved(function(Form $form){
                $cat = Cat::find($form->cat_id);
                $cat->a_count = $cat->a_count + 1;
                $cat->save();
            });
    	});
    }

    public function destroy($id)
    {
       $idArr = explode(',', $id);
       DB::transaction(function() use ($idArr) {
            $art = Article::whereIn('id', $idArr)->get();
            Article::whereIn('id', $idArr)->delete();
            $art->each(function($item, $key){
                $cat = Cat::find($item['cat_id']);
                $cat->a_count = $cat->a_count - 1; 
                $cat->save();
            });
            
       }); 
    }

}
