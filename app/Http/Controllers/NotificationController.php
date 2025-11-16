<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{


    public function getNotifications($userId)
    {
        // Fetch unread notifications for the user, limited to 5
        $notifications = Notification::where('user_id', $userId)
            ->whereNull('read_at')  // Only unread notifications
            ->orderBy('created_at', 'desc')  // Sort by the newest first
            ->take(5)  // Limit to 5 notifications
            ->get()
            ->map(function ($notification) {
                // Add time ago formatting using Carbon
                return [
                    'id' => $notification->id,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'time_ago' => Carbon::parse($notification->created_at)->diffForHumans(),
                ];
            });

        return response()->json(['notifications' => $notifications]);
    }

    public function markAsRead(Request $request)
    {
        // Mark notifications as read for the given user
        Notification::where('user_id', $request->user_id)
            ->whereNull('read_at')  // Only unread notifications
            ->update(['read_at' => now()]);  // Mark them as read

        return response()->json(['message' => 'Notifications marked as read']);
    }



}
