<?php

namespace App\Http\Controllers\AdminDashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $admin=Admin::find(auth()-id());
        return response()->json([
            'notifications'=>$admin->notifications,
        ]);
    }
    public function unread()
    {
        $admin=Admin::find(auth()-id());
        return response()->json([
            'notifications'=>$admin->unreadNotifications,
        ]);
    }
    public function markReadAll()
    {
        $admin=Admin::find(auth()-id());
//        foreach ($admin->unreadNotifications as $notification) {
//            $notification->markAsRead();
//        }
        $admin->unreadNotifications()->update(['read_at' => now()]);


        return response()->json([
            'message'=>"success",
        ]);
    }
    public function deleteAll()
    {
        $admin=Admin::find(auth()-id());
        $admin->notifications()->delete();
        return response()->json([
            'message'=>"notifications deleted successfully",
        ]);
    }
    public function delete($id)
    {
        $admin=Admin::find(auth()-id());
        DB::table('notifications')->where('id',$id)->delete();

        return response()->json([
            'message'=>"notification deleted successfully",
        ]);
    }
}
