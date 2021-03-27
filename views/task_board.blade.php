@extends('layout/admin_dashboard')
@extends('layout/details')
@section('popup')
{{-- Modal Form add student --}}
<div id="create_task" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Task</h4>
            </div>
            <div class="modal-body">
                <script src="{{asset('js/ckeditors/ckeditor.js')}}"></script>
                <link href="{{ asset('adminsa/bootstrap/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" media="screen">
                <?php $date = new \DateTime();
$timer=date_format($date, 'Y-m-d H:i:s');?>
                <form class="form-group add_task_form" method="POST" enctype="multipart/form-data">
                    {{ csrf_field()}}
                    <p class="add-error text-center alert alert-danger hidden"></p>
                    <p class="add-success text-center alert alert-success hidden">Task Added Successfully...</p>
                    <!-- ----------------------------------------- option menu------------------------------------ -->
                    <div><select id="class" class="form-control" name="class" required="">
                            <option value="">Class</option>
                            <option value="8th">8th</option>
                            <option value="9th">9th</option>
                            <option value="10th">10th</option>
                            <option value="11th">11th</option>
                            <option value="12th">12th</option>
                            <option value="Repeater">Repeater</option>
                        </select></div>
                    <div id="course"><select class="form-control" name="course" required="">
                            <option value="">Course</option>
                            <option value="Foundation">Foundation</option>
                            <option value="JEE Main">JEE Main</option>
                            <option value="JEE (Main + Advance)">JEE (Main + Advance)</option>
                            <option value="NEET">NEET</option>
                            <option value="NEET + AIIMS">NEET + AIIMS</option>
                            <option value="MHT-CET">MHT-CET</option>
                            <option value="Classroom Test">Classroom Test</option>
                        </select> </div>
                    <div id="coursetype"><select class="form-control" name="coursetype" required="">
                            <option value="">Course Type</option>
                            <option value="Classroom Course">Classroom Course</option>
                            <option value="Crash Course">Crash Course</option>
                            <option value="Distance Learning">Distance Learning</option>
                        </select> </div>
                    <div class="time">
                        <label for="dtp_input1" class="col-lg-12 control-label">Publish Time</label>
                        <div class="input-group date form_datetime" data-date="{{$timer}}" data-date-format="dd-mm-yyyy hh:ii:ss" data-link-field="dtp_input11">
                            <input class="form-control" size="19" type="text" value="" readonly>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>&nbsp;
                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                        </div>
                        <input type="hidden" id="dtp_input11" name="publishtime" value="" /><br />
                    </div>
                    <div style="display: flex;">
                        <label>Group</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A<input type="radio" name="group" value="A" required="">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;B<input type="radio" name="group" value="B" required="">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C<input type="radio" name="group" value="C" required="">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;D<input type="radio" name="group" value="D" required="">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E<input type="radio" name="group" value="E" required="">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;F<input type="radio" name="group" value="F" required="">
                    </div>
                    <div style="display: flex;">
                        <label>Priority</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;High<input type="radio" name="priority" value="High" required="">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Medium<input type="radio" name="priority" value="Medium" required="">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;low<input type="radio" name="priority" value="Low" required="">
                    </div>
                    <div class="col-lg-12">
                        <input type="text" id="title" name="title" placeholder="Title" required="">
                    </div>
                    <div class="col-lg-12">
                        <textarea class="form-control" id="description" name="description"></textarea>
                        <script>
                            CKEDITOR.replace('description',{
                          filebrowserUploadUrl: "{{route('admin-ckeditor_upload', ['_token' => csrf_token() ])}}",
                                filebrowserUploadMethod: 'form'
                        });
                      </script>
                        {{-- <style type="text/css">
                        .modal {
                            z-index: 10009;
                        }

                        </style> --}}
                    </div>
            </div>
            <div class="modal-footer">
                <button style="background-color: #fd6e70" class="btn btn-warning" type="submit" id="add-task">
                    Add Task
                </button></form>
                <button class="btn btn-warning" type="button" data-dismiss="modal">
                    <span class="glyphicon glyphicon-remobe"></span>Close
                </button>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<div id="edit_task" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Task</h4>
            </div>
            <div class="modal-body">
                <link href="{{ asset('adminsa/bootstrap/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" media="screen">
                <?php $date = new \DateTime();
