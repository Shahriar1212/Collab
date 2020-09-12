<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsfeedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(){
        $user_id = Auth()->user()->id;
        $interests = DB::table("interests")->where("user_id", $user_id)->get("interest");
        $related_users = array();
        
        foreach($interests as $interest){
            $users = DB::table("interests")->where("interest", $interest->interest)->get("user_id");
            //dd($users);
            array_push($related_users, $users[0]->user_id);
        }
        //dd($related_users);
        $releted_users = array_unique($related_users);
        //dd($related_users);
        $posts = DB::table("newsfeeds")->whereIn("user_id", $related_users)->orderBy("created_at", "DESC")->get();
        // $posts = array();
        // foreach($releted_users as $user){
        //     $post = DB::table("newsfeeds")->where("user_id", $user)->orderBy("created_at", "DESC")->first();
        //     if(!is_null($post)){
        //         array_push($posts, $post);
        //     }
        // }
        //dd($posts);
        //$posts = \App\Newsfeed::orderBy('created_at', 'DESC')->get();
        //dd($posts);
        //dd($posts);
        return View("newsfeed.index", compact("posts"));
    }

    public function post(){
        $status = new \App\Newsfeed;
        $user = Auth()->user();
        $status->user_id = Auth()->user()->id;
        $status->status = request('status');
        $status->image = request('image');

        // $data = request();

        // if(request()->hasFile('image')){
        //     //dd("ok");
        //     request()->validate([
        //         'image' => 'required|file|max:10000'
        //     ]);
        // }

        // if(request()->has('image')){
        //     $status->save([
        //         'image' => request()->image->store('status_image', 'public'),
        //     ]);
        // }

        if(request()->has('image')){
            $status->image = request('image')->store('status_image', 'public');
        }
        if(request()->has('status')){
            $status->status = request('status');
        }

        

        // $status->status = request('status');
        // $status->image = request('image')->store('status_image', 'public');

        if(request()->has('image') || $status->status != ""){
            $status->save();
            session()->flash('success', 'Status added');
            return redirect()->route('newsfeed.index');
        }else{
            session()->flash('failed', 'Can not add empty post');
            return redirect()->route('newsfeed.index');
        }
        

        

        
    }
}
