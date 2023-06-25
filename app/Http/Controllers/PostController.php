<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Admin;
use App\Models\Post;
use App\Models\PostPhoto;
use App\Notifications\AdminPost;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PostController extends Controller
{
public function store(StorePostRequest $request){

    try {
        DB::beginTransaction();
        $data=$request->except('photos');
        $data['worker_id']=auth()->guard('worker')->id();
        $post=Post::create($data);
        if ($request->hasFile('photos')){
            foreach ($request->file('photos') as $photo){
                $postPhoto=new PostPhoto();
                $postPhoto->post_id=$post->id;
                $postPhoto->photo=$photo->store('posts');
                $postPhoto->save();
            }
        }
        $admin=Admin::get();
        Notification::send($admin, new AdminPost($post,auth()->guard('worker')->user()));
        DB::commit();
        return response()->json([
            "message"=>"post has been created successfully"]);
    }
    catch (Exception $e){
        DB::rollBack();
return $e->getMessage();
    }
}
}