$timer=date_format($date, 'Y-m-d H:i:s');?>
                <form class="form-group edit_task_form" method="POST" enctype="multipart/form-data">
                    {{ csrf_field()}}
                    <input type="hidden" id="edit_id" name="id" required="">
                    <p class="edit-error text-center alert alert-danger hidden"></p>
                    <p class="edit-success text-center alert alert-success hidden">Task Edited Successfully...</p>
                    <!-- ----------------------------------------- option menu------------------------------------ -->
                    <div id="class"><select class="form-control" id="edit_class" name="class" required="">
                            <option value="">Class</option>
                            <option value="8th">8th</option>
                            <option value="9th">9th</option>
                            <option value="10th">10th</option>
                            <option value="11th">11th</option>
                            <option value="12th">12th</option>
                            <option value="Repeater">Repeater</option>
                        </select></div>
                    <div id="course"><select class="form-control" id="edit_course" name="course" required="">
                            <option value="">Course</option>
                            <option value="Foundation">Foundation</option>
                            <option value="JEE Main">JEE Main</option>
                            <option value="JEE (Main + Advance)">JEE (Main + Advance)</option>
                            <option value="NEET">NEET</option>
                            <option value="NEET + AIIMS">NEET + AIIMS</option>
                            <option value="MHT-CET">MHT-CET</option>
                            <option value="Classroom Test">Classroom Test</option>
                        </select> </div>
                    <div id="coursetype"><select class="form-control" id="edit_coursetype" name="coursetype" required="">
                            <option value="">Course Type</option>
                            <option value="Classroom Course">Classroom Course</option>
                            <option value="Crash Course">Crash Course</option>
                            <option value="Distance Learning">Distance Learning</option>
                        </select> </div>
                    <div class="time">
                        <label for="dtp_input1" class="col-lg-12 control-label">Publish Time</label>
                        <div class="input-group date form_datetime" data-date="{{$timer}}" data-date-format="dd-mm-yyyy hh:ii:ss" data-link-field="dtp_input1">
                            <input class="form-control edit_publishtime" size="19" type="text" value="" readonly>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                        </div>
                        <input type="hidden" id="dtp_input1" name="publishtime" value="" /><br />
                    </div>
                    <div style="display: flex;">
                        <label>Group</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A<input type="radio" id="edit_group" name="edit_group" value="A" required="">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;B<input type="radio" id="edit_group" name="edit_group" value="B" required="">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C<input type="radio" id="edit_group" name="edit_group" value="C" required="">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;D<input type="radio" id="edit_group" name="edit_group" value="D" required="">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E<input type="radio" id="edit_group" name="edit_group" value="E" required="">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;F<input type="radio" id="edit_group" name="edit_group" value="F" required="">
                    </div>
                    <div style="display: flex;">
                        <label>Priority</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;High<input type="radio" id="edit_priority" name="edit_priority" value="High" required="">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Medium<input type="radio" id="edit_priority" name="edit_priority" value="Medium" required="">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;low<input type="radio" id="edit_priority" name="edit_priority" value="Low" required="">
                    </div>
                    <div class="col-lg-12">
                        <input type="text" id="edit_title" name="title" placeholder="Title" required="">
                    </div>
                    <div class="col-lg-12">
                        <textarea class="form-control" id="edit_description" name="edit_description"></textarea>
                        <script>
                            CKEDITOR.replace('edit_description',{
                          filebrowserUploadUrl: "{{route('admin-ckeditor_upload', ['_token' => csrf_token() ])}}",
                                filebrowserUploadMethod: 'form'
                        });
                      </script>
                        <style type="text/css">
                        .modal {
                            z-index: 10009;
                        }

                        </style>
                    </div>
            </div>
            <div class="modal-footer">
                <button style="background-color: #fd6e70" class="btn btn-warning" type="submit" id="edit-task">
                    Edit Task
                </button></form>
                <button class="btn btn-warning" type="button" data-dismiss="modal">
                    <span class="glyphicon glyphicon-remobe"></span>Close
                </button>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<div id="delete_task" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Delete Task</h4>
            </div>
            <div class="modal-body">
                {{ csrf_field()}}
                <p class="delete-error text-center alert alert-danger hidden"></p>
                <p class="delete-success text-center alert alert-success hidden">Task Deleted Successfully...</p>
                <h5>Are You Sure? want to delete...</h5>
                <input type="hidden" id="delete_id">
            </div>
            <div class="modal-footer">
                <button style="background-color: #fd6e70" class="btn btn-warning" type="submit" id="delete-task">
                    Delete Task
                </button></form>
                <button class="btn btn-warning" type="button" data-dismiss="modal">
                    <span class="glyphicon glyphicon-remobe"></span>Close
                </button>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
