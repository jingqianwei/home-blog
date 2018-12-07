@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">欢迎！这里是 “那就远走” 的个人博客</div>

                <div class="card-body">
                    如无牵挂，那就远走。
                    {{--  上面说过这里会添加一个按钮  --}}
                    <a href="{{ route('blog.index') }}" class="btn btn-lg btn-block btn-primary">点击这里查看我的博客</a>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
