<?php
namespace App\Admin\Extensions;
use \Encore\Admin\Form\Field;
class KEditor extends Field
{
    protected $view = 'admin.keditor';
    protected static $js = [
         '/keditor/kindeditor-all-min.js',
         '/Keditor/lang/zh-CN.js',
    ];
    public function render()
    {
    	 $this->script = "
    	 var options = {
			cssPath: '/editor/themes/simple/simple.css',
			filterMode: true,
			items: [
						'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
						'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 
						'insertorderedlist',
						'insertunorderedlist', '|', 'emoticons', 'image', '|', 'code', 'source', '|', 
						'baidumap'
					],
			resizeType: 0,
			width: '700px',
			height: '300px',
			allowImageRemote: false
		};
		KindEditor.ready(function(K){
			window.editor = K.create('#".$this->column."', options);
		});
    	 ";
        return parent::render();

    }

}