@section('analysis')
<div class="d-sm-flex justify-content-between align-items-center mb-4 pt-2">
    <h3 class="text-dark mb-0">Task Board&nbsp;<a class="btn btn-success send_msg" data-toggle="modal" data-target="#create_task" style="padding: 6px 8px;font-size: 13px;">Add Task&nbsp;</a></h3>
</div>
<div class="row">
    <?php $date_show='true'; $no=0;  $date=0;?>
    @foreach($users as $user)
    <?php if($user->priority=="High"){$lable = "danger";} if($user->priority=="Medium"){$lable = "warning";} if($user->priority=="Low"){$lable = "primary";}  ?>
    <?php if(date_format(date_create($user->publish_date),"d/m/Y") != $date || $no==0){ $date_show='true'; $no=1; $date = date_format(date_create($user->publish_date),"d/m/Y"); }else{$date_show='false'; } ?>
    @if($date_show=='true')
    <h4 style="text-align: center;width: 100%;">{{date_format(date_create($user->publish_date),"jS M, Y")}}</h4>
    @endif
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-left-{{$lable}}">
            <div class="card-body">
                <div class="row align-items-center no-gutters">
                    <div class="col mr-2">
                        <div class="text-capitalize text-{{$lable}} font-weight-bold text-xs"><span>{{$user->t_name}} sir</span> <span style="float: right;">Priority : {{$user->priority}}</span></div>
                        <div class="text-dark font-weight-bold mb-0 text-xs"><span></span><a style="font-size: 14px;">{{$user->title}}</a></div>
                        <div class="text-dark mb-3 text-xs description" data-data="{{$user->description}}"></div>
                        <div class="text-dark mb-0 text-xs font-weight-bold"><span></span>
                            <a style="font-size: 11px; max-width: 70%; float: left;">Publish : {{$user->classid." | ".$user->courseid." | ".$user->groupid." at ".date_format(date_create($user->publish_date),"h:i a")}}</a><a style="float: right; font-size: 11px; padding: 3px 6px; color: #fff;" class="btn btn-success details" data-id="{{$user->id}}" data-t_name="{{$user->t_name}}" data-title="{{$user->title}}" data-description='{{$user->description}}' data-complete="{{$user->complete}}" data-publishtime="{{$user->publish_date}}" data-priority="{{$user->priority}}" data-class="{{$user->classid}}" data-course="{{$user->courseid}}" data-coursetype="{{$user->coursetypeid}}" data-group="{{$user->groupid}}">Details&nbsp;{{$user->count}}</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
</div>
<style type="text/css">
.card-body {
    padding: .25rem 1.15rem;
}

.slidecontainer {
    width: 100%;
}

.slider {
    -webkit-appearance: none;
    width: 67%;
    height: 8px;
    padding: 0;
    border-radius: 5px;
    background: #d3d3d3;
    outline: none;
    opacity: 0.7;
    -webkit-transition: .2s;
    transition: opacity .2s;
}

.slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #4CAF50;
    cursor: pointer;
}

.slider::-moz-range-thumb {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #4CAF50;
    cursor: pointer;
}

.description {
    font-size: 12px;
}

.description p {
    margin: 0;
}

</style>
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('adminsa/bootstrap/js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('adminsa/bootstrap/js/bootstrap-datetimepicker.fr.js') }}" charset="UTF-8"></script>
<script type="text/javascript">
function desc() {
    $('.description').each(function(e) {
        $(this).html($(this).data("data"));

    });
}
desc();
$("body").delegate(".details", "click", function() {
    var t_name = $(this).data("t_name");
    var id = $(this).data("id");
    var title = $(this).data("title");
    var progress = 100;
    var description = $(this).data("description");
    var classid = $(this).data("class");
    var courseid = $(this).data("course");
    var coursetypeid = $(this).data("coursetype");
    var group = $(this).data("group");
    var priority = $(this).data("priority");
    var publishtime = $(this).data("publishtime");
    var data = $(this).data("complete");
    var data = JSON.parse(JSON.stringify(data));
    $('.socket_body').empty().html("");
    var html = $('.socket_body').append('<div class="row align-items-center no-gutters"><div class="col mr-5"><div class="text-capitalize text-danger font-weight-bold text-xs"><span>' + t_name + ' Sir</span><span style="float: right;"><a style="float: right; font-size: 11px; padding: 3px 6px; color: #fff;" class="btn btn-danger Delete" data-id="' + id + '"data-toggle="modal" data-target="#delete_task">Delete</a>&nbsp;<a style="float: right; font-size: 11px; padding: 3px 6px; color: #fff;" class="btn btn-info Edit mr-1" data-id="' + id + '" data-title="' + title + '"data-publishtime="' + publishtime + '"data-class="' + classid + '"data-course="' + courseid + '"data-coursetype="' + coursetypeid + '"data-group="' + group + '"data-priority="' + priority + '" data-toggle="modal" data-target="#edit_task" >Edit</a></span></div><div class="text-dark font-weight-bold mb-0 text-xs"><span></span><a style="font-size: 14px;">' + title + '</a></div><div class="text-dark mb-3 text-xs description">' + description + '</div></div><div><br>');
    $(".spinner").hide();
    $('.Edit').attr("data-description", description);
    $('.socket_title').text('Details');
    for (var i = 0; i < data.length; i++) {
        $('.socket_body').append('<div class="text-capitalize font-weight-bold text-xs"><span>' + data[i].s_name + '</span> <span style="float: right;">mark : ' + data[i].mark + '</span></div><div class="text-dark mb-5 text-xs"><span></span><div class="slidecontainer"><a class="text-dark font-weight-bold">Progress </a><input style="background:linear-gradient(to right, #82CFD0 0%, #82CFD0 ' + data[i].complete + '%, #d3d3d3 ' + data[i].complete + '%, #d3d3d3 100%);" type="range" min="1" max="100" value="' + data[i].complete + '" class="slider" disabled>&nbsp;<a text-dark font-weight-bold">' + data[i].complete + '%</a></div>');
    }
    $("#socket").show("closed");
});
$('.form_datetime').datetimepicker({
    //language:  'fr',
    weekStart: 1,
    todayBtn: 1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    forceParse: 0,
    showMeridian: 1
});

$(".form_datetime").datetimepicker({
    format: "dd-mm-yyyy hh:ii:ss",
    autoclose: true,
    todayBtn: true,
    startDate: "01-03-2019 00:00",
    minuteStep: 10
});

</script>
<script type="text/javascript">
$('.add_task_form').on('submit', function(event) {
    event.preventDefault();
    for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
    var loading = document.getElementById('loading');
    loading.style.display = '';
    $.ajax({
        url: "{{ route('admin-add_task') }}",
        method: "POST",
        data: new FormData(this),
        dataType: 'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
            if ((data.errors)) {
                if (!$('.add-success').hasClass('hidden')) {
                    $('.add-success').addClass('hidden');
                }
                $('.add-error').removeClass('hidden');
                $('.add-error').text('Publish time cant\'t be empty');
                $("#loading").fadeOut(500);

            } else {

                $('.add_task_form').trigger("reset");
                CKEDITOR.instances['description'].setData('');
                if (!$('.add-error').hasClass('hidden')) {
                    $('.add-error').addClass('hidden');
                }
                if ($('.add-success').hasClass('hidden')) {
                    $('.add-success').removeClass('hidden');
                    $('.add-success').text('Task Added Successfully...');
                    setTimeout(function() {
                        $('.add-success').addClass('hidden');
                        $('#edit_task').modal('hide');
                    }, 2000);
                    setTimeout(function() { location.reload() }, 3000);
                }

                $("#loading").fadeOut(500);
            }

        },
    })
});
$("body").delegate(".Delete", "click", function() {
    $('#delete_id').val($(this).data("id"));
    $("#socket").hide();
});
$("body").delegate("#delete-task", "click", function() {
    var id = $('#delete_id').val();
    $.ajax({
        type: 'POST',
        url: '{{ route('
        admin - delete_task ') }}',
        data: {
            '_token': $('input[name=_token]').val(),
            'id': id,
        },
        success: function(data) {
            if ((data.errors)) {
                if (!$('.delete-success').hasClass('hidden')) {
                    $('.delete-success').addClass('hidden');
                }
                $('.delete-error').removeClass('hidden');
                $('.delete-error').text('Access Denied...');
            } else {
                if (!$('.delete-error').hasClass('hidden')) {
                    $('.delete-error').addClass('hidden');
                }
                if ($('.delete-success').hasClass('hidden')) {
                    $('.delete-success').removeClass('hidden');
                    $('.delete-success').text('Delete Successfully...');
                    setTimeout(function() {
                        $('.delete-success').addClass('hidden');
                        $('#delete_task').modal('hide');
                    }, 2000);
                    setTimeout(function() { location.reload() }, 3000);
                }
            }
        },
    });
});
$("body").delegate(".Edit", "click", function() {

    $('#edit_id').val($(this).data('id'));
    $('#edit_title').val($(this).data('title'));
    CKEDITOR.instances['edit_description'].setData($(this).data('description'));
    $('.edit_publishtime').val($(this).data('publishtime'));
    $('#dtp_input1').val($(this).data('publishtime'));
    $('#edit_class').val($(this).data('class'));
    $('#edit_course').val($(this).data('course'));
    $('#edit_coursetype').val($(this).data('coursetype'));
    $('input[name="edit_group"][value=' + $(this).data('group') + ']').prop('checked', true).trigger("click");
    $('input[name="edit_priority"][value=' + $(this).data('priority') + ']').prop('checked', true).trigger("click");
    $("#socket").hide();
});

$('.edit_task_form').on('submit', function(event) {
    event.preventDefault();
    for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
    $.ajax({
        type: 'POST',
        url: '{{ route('
        admin - edit_task ') }}',
        method: "POST",
        data: new FormData(this),
        dataType: 'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
            if ((data.errors)) {
                if (!$('.edit-success').hasClass('hidden')) {
                    $('.edit-success').addClass('hidden');
                }
                $('.edit-error').removeClass('hidden');
                $('.edit-error').text('Access Denied or Something missing...');
            } else {
                if (!$('.edit-error').hasClass('hidden')) {
                    $('.edit-error').addClass('hidden');
                }
                if ($('.edit-success').hasClass('hidden')) {
                    $('.edit-success').removeClass('hidden');
                    $('.edit-success').text('Edit Successfully...');
                    setTimeout(function() {
                        $('.edit-success').addClass('hidden');
                        $('#edit_task').modal('hide');
                    }, 2000);
                    setTimeout(function() { location.reload() }, 3000);
                }
            }
        },
    });
});

</script>
@endsection
