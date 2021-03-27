<?php
namespace App\Http\Controllers;
use Validator;
use Response;
use File;
use Storage;
use disk;
use Auth;
use PDF;
use Zip;
use Session;
use newImage;
use ZanySoft\Zip\ZipManager;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use App\http\Requests;
use Illuminate\Http\Request;
use App\paper_link;
use App\student;
use App\result;
use App\teacher;
use App\time_left;
use App\admin;
use App\dpp;
use App\enquiry;
use App\dpp_link;
use App\advance_paper;
use App\answer;
use App\new_answer;
use App\normal_paper;
use App\online;
use App\question;
use App\new_question;
use App\chatbox;
use App\ts_folder;
use App\ts_folder_link;
use App\task_board;
use App\lecture;
use App\lecture_folder;
use App\lecture_link;
use App\lecture_subfolder;
use App\message;
use App\message_template;
use App\notification;
use App\notification_template;
use App\image;
use App\token;
use DB;
use Carbon\Carbon;

class AdminTaskController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function task_board(Request $request)
    {
        $users = task_board::where(['active' => '1'])->orderBy('publish_date', 'desc')
            ->paginate(30);
        //$users=$users->sortByDesc('publish_date');
        return view('admin.task_board', compact('users'));
    }

    public function add_task(Request $request)
    {
        $rules = array(
            'publishtime' => 'required',
        );
        $validator = Validator::make(Input::all() , $rules);
        if ($validator->fails())
        {
            return Response::json(array(
                'errors' => $validator->getMessageBag()
                    ->toArray()
            ));
        }
        else
        {
            $task = new task_board();
            $task->acd_id = Auth::user()->acd_id;
            $task->acd_name = Auth::user()->acd_name;
            $task->classid = $request->class;
            $task->courseid = $request->course;
            $task->coursetypeid = $request->coursetype;
            $task->groupid = $request->group;
            $task->cccgid = $request->class . $request->course . $request->coursetype . $request->group;
            $task->title = $request->title;
            $task->description = $request->description;
            $task->publish_date = $request->publishtime;
            $task->priority = $request->priority;
            $task->count = 0;
            $task->t_id = Auth::user()->id;
            $task->t_name = Auth::user()->name;
            $task->active = "1";
            $task->save();

            //----------------------------------------------------------------notification section------------------------------------------------
            $body = "A New " . $request->priority . " priority task has been assigned by " . Auth::user()->name . " sir.
  " . $request->title;
            $acd_id = Auth::user()->acd_id;
            $acd_name = Auth::user()->acd_name;
            $notification_type = 'right_icon_long';
            $title = 'New Task Assigned.';
            $body = $body;
            $title_long = 'New Task Assigned.';
            $body_long = $body;
            $title_line = null;
            $body_line1 = null;
            $body_line2 = null;
            $body_line3 = null;
            $body_line4 = null;
            $body_line5 = null;
            $body_line6 = null;
            $body_line7 = null;
            $body_line8 = null;
            $body_line9 = null;
            $body_line10 = null;
            $summary = "Task Assigned";
            $icon = asset('') . env('NOTI_ICON');
            $image = asset('') . env('NOTI_ICON');

            $browser_token = array();
            $br_tk = 0;
            $app_tk = 0;
            $app_token = array();
            $url = 'https://fcm.googleapis.com/fcm/send';
            $userss = student::where(['class' => $request->class, 'course' => $request->course, 'coursetype' => $request->coursetype, 'groupid' => $request->group, 'active' => '1'])
                ->select('id')
                ->get();
            foreach ($userss as $users)
            {
                $use = token::where(['user_id' => $users->id, 'user_type' => 'student', 'active' => '1'])
                    ->get();
                foreach ($use as $user)
                {
                    if ($user->token_type == "Application")
                    {
                        $app_token[$app_tk] = $user->token;
                        $app_tk++;
                    }
                    else
                    {
                        $browser_token[$br_tk] = $user->token;
                        $br_tk++;
                    }
                }
            }

            $headers = array(
                "Authorization: key=" . env('FCM_SERVER_KEY') ,
                'Content-Type: application/json'
            );
            $app_tokens = array_chunk($app_token, 999, true);
            foreach ($app_tokens as $token)
            {
                $app_fields = array(
                    'registration_ids' => $token,
                    'data' => array(
                        "title" => $title,
                        "body" => $body,
                        "title_long" => $title_long,
                        "body_long" => $body_long,
                        "title_line" => $title_line,
                        "body_line1" => $body_line1,
                        "body_line2" => $body_line2,
                        "body_line3" => $body_line3,
                        "body_line4" => $body_line4,
                        "body_line5" => $body_line5,
                        "body_line6" => $body_line6,
                        "body_line7" => $body_line7,
                        "body_line8" => $body_line8,
                        "body_line9" => $body_line9,
                        "body_line10" => $body_line10,
                        "summary" => $summary,
                        "icon" => $icon,
                        "sound" => "notification",
                        "noti_id" => rand(1, 1000) ,
                        "channel_id" => "Notification",
                        "image" => $image,
                        "type" => $notification_type,
                        "click_action" => "https://deltatrek.in/user/login"
                    )
                );

                $fields = json_encode($app_fields);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                $results = curl_exec($ch);
                curl_close($ch);
            }
            $browser_tokens = array_chunk($browser_token, 999, true);
            foreach ($browser_tokens as $token)
            {
                if ($notification_type == 'no_icon')
                {
                    $title = $title;
                    $body = $body;
                    $image = '';
                    $icon = 'https://deltatrek.in/img/mobile%20ins.png';
                }
                elseif ($notification_type == 'right_icon' || $notification_type == 'left_icon')
                {
                    $title = $title;
                    $body = $body;
                    $image = '';
                    $icon = $icon;
                }
                elseif ($notification_type == 'right_icon_long')
                {
                    $title = $title_long;
                    $body = $body_long;
                    $image = '';
                    $icon = $icon;
                }
                elseif ($notification_type == 'no_icon_long')
                {
                    $title = $title_long;
                    $body = $body_long;
                    $image = '';
                    $icon = 'https://deltatrek.in/img/mobile%20ins.png';
                }
                elseif ($notification_type == 'no_icon_image')
                {
                    $title = $title;
                    $body = $body;
                    $image = $image;
                    $icon = 'https://deltatrek.in/img/mobile%20ins.png';
                }
                elseif ($notification_type == 'right_icon_image_hide' || $notification_type == 'right_icon_image_show')
                {
                    $title = $title;
                    $body = $body;
                    $image = $image;
                    $icon = $icon;
                }
                elseif ($notification_type == 'no_icon_lines')
                {
                    $title = $title_line;
                    $body = $body_line1 . ' ' . $body_line2 . ' ' . $body_line3 . ' ' . $body_line4 . ' ' . $body_line5 . ' ' . $body_line6 . ' ' . $body_line7 . ' ' . $body_line8 . ' ' . $body_line9 . ' ' . $body_line10;
                    $image = '';
                    $icon = 'https://deltatrek.in/img/mobile%20ins.png';
                }
                elseif ($notification_type == 'right_icon_lines')
                {
                    $title = $title_line;
                    $body = $body_line1 . ' ' . $body_line2 . ' ' . $body_line3 . ' ' . $body_line4 . ' ' . $body_line5 . ' ' . $body_line6 . ' ' . $body_line7 . ' ' . $body_line8 . ' ' . $body_line9 . ' ' . $body_line10;
                    $image = '';
                    $icon = $icon;
                }
                $browser_fields = array(
                    'registration_ids' => $token,
                    'notification' => array(
                        "title" => $title,
                        "body" => $body,
                        "icon" => $icon,
                        "sound" => "notification",
                        "noti_id" => rand(1, 1000) ,
                        "image" => $image,
                        "click_action" => "https://deltatrek.in/user/login"
                    )
                );

                $fields = json_encode($browser_fields);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                $results = curl_exec($ch);
                curl_close($ch);
            }
            return response()->json($task);
        }
    }

    public function edit_task(Request $request)
    {
        $rules = array(
            'publishtime' => 'required',
        );
        $validator = Validator::make(Input::all() , $rules);
        if ($validator->fails())
        {
            return Response::json(array(
                'errors' => $validator->getMessageBag()
                    ->toArray()
            ));
        }
        else
        {
            $task = task_board::find($request->id);
            $task->acd_id = Auth::user()->acd_id;
            $task->acd_name = Auth::user()->acd_name;
            $task->classid = $request->class;
            $task->courseid = $request->course;
            $task->coursetypeid = $request->coursetype;
            $task->groupid = $request->edit_group;
            if ($task->cccgid != $request->class . $request->course . $request->coursetype . $request->edit_group)
            {
                $task->count = 0;
                $task->complete = NULL;
            }
            if ($task->t_id != Auth::user()
                ->id)
            {
                if (Auth::user()->admin != 'yes')
                {
                    return Response::json(array(
                        'errors' => 'Access Denied'
                    ));
                }
            }
            $task->cccgid = $request->class . $request->course . $request->coursetype . $request->edit_group;
            $task->title = $request->title;
            $task->description = $request->edit_description;
            if ($request->publishtime != '')
            {
                $task->publish_date = $request->publishtime;
            }
            $task->priority = $request->edit_priority;
            $task->save();

            //----------------------------------------------------------------notification section------------------------------------------------
            $body = "A New " . $request->edit_priority . " priority task has been assigned by " . Auth::user()->name . " sir.
  " . $request->title;
            $acd_id = Auth::user()->acd_id;
            $acd_name = Auth::user()->acd_name;
            $notification_type = 'right_icon_long';
            $title = 'New Task Assigned.';
            $body = $body;
            $title_long = 'New Task Assigned.';
            $body_long = $body;
            $title_line = null;
            $body_line1 = null;
            $body_line2 = null;
            $body_line3 = null;
            $body_line4 = null;
            $body_line5 = null;
            $body_line6 = null;
            $body_line7 = null;
            $body_line8 = null;
            $body_line9 = null;
            $body_line10 = null;
            $summary = "Task Assigned";
            $icon = asset('') . env('NOTI_ICON');
            $image = asset('') . env('NOTI_ICON');

            $browser_token = array();
            $br_tk = 0;
            $app_tk = 0;
            $app_token = array();
            $url = 'https://fcm.googleapis.com/fcm/send';
            $userss = student::where(['class' => $request->class, 'course' => $request->course, 'coursetype' => $request->coursetype, 'groupid' => $request->edit_group, 'active' => '1'])
                ->select('id')
                ->get();
            foreach ($userss as $users)
            {
                $use = token::where(['user_id' => $users->id, 'user_type' => 'student', 'active' => '1'])
                    ->get();
                foreach ($use as $user)
                {
                    if ($user->token_type == "Application")
                    {
                        $app_token[$app_tk] = $user->token;
                        $app_tk++;
                    }
                    else
                    {
                        $browser_token[$br_tk] = $user->token;
                        $br_tk++;
                    }
                }
            }

            $headers = array(
                "Authorization: key=" . env('FCM_SERVER_KEY') ,
                'Content-Type: application/json'
            );
            $app_tokens = array_chunk($app_token, 999, true);
            foreach ($app_tokens as $token)
            {
                $app_fields = array(
                    'registration_ids' => $token,
                    'data' => array(
                        "title" => $title,
                        "body" => $body,
                        "title_long" => $title_long,
                        "body_long" => $body_long,
                        "title_line" => $title_line,
                        "body_line1" => $body_line1,
                        "body_line2" => $body_line2,
                        "body_line3" => $body_line3,
                        "body_line4" => $body_line4,
                        "body_line5" => $body_line5,
                        "body_line6" => $body_line6,
                        "body_line7" => $body_line7,
                        "body_line8" => $body_line8,
                        "body_line9" => $body_line9,
                        "body_line10" => $body_line10,
                        "summary" => $summary,
                        "icon" => $icon,
                        "sound" => "notification",
                        "noti_id" => rand(1, 1000) ,
                        "channel_id" => "Notification",
                        "image" => $image,
                        "type" => $notification_type,
                        "click_action" => "https://deltatrek.in/user/login"
                    )
                );

                $fields = json_encode($app_fields);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                $results = curl_exec($ch);
                curl_close($ch);
            }
            $browser_tokens = array_chunk($browser_token, 999, true);
            foreach ($browser_tokens as $token)
            {
                if ($notification_type == 'no_icon')
                {
                    $title = $title;
                    $body = $body;
                    $image = '';
                    $icon = 'https://deltatrek.in/img/mobile%20ins.png';
                }
                elseif ($notification_type == 'right_icon' || $notification_type == 'left_icon')
                {
                    $title = $title;
                    $body = $body;
                    $image = '';
                    $icon = $icon;
                }
                elseif ($notification_type == 'right_icon_long')
                {
                    $title = $title_long;
                    $body = $body_long;
                    $image = '';
                    $icon = $icon;
                }
                elseif ($notification_type == 'no_icon_long')
                {
                    $title = $title_long;
                    $body = $body_long;
                    $image = '';
                    $icon = 'https://deltatrek.in/img/mobile%20ins.png';
                }
                elseif ($notification_type == 'no_icon_image')
                {
                    $title = $title;
                    $body = $body;
                    $image = $image;
                    $icon = 'https://deltatrek.in/img/mobile%20ins.png';
                }
                elseif ($notification_type == 'right_icon_image_hide' || $notification_type == 'right_icon_image_show')
                {
                    $title = $title;
                    $body = $body;
                    $image = $image;
                    $icon = $icon;
                }
                elseif ($notification_type == 'no_icon_lines')
                {
                    $title = $title_line;
                    $body = $body_line1 . ' ' . $body_line2 . ' ' . $body_line3 . ' ' . $body_line4 . ' ' . $body_line5 . ' ' . $body_line6 . ' ' . $body_line7 . ' ' . $body_line8 . ' ' . $body_line9 . ' ' . $body_line10;
                    $image = '';
                    $icon = 'https://deltatrek.in/img/mobile%20ins.png';
                }
                elseif ($notification_type == 'right_icon_lines')
                {
                    $title = $title_line;
                    $body = $body_line1 . ' ' . $body_line2 . ' ' . $body_line3 . ' ' . $body_line4 . ' ' . $body_line5 . ' ' . $body_line6 . ' ' . $body_line7 . ' ' . $body_line8 . ' ' . $body_line9 . ' ' . $body_line10;
                    $image = '';
                    $icon = $icon;
                }
                $browser_fields = array(
                    'registration_ids' => $token,
                    'notification' => array(
                        "title" => $title,
                        "body" => $body,
                        "icon" => $icon,
                        "sound" => "notification",
                        "noti_id" => rand(1, 1000) ,
                        "image" => $image,
                        "click_action" => "https://deltatrek.in/user/login"
                    )
                );

                $fields = json_encode($browser_fields);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                $results = curl_exec($ch);
                curl_close($ch);
            }

            return response()->json($task->id);
        }
    }
    public function delete_task(Request $request)
    {
        $task = task_board::find($request->id);
        if ($task->t_id != Auth::user()
            ->id)
        {
            if (Auth::user()->admin != 'yes')
            {
                return Response::json(array(
                    'errors' => 'Access Denied'
                ));
            }
        }
        $task->active = "0";
        $task->save();
        return Response::json($task->id);
    }

    public function ckeditor_upload(Request $request)
    {
        if ($request->hasFile('upload'))
        {
            $originName = $request->file('upload')
                ->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')
                ->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')
                ->move(base_path() . '/public_html/task_board_images', $fileName);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = env('APP_URL') . '/task_board_images/' . $fileName;
            $msg = 'Image Uploaded Successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }

}

