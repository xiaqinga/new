@extends('admin.layouts.base')

@section('title','公告列表')

@section('pageHeader','公告管理')

@section('pageDesc','公告管理页面')

@section('content')

<div class="row page-title-row" style="margin:5px;">
    <div class="col-md-6">
    </div>
    <div class="col-md-6 text-right">
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="box">
            @include('admin.partials.errors')
            @include('admin.partials.success')
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-12">
                        <form class="form-horizontal" action="${pageContext.request.contextPath}/equipment/list.do" method="post">
                            <div class="form-group">

                                <label class="col-sm-1 control-label">标题：</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="deptname" name="deptname" style="width: 250px">
                                </div>
                                <label class="col-sm-1 control-label">公告类型：</label>
                                <div class="col-sm-2">

                                    <select name="indexClassId" style="width: 250px" class="form-control">
                                        <option value="">请选择...</option>
                                        @foreach ($types as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-sm-1 control-label">状态：</label>
                                <div class="col-sm-2">
                                    <select class="form-control" style="width: 250px" id="state" name="state">
                                        <option value="">请选择...</option>
                                        <option value="1">正常</option>
                                        <option value="2">隐藏</option>
                                    </select>
                                </div>



                                <div class="col-sm-1">
                                    <button  type="button" class="btn btn-success search"  >查 询</button>
                                </div>

                                <div class="col-sm-2 text-right">
                                    <a href="{{ url('/admin/adposition/create')}}" class="btn btn-primary btn-md">
                                        <i class="fa fa-plus-circle"></i> 添加公告
                                    </a>
                                </div>
                            </div>



                        </form>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <table id="tags-table" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th data-sortable="false" class="hidden-sm">序号</th>
                        <th class="hidden-sm">标题</th>
                        <th class="hidden-sm">公告类型</th>
                        <th class="hidden-md">发布时间</th>
                        <th class="hidden-md">浏览量</th>
                        <th class="hidden-md">状态</th>
                        <th class="hidden-md">排序</th>
                        <th data-sortable="false">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<div class="modal fade" id="modal-delete" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    ×
                </button>
                <h4 class="modal-title">提示</h4>
            </div>
            <div class="modal-body">
                <p class="lead">
                    <i class="fa fa-question-circle fa-lg"></i>
                    确认要删除这个公告吗?
                </p>
            </div>
            <div class="modal-footer">
                <form class="deleteForm" method="POST" action="/admin/adposition">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-times-circle"></i>确认
                    </button>
                </form>
            </div>
        </div>
        @stop

        @section('js')
        <script>
            $(function () {
                var table = $("#tags-table").DataTable({
                    language: {
                        "processing": "处理中...",
                        "lengthMenu": "显示 _MENU_ 项结果",
                        "zeroRecords": "没有匹配结果",
                        "info": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                        "infoEmpty": "显示第 0 至 0 项结果，共 0 项",
                        "infoFiltered": "(由 _MAX_ 项结果过滤)",
                        "infoPostFix": "",
                        "search": "搜索:",
                        "url": "",
                        "emptyTable": "表中数据为空",
                        "loadingRecords": "载入中...",
                        "infoThousands": ",",
                        "paginate": {
                            "sFirst": "首页",
                            "sPrevious": "上页",
                            "sNext": "下页",
                            "sLast": "末页"
                        },
                        "aria": {
                            "sortAscending": ": 以升序排列此列",
                            "sortDescending": ": 以降序排列此列"
                        }
                    },
                    searching:false,
                    order: [[1, "desc"]],
                    serverSide: true,
                    ajax: {
                        url: "{{ url('admin/adposition/index') }}",
                        type: 'POST',
                        "data":function(d){
                            d.state=$("#state").val();
                            d.deptname=$("#deptname").val().trim();
                            d.startTime=$("#startTime").val();
                            d.endTime=$("#endTime").val();
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    },
                    "columns": [
                        {"data": "id"},
                        {"data": "templateName"},
                        {"data": "indexClassId"},
                        {"data": "createData"},
                        {"data": "pageviews"},
                        {"data": "status"},
                        {"data": "sort"},
                        {"data": "action"}
                    ],
                    columnDefs: [

                        {
                            'targets': -1, "render": function (data, type, row) {
                            return '<a style="margin:3px;" href="/admin/adposition/' + row['id'] + '/edit" class="X-Small btn-xs text-success"><i class="fa fa-edit"></i> 编辑</a><a style="margin:3px;" href="#" attr="' + row['id'] + '" class="delBtn X-Small btn-xs text-danger"><i class="fa fa-times-circle"></i> 删除</a>';
                        }
                        },
                        {
                            "targets": -3, "render": function (data, type, row) {

                            if (data == 1) {
                                return '<span class=""><small class="label bg-green">正常</small></span>';
                            } else {
                                return '<span class=""><small class="label bg-red">隐藏</small></span>';
                            }
                        }
                        }
                    ]
                });

                table.on('preXhr.dt', function () {
                    loadShow();
                });

                table.on('draw.dt', function () {
                    loadFadeOut();
                });

                table.on('order.dt search.dt', function () {
                    table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();

                $("table").delegate('.delBtn', 'click', function () {
                    var id = $(this).attr('attr');
                    $('.deleteForm').attr('action', '/admin/adposition/' + id);
                    $("#modal-delete").modal();
                });

                $(document).delegate('.search','click',function() {
                    table.ajax.reload();
                    // $("#example").dataTable().api().ajax.reload();
//		   table.search(
//				   "state="+$('#state').val()
//			    ).draw();
//		  var 	    state=$("#state").val();
//		   table.column(1).search(state, false, false).draw();
                });

            });
        </script>
        @stop