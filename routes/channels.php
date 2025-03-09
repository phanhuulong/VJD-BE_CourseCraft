<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::private('course.comments.{courseId}', function ($user, $courseId) {
    //Kiểm tra quyền truy cập của người dùng vào khóa học.
    return true;
});
