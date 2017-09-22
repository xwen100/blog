$(function(){
	getCommentList();
	$('#submitComment').click(submitComment);
	imageWidth = 0;
	$('.showImage').click(function(){
		showMask();
		var img = $($(this).children('img').get(0)).clone();
		img.attr('width', 'auto');
		img.attr('height', $(document).height());
		$('#imageWrap').append(img);
		imageWidth = img.width() == 0 ? imageWidth : img.width();
		$('#imageWrap').css('margin-left', -Math.round(imageWidth / 2));
		$('#imageWrap').show();
	});
	$(document).on('click', '#imageWrap img', function(){
		$('#imageWrap img').remove();
		$('#imageWrap').hide();
		hideMask();
	});
});

//显示遮罩层    
function showMask(){     
    $("#mask").css("height",$(document).height());     
    $("#mask").css("width",$(document).width());     
    $("#mask").show();
} 


//隐藏遮罩层  
function hideMask(){     
      
    $("#mask").hide();     
}  

function getCommentList(page = 1)
{
	$.ajax({
		url: '/comment/index',
		method: 'get',
		headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    	},
		data: {
			page: page,
			aid: $('#getId').text()
		},
		dataType: 'json',
		success: function(jsonData)
		{
			commentList(jsonData);
			displayPage(jsonData);
		}
	});
}


function commentList(jsonData)
{
	var list = '';

	$.each(jsonData.data, function(k, v){
		list += '<div class="row-wrap">' +
					'<div class="row row1">' +
						'<div class="col-md-1">' +
							'<img width="50" height="50" src="/images/img01.jpg" class="img-thumbnail">' +
						'</div>' +
						'<div class="col-md-11">' +
							v['content'] +
						'</div>' +
					'</div>' +
					'<div class="row row2">' +
						'<span>'+v['name']+'发布于：</span>' +
						'<span>'+v['created_at']+'</span>' +
					'</div>' +
				'</div>';
	});

	$('.comment-list').html(list);

}

function displayPage(jsonData)
{
	var html = '<ul class="pagination">';
	if(jsonData['current_page'] == 1)
	{
		html += '<li class="disabled">' +
		        '<span aria-hidden="true">&laquo;</span>' +
		     '</li>';
	}else{

	    html += '<li>' +
			      '<a href="javascript:void(0);" aria-label="Previous">' +
			        '<span aria-hidden="true">&laquo;</span>' +
			      '</a>' +
			    '</li>';
	}
	for(var i=1; i <= jsonData['last_page']; i++)
	{
		if(jsonData['current_page'] == i){
    		html += '<li class="active"><a href="javascript:void(0);" onclick="getCommentList('+i+')">'+i+'</a></li>';

		}else{
    		html += '<li><a href="javascript:void(0);" onclick="getCommentList('+i+')">'+i+'</a></li>';

		}
	}
	if(jsonData['current_page'] == jsonData['last_page'])
	{
		html += '<li class="disabled">' +
			        '<span aria-hidden="true">&raquo;</span>' +
			    '</li>';
	}else{
		html += '<li>'+
			      '<a href="javascript:void(0);" aria-label="Next">' +
			        '<span aria-hidden="true">&raquo;</span>' +
			      '</a>' +
			    '</li>';
	}
	html += '</ul>';
	$('#page').html(html);
}

function submitComment()
{
	var content = $('#content').val();
	$.ajax({
		url: '/comment/save',
		method: 'post',
		headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    	},
		data: {
			content: content,
			aid: $('#getId').text()
		},
		beforeSend: function()
		{
			$('#submitComment').attr('disabled', 'disabled');
		},
		dataType: 'json',
		success: function(jsonData)
		{
			if(typeof(jsonData.error_code) == 'undefined')
			{
				displayCommentList(jsonData);
			}
		},
		complete: function()
		{
			$('#content').val('');
			$('#submitComment').removeAttr('disabled');
		}
	});
}

function displayCommentList(jsonData)
{
	var str = '<div class="row-wrap">' +
					'<div class="row row1">' +
						'<div class="col-md-1">' +
							'<img width="50" height="50" src="/images/img01.jpg" class="img-thumbnail">' +
						'</div>' +
						'<div class="col-md-11">' +
							jsonData.content +
						'</div>' +
					'</div>' +
					'<div class="row row2">' +
						'<span>'+jsonData.name+'发布于：</span>' +
						'<span>'+jsonData.createtime+'</span>' +
					'</div>' +
				'</div>';
	$('.comment-list').prepend(str);
}