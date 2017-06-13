@section('css')
    {{--icheck--}}
    <link href="{{ asset("/bower_components/AdminLTE/plugins/iCheck/square/blue.css") }}" rel="stylesheet">
    <link href="{{ asset("/js/jcrop/image/jquery.Jcrop.min.css") }}" rel="stylesheet">
    <link href="{{ asset("/js/jcrop/image/J_jcorp.css") }}" rel="stylesheet">
@stop

<div class="form-group">
    <label for="" class="col-md-3 control-label">标题</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="templateName" value="" autofocus>
    </div>
</div>
<div class="form-group">
    <label for="" class="col-md-3 control-label">摘要</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="" value="" autofocus>
    </div>
</div>
<div class="form-group">
    <label for="" class="col-md-3 control-label">封面图</label>
    <div class="col-md-5">
        <div class="controls" id="upload_logo">
            @if (isset($photoUrl))
            <img style="width: 105px; height: 105px;" src="{{isset($photoUrl) ? $photoUrl : '' }}">
            @endif
            <a href="javascript:void(0);" id="upload_btn" class="btn btn-primary btn-md">上传</a>
            <input type="hidden" name="photoUrl" id="photoUrl" value=""/>
            <span style="color:#ff9900;">&nbsp;&nbsp;图片尺寸&nbsp;&nbsp;180*180px</span>

        </div>
    </div>
</div>

<div class="form-group">
    <label for="" class="col-md-3 control-label">公告类型</label>
    <div class="col-md-5">

            <select name="indexClassId" style="height: 30px;line-height: 30px">
                @foreach ($types as $key=>$value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>


    </div>
</div>


<div class="form-group">
    <label for="" class="col-md-3 control-label">链接</label>
    <div class="col-md-5">
        <input type="hidden" name="forUrl" id="forUrl" value=""/>

    </div>
</div>
<div class="form-group">
    <label for="" class="col-md-3 control-label">内容</label>
    <div class="col-md-5">
  <textarea>

        </textarea>

    </div>
</div>

@section('js')
    <script src="{{ asset("/js/jcrop/js/jquery.Jcrop.min.js") }}"></script>
    <script src="{{ asset("/js/jcrop/js/J_jcorp.js") }}"></script>
    <script>
    $("#upload_btn").J_jcorp({
    'filePath': '{{ $otherImgPath }}',
    'imagePath': '{{ $otherImgUrl }}',
    'aspectRatio': 1.5,//裁剪宽高比
    'maxSize': [600, 400],//裁剪最大尺寸 [width,height]
    'minSize': [150, 100],//裁剪最小尺寸 [width,height]
    'picSize': [200, 200],//最终保存图片尺寸 [width,height]
    'quality': 1,//裁剪完后图片压缩比例
    'AjaxData': ['{{ $resSer }}', 'json'],// [0,1] 0:接口地址 1:dataType(json，jsonp)(必填项)
    'callback': function (s, data) {//接口成功回调
    $('#upload_cre').children('img').remove();
    $('#upload_cre').prepend('<img src="' + s + '" style="width: 105px; height: 105px;" />');
    if (data.data[0].photoUrl != undefined) {
    $("#crePhotoUrl").val(data.data[0].photoUrl);
    }
    }
    });
    </script>
@stop

