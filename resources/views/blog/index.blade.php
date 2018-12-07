@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">文章列表</div>

                <div class="card-body">
                    <table class="table table-hover table-bordered">
                        <thead class="bg-info">
                        <tr>
                            <th>文章标题</th>
                            <th>发布时间</th>
                            <th>相关操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{-- 这里通过 @foreach 遍历数据 --}} @foreach ($blogs as $blog)
                            <tr>
                                <td>{{ $blog->title }}</td>
                                <td>{{ $blog->created_at }}</td>
                                <td>
                                    <a href="{{ route('blog.edit', $blog->id) }}" class="btn btn-info">编辑文章</a>
                                    <a href="javascript:deleteConfirm({{ $blog->id }});" class="btn btn-danger btn-sm">删除文章</a>
                                    {{--  因为删除也需要 csrf 令牌认证，所以弄个表单  --}}
                                    <form method="POST" action="{{ route('blog.destroy', $blog->id) }}" id="delete-blog-{{ $blog->id }}">
                                        @csrf
                                        {{--  这里伪造DELETE请求  --}}
                                        @method("DELETE")
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        {{-- 这里通过 $blogs->links() 来显示分页按钮 --}} {{ $blogs->links() }}
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
