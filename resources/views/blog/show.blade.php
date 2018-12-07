@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">文章详情</div>

                <div class="card-body">
                    <h1 class="text-center">{{ $blog->title }}</h1>

                    <p>发布时间<small>{{ $blog->created_at }}</small></p>

                    <hr>

                    <p> {{ $blog->content }} </p>
                </div>
                <a href="{{ route('blog.edit', $blog->id) }}" class="btn btn-info">编辑文章</a>
                <a href="javascript:deleteConfirm({{ $blog->id }});" class="btn btn-danger btn-sm">删除文章</a>
                {{--  因为删除也需要 csrf 令牌认证，所以弄个表单  --}}
                <form method="POST" action="{{ route('blog.destroy', $blog->id) }}" id="delete-blog-{{ $blog->id }}">
                    @csrf
                    {{--  这里伪造DELETE请求  --}}
                    @method("DELETE")
                </form>
            </div>
        </div>
    </div>
    @include('components._error')
    <form method="POST" action="{{ route('comment.store') }}">
        @csrf
        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
        <input type="hidden" name="blog_id" value="{{ $blog->id }}">

        <div class="form-group">
            <label for="content"></label>
            {{-- 样式里面加一个判断，判断是否有关于content的错误有的话给样式给文本域加一个红边边 --}}
            <textarea id="content" class="form-control {{ $errors->has('content') ? ' is-invalid' : '' }}" cols="30" rows="10" name="content">你对文章有什么看法呢？</textarea>
            {{-- 如果有错误，再显示一个小的错误提示信息 --}}
            @if ($errors->has('content'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('content') }}</strong>
                </span>
            @endif
        </div>
        <button class="btn btn-primary" type="submit">发表评论</button>
    </form>
    <div>
        <h3>评论</h3>
        <ul>
            @foreach ($comments as $comment)
                <li>
                    <p>评论时间：{{ $comment->created_at }}</p>
                    <small>{{ $comment->userName() }} 评论说：</small>“ {{ $comment->content }} ”
                </li>
            @endforeach
        </ul>
    </div>
@endsection
