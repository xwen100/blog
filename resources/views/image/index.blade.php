@extends('layouts.image')

@section('content')

<div class="image-wrap">
	<h2>相册</h2>
	<hr>
	<div class="row">
	@foreach($list as $k => $v)
	  <div class="col-xs-6 col-md-3">
	    <a href="/image/index/{{$v['id']}}" class="thumbnail">
	      <img src="/image/album/get/{{$v['id']}}" width="300">
	    </a>
	    <div class="caption">
	      <h3>{{$v['name']}}</h3>
	      <p>{{$v['image_num']}}张</p>
	    </div>
	  </div>
	@endforeach
	  </div>

	</div>
</div>

@endsection
