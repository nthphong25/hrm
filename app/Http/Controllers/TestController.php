<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function testConnection()
    {
        try {
            // Thử truy vấn đến bảng ts_user
            $users = DB::table('ts_user')->get();

            // In ra dữ liệu hoặc thông báo thành công
            dd($users);
        } catch (\Exception $e) {
            // Nếu có lỗi, in ra thông báo lỗi
            dd($e->getMessage());
        }
    }
}
