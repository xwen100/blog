@extends('layouts.parent')

@section('right')
<div class="articl-wrap">
    <h2>{{$data['title']}}<span id="getId">{{$data['id']}}<span></h2>
    <p class="p1">作者： {{$data['username']}} • {{$data['created_at']}} </p>
    <div class="d1">
    	{!!$data['content']!!}
    </div>
    <hr>
    <p class="p3">
        <span class="glyphicon glyphicon-tag"></span>{{$data['name']}}
    </p>
</div>
<div class="comment-wrap">
	<h3>评论</h3>
	<div class="row">
		<div class="col-md-1">
			<img width="50" height="50" src="/images/img01.jpg" class="img-thumbnail">
		</div>
		<div class="col-md-11">
			<div class="row">
				<textarea id="content" class="form-control" rows="5"></textarea>
			</div>
			<div class="row btn-wrap">
				<button class="btn btn-default" id="submitComment">发布</button>
			</div>
		</div>
	</div>
	<div class="comment-list">

		

	</div>
	<nav aria-label="Page navigation" id="page">
	</nav>
</div>
@endsection