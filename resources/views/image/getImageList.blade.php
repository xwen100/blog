@extends('layouts.image')

@section('content')

<div class="image-wrap">
	<h2>{{$albumData['name']}}</h2>
	<hr>
	<div class="row" id="downbok">
	@foreach($imageList as $k => $v)
	  <div class="col-xs-6 col-md-3">
	    <a href="javascript:;" class="thumbnail showImage">
	      <img src="/image/get/{{$v['id']}}" width="300">
	    </a>
	    <div class="caption">
	      
	    </div>
	  </div>
	@endforeach
	  </div>

	</div>
</div>

@endsection
