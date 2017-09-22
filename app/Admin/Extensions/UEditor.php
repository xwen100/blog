<?php

namespace App\Admin\Extensions;

use \Encore\Admin\Form\Field;

class UEditor extends Field
{
    protected $view = 'admin.ueditor';

    protected static $js = [
         '/editor/ueditor.config.js',
         '/editor/ueditor.all.min.js',
    ];

    protected static $css = [
    	'/editor/diy.css',
    ];

    public function render()
    {
        $this->script = '
        UE.delEditor("'.$this->column.'");
            var ue = UE.getEditor("'.$this->column.'", {
                zIndex: 0,
                elementPathEnabled: false,
                initialFrameWidth: 700,
                wordCount:false,
                toolbars: [
                    [
                        "undo", //撤销
                        "bold", //加粗
                        "italic", //斜体
                        "underline", //下划线
                        "strikethrough", //删除线
                        "pasteplain", //纯文本粘贴模式
                        "horizontal", //分隔线
                        "fontfamily", //字体
                        "fontsize", //字号
                        "paragraph", //段落格式
                        "simpleupload", //单图上传
                        "insertimage", //多图上传
                        "link", //超链接
                        "emotion", //表情
                        "justifyleft", //居左对齐
                        "justifyright", //居右对齐
                        "justifycenter", //居中对齐
                        "justifyjustify", //两端对齐
                        "forecolor", //字体颜色
                        "backcolor", //背景色
                        "insertorderedlist", //有序列表
                        "insertunorderedlist", //无序列表
                        // "attachment", //附件
                        "lineheight", //行间距
                        "edittip ", //编辑提示
                    ]
                ]
            });

        $(function() {
            $("#btnConsult").on("click", function () {
                var imgExists = $(".consult-card").length;
                if (imgExists == 0) return false;
                ue.execCommand("insertparagraph");
                ue.execCommand("insertimage", {
                    src: $(".consult-card").attr("src"),
                    title : ("aid-" + $(".consult-card").attr("data-aid"))
                });
                ue.execCommand("insertparagraph");
                $("#consultModal").modal("hide");
            })
        });';

        $this->style = "";

        return parent::render();
    }
}
