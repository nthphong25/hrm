<?php

namespace App\Http\Controllers;

use App\Models\FormingWHRecordmst;
use App\Models\Inspectionmst;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;


class pdfController extends Controller
{

    public function index()
    {
        return view('index');
    }



    public function showChart(Request $request)
    {
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $qc_dept_code = $request->input('qc_dept_code'); // Lấy qc_dept_code từ request
        $days = [];
        $currentDate = $fromDate;
        $allDataByDayAndDept = []; // Mảng phân loại dữ liệu theo 'day' và 'qc_dept_code'

        $ATQC20 = ['ATQC2001', 'ATQC2002', 'ATQC2003', 'ATQC2004', 'ATQC2005', 'ATQC2006', 'ATQC2007', 'ATQC2008', 'ATQC2009', 'ATQC2010', 'ATQC2011', 'ATQC2012', 'ATQC2013', 'ATQC2014', 'ATQC20_qi_others'];
        $ATQC30 = ['ATQC3001', 'ATQC3002', 'ATQC3003', 'ATQC3004', 'ATQC3005', 'ATQC3006', 'ATQC3007', 'ATQC3008', 'ATQC3009', 'ATQC3010', 'ATQC3011', 'ATQC3012', 'ATQC3013', 'ATQC3014', 'ATQC30_qi_others'];
        $ATQC40 = ['ATQC4001', 'ATQC4002', 'ATQC4003', 'ATQC4004', 'ATQC4005', 'ATQC4006', 'ATQC4007', 'ATQC4008', 'ATQC4009', 'ATQC4010', 'ATQC4011', 'ATQC4012', 'ATQC4013', 'ATQC4014', 'ATQC40_qi_others'];
        $error = ['ATQC2001', 'ATQC2002', 'ATQC2014', 'ATQC4004', 'ATQC4005', 'ATQC3004', 'ATQC2004', 'ATQC4009', 'ATQC2005', 'ATQC3001', 'ATQC3002', 'ATQC3003', 'ATQC3009', 'ATQC3012', 'ATQC3013', 'ATQC4002', 'ATQC4003', 'ATQC4001', 'ATQC2006', 'ATQC2007', 'ATQC2003', 'ATQC2011', 'ATQC3005', 'ATQC2009', 'ATQC2010', 'ATQC3008', 'ATQC4006', 'ATQC2008', 'ATQC4008', 'ATQC2012', 'ATQC2013', 'ATQC3007', 'ATQC3011', 'ATQC3010', 'ATQC3006', 'ATQC4007'];

        $GLs = [
            'GL1' => [
                '07:30-8:30',
                '08:30-9:30',
                '09:30-10:30',
                '10:30-11:30',
                '12:30-13:30',
                '13:30-14:30',
                '14:30-15:30',
                '15:30-16:30',
                '16:30-17:30',
                '17:30-18:30',
            ],
            'GL2' => [
                '07:30-8:30',
                '08:30-9:30',
                '09:30-10:30',
                '10:30-11:30',
                '13:00-14:00',
                '14:00-15:00',
                '15:00-16:00',
                '16:00-17:00',
                '17:00-18:00',
                '18:00-19:00',
                '19:00-20:00',
            ],
            'GL3' => [
                '07:30-08:30',
                '08:30-09:30',
                '09:30-10:30',
                '10:30-11:30',
                '12:30-13:00',
                '13:00-14:00',
                '14:00-15:00',
                '15:00-16:00',
                '16:00-17:00',
                '17:00-18:00',
                '18:00-19:00'
            ],
            'GL4' => [
                '07:00-08:00',
                '08:00-09:00',
                '09:00-10:00',
                '10:00-11:00',
                '11:00-12:00',
                '12:00-13:00',
                '13:00-14:00',
                '14:00-15:00',
                '15:00-16:00',
                '16:00-17:00',
                '17:00-18:00',
                '18:00-19:00',
            ],
            // Thêm GL3, GL2, GL5 hoặc bất kỳ giá trị GL nào khác theo cùng mẫu
        ];

        $selectedGL = $request->input('GL'); // Đọc giá trị GL từ request
        $timeline = [];
        // Xác định timeline dựa trên giá trị GL trong request
        if (array_key_exists($selectedGL, $GLs)) {
            $timeline = $GLs[$selectedGL];
        }
        while ($currentDate <= $toDate) {
            $days[] = $currentDate;

            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }

        // Lặp qua các ngày
        foreach ($days as $day) {
            $data = DB::table('ta_inspectionmst')
                ->where('cofactory_code', $selectedGL)
                ->whereDate('qip_date', $day)
                ->where('qc_dept_code', $qc_dept_code)
                ->get();

            if ($data->count() > 0) {
                $allDataByDayAndDept[$day] = $data;
            }
        }
        // dd($allDataByDayAndDept);
        foreach ($allDataByDayAndDept as $day => $datas) {

            foreach ($datas as $index => $items) {

                    $logData20 = json_decode($items->ATQC20_time_log, true);
                    $logData30 = json_decode($items->ATQC30_time_log, true);
                    $logData40 = json_decode($items->ATQC40_time_log, true);

                    $day = str_replace('-', '_', $day);
                    $logData20new[$day] = $logData20;
                    $logData30new[$day] = $logData30;
                    $logData40new[$day] = $logData40;
                    $logData20new1[] = $logData20new;
                    $logData30new1[] = $logData30new;
                    $logData40new1[] = $logData40new;
            }
        }
        // dd($logData20new1);

        return view('show', compact('allDataByDayAndDept', 'timeline', 'logData20new1', 'logData30new1', 'logData40new1', 'selectedGL'));
    }
}
