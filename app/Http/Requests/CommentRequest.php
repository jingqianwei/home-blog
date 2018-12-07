<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 开启授权
     * @return bool
     */
    public function authorize()
    {
        return true; //如果返回false则所有请求都无法生效，会告诉你没有授权（其实在这里面我们是需要去进行判断的，但是这里的逻辑很简单：
        //只有登陆才能查看文章详情，才能看到文章详情下面发表评论的表单，才能发表评论。）所以我们这里直接 return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * 编辑规则
     * @return array
     */
    public function rules()
    {
        return [
            'content' => 'required|min:15|max:100', //这里你可以定义规则我的是：必填、最少15字、最多100字
        ];
    }
}
