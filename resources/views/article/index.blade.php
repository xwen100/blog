@extends('layouts.parent')

@section('right')

@foreach($list['data'] as $k => $v)
<div class="articl-wrap">
    <h2>{{$v['title']}}</h2>
    <p class="p1">作者： {{$v['username']}} • {{$v['created_at']}} </p>
    <div class="d1">{{$v['des']}}
    </div>
    <p class="p2">
        <a href="{{url('article/read',$v['id'])}}" class="btn btn-default">阅读全文</a>
    </p>
    <hr>
    <p class="p3">
        <span class="glyphicon glyphicon-tag"></span>{{$v['name']}}
    </p>
</div>
@endforeach

@unless($list['total']==0)
<nav aria-label="Page navigation">
  <ul class="pagination">
    @if($list['current_page'] == 1)
    <li class="disabled">
        <span aria-hidden="true">&laquo;</span>
    </li>
    @else
    <li>
      <a href="{{$list['prev_page_url']}}" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    @endif
    @for($i=1; $i<=$list['last_page']; $i++)
    @if($list['current_page'] == $i)
    <li class="active"><a href="{{$list['path']}}?page={{$i}}">{{$i}}</a></li>
    @else
    <li><a href="{{$list['path']}}?page={{$i}}">{{$i}}</a></li>
    @endif
    @endfor
    @if($list['current_page'] == $list['last_page'])
    <li class="disabled">
        <span aria-hidden="true">&raquo;</span>
    </li>
    @else
    <li>
      <a href="{{$list['next_page_url']}}" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
    @endif
  </ul>
</nav>
@endunless

@endsection