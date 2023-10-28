@php
    $count = count($timeline);
    $ATQC20 = ['ATQC2001', 'ATQC2002', 'ATQC2003', 'ATQC2004', 'ATQC2005', 'ATQC2006', 'ATQC2007', 'ATQC2008', 'ATQC2009', 'ATQC2010', 'ATQC2011', 'ATQC2012', 'ATQC2013', 'ATQC2014', 'ATQC20_qi_others'];
    $ATQC30 = ['ATQC3001', 'ATQC3002', 'ATQC3003', 'ATQC3004', 'ATQC3005', 'ATQC3006', 'ATQC3007', 'ATQC3008', 'ATQC3009', 'ATQC3010', 'ATQC3011', 'ATQC3012', 'ATQC3013', 'ATQC30_qi_others'];
    $ATQC40 = ['ATQC4001', 'ATQC4002', 'ATQC4003', 'ATQC4004', 'ATQC4005', 'ATQC4006', 'ATQC4007', 'ATQC4008', 'ATQC4009', 'ATQC40_qi_others'];
    $error = ['ATQC2001', 'ATQC2002', 'ATQC2014', 'ATQC4004', 'ATQC4005', 'ATQC3004', 'ATQC2004', 'ATQC4009', 'ATQC2005', 'ATQC3001', 'ATQC3002', 'ATQC3003', 'ATQC3009', 'ATQC3012', 'ATQC3013', 'ATQC4002', 'ATQC4003', 'ATQC4001', 'ATQC2006', 'ATQC2007', 'ATQC2003', 'ATQC2011', 'ATQC3005', 'ATQC2009', 'ATQC2010', 'ATQC3008', 'ATQC4006', 'ATQC2008', 'ATQC4008', 'ATQC2012', 'ATQC2013', 'ATQC3007', 'ATQC3011', 'ATQC3010', 'ATQC3006', 'ATQC4007'];
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title> PDF Example</title>

</head>

<body>
    <script src="{{ asset('js/app.js') }}" type="text/js"></script>
    <script>
        const imageCollection = [];
    </script>
    <div class="justify-center items-center text-center mt-8">
        @if (isset($allDataByDayAndDept))
            @foreach ($allDataByDayAndDept as $day => $datas)
                @php
                    $h = 0;
                    $day = str_replace('-', '_', $day);
                @endphp
                @foreach ($datas as $index => $items)

                    @if ($selectedGL == 'GL4')
                        @php
                            $top3MaxValues = [];
                            foreach ($ATQC20 as $label) {
                                $maxValue = 0;

                                // Duyệt qua dữ liệu và tìm giá trị lớn nhất cho nhãn $label
                                foreach ($allDataByDayAndDept as $day => $datas) {
                                    $day = str_replace('-', '_', $day);
                                    foreach ($datas as $items) {
                                        $value = $items->$label;

                                        if ($value >= $maxValue) {
                                            $maxValue = $value;
                                        }
                                    }
                                }

                                $top3MaxValues[] = ['label' => $label, 'value' => $maxValue];
                            }

                            foreach ($ATQC30 as $label) {
                                $maxValue = 0;

                                // Duyệt qua dữ liệu và tìm giá trị lớn nhất cho nhãn $label
                                foreach ($allDataByDayAndDept as $day => $datas) {
                                    $day = str_replace('-', '_', $day);
                                    foreach ($datas as $items) {
                                        $value = $items->$label;

                                        if ($value >= $maxValue) {
                                            $maxValue = $value;
                                        }
                                    }
                                }

                                $top3MaxValues[] = ['label' => $label, 'value' => $maxValue];
                            }

                            foreach ($ATQC40 as $label) {
                                $maxValue = 0;

                                // Duyệt qua dữ liệu và tìm giá trị lớn nhất cho nhãn $label
                                foreach ($allDataByDayAndDept as $day => $datas) {
                                    $day = str_replace('-', '_', $day);
                                    foreach ($datas as $items) {
                                        $value = $items->$label;

                                        if ($value >= $maxValue) {
                                            $maxValue = $value;
                                        }
                                    }
                                }

                                $top3MaxValues[] = ['label' => $label, 'value' => $maxValue];
                            }

                            // Sắp xếp mảng $top3MaxValues theo giá trị giảm dần
                            usort($top3MaxValues, function ($a, $b) {
                                return $b['value'] - $a['value'];
                            });

                            // Lấy 3 giá trị lớn nhất từ mảng $top3MaxValues
                            $top3MaxValues = array_slice($top3MaxValues, 0, 3);

                                // dd($top3MaxValues);
                        @endphp
                    @else
                        @php
                            $maxValues = [];
                            $timeSlot = '07:30-08:30';
                            foreach ($error as $label) {
                                $maxValue = 0;

                                $logData20 = json_decode($items->ATQC20_time_log, true);
                                $logData30 = json_decode($items->ATQC30_time_log, true);
                                $logData40 = json_decode($items->ATQC40_time_log, true);

                                $value20 = $logData20[$label][$timeSlot] ?? 0;
                                $value30 = $logData30[$label][$timeSlot] ?? 0;
                                $value40 = $logData40[$label][$timeSlot] ?? 0;

                                $totalValue = $value20 + $value30 + $value40;

                                if ($totalValue >= $maxValue) {
                                    $maxValue = $totalValue;
                                    $maxLabel = $label;
                                }

                                $maxValues[] = [
                                    'label' => $maxLabel,
                                    'value' => $maxValue,
                                ];
                            }

                            // Lặp qua các label không có trong index_error
                            $allLabels = array_merge($ATQC20, $ATQC30, $ATQC40);
                            $nonErrorLabels = array_diff($allLabels, $error);

                            foreach ($nonErrorLabels as $label) {
                                $maxValue = 0;

                                $logData20 = json_decode($items->ATQC20_time_log, true);
                                $logData30 = json_decode($items->ATQC30_time_log, true);
                                $logData40 = json_decode($items->ATQC40_time_log, true);

                                $value20 = $logData20[$label][$timeSlot] ?? 0;
                                $value30 = $logData30[$label][$timeSlot] ?? 0;
                                $value40 = $logData40[$label][$timeSlot] ?? 0;

                                $totalValue = $value20 + $value30 + $value40;

                                if ($totalValue >= $maxValue) {
                                    $maxValue = $totalValue;
                                    $maxLabel = $label;
                                }

                                $maxValues[] = [
                                    'label' => $maxLabel,
                                    'value' => $maxValue,
                                ];
                            }

                            // Sắp xếp mảng $maxValues theo giá trị giảm dần
                            usort($maxValues, function ($a, $b) {
                                return $b['value'] - $a['value'];
                            });

                            // Lấy 3 giá trị lớn nhất từ mảng $maxValues
                            $top3MaxValues = array_slice($maxValues, 0, 3);
                        @endphp
                    @endif
                    
                    <table class="border border-black mx-auto" id="customers_{{ $day }}_{{ $h }}">
                        <thead>
                            <tr>
                                <th class="w-10 px-2 py-1 bg-gray-400 border border-black">#id</th>
                                <th class="w-10 text-xs	 px-0 py-0 bg-gray-400 border border-black">#NO</th>
                                <th class="w-16 text-xs	 px-0 py-0 bg-gray-400 border border-black">INSPECTED DATE</th>
                                <th class="w-30 text-xs	 px-0 py-0 bg-gray-400 border border-black">HOURS</th>
                                <th class="w-16 text-xs	 px-0 py-0 bg-gray-400 border border-black">BRAND</th>
                                <th class="w-16 text-xs	 px-0 py-0 bg-gray-400 border border-black">LINE NAME</th>
                                <th class="w-16 text-xs	 px-0 py-0 bg-gray-400 border border-black">TQC NAME</th>
                                <th class="w-16 text-xs	 px-0 py-0 bg-gray-400 border border-black">DEFECT QTY</th>
                                @foreach ($top3MaxValues as $d)
                                    <th class="w-16 text-xs px-0 py-0 bg-gray-400 border border-black">
                                        {{ $d['label'] }}</th>
                                    <th class="w-16 text-xs	px-0 py-0 bg-gray-400 border border-black">
                                        {{ $d['label'] }} %
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($timeline as $index => $time)
                                @php
                                    $data = [
                                        'day' => $day,
                                        'ATQC20' => $logData20new1[1][$day],
                                        'ATQC30' => $logData30new1[1][$day],
                                        'ATQC40' => $logData40new1[1][$day],
                                    ];
                                    // dd($logData20new);
                                @endphp

                                <tr class="border border-black">
                                    <td class="px-4 py-2 border-r border-black">{{ $items->keyid }}</td>
                                    <td class="text-xs px-0 py-0 border-r border-black"></td>
                                    <td class="text-xs px-0 py-0 border-r border-black">
                                        {{ date('j-M', strtotime($items->qip_date)) }}
                                    </td>
                                    <td class="text-xs px-0 py-0 border-r border-black">{{ $time }}</td>
                                    <td class="text-xs px-0 py-0 border-r border-black">{{ $items->custbrand_id }}</td>
                                    <td class="text-xs px-0 py-0 border-r border-black">{{ $items->qc_dept_code }}</td>
                                    <td class="text-xs px-0 py-0 border-r border-black">{{ $items->qc_dept_code }}</td>
                                    <td class="text-xs px-0 py-0 border-r border-black"></td>
                                    @php
                                        $totalColumn1Array = []; // Mảng để lưu tổng cột 1 cho mỗi cột
                                        $totalColumn2Array = []; // Mảng để lưu tổng cột 2 cho mỗi cột
                                        $totalColumn3Array = []; // Mảng để lưu tổng cột 3 cho mỗi cột
                                    @endphp
                                    @foreach ($top3MaxValues as $d)

                                        @php
                                            $totalColumn1 = 0; // Khởi tạo biến tổng cột 1
                                            $totalColumn2 = 0; // Khởi tạo biến tổng cột 2
                                            $totalColumn3 = 0; // Khởi tạo biến tổng cột 3
                                            $label = $d['label'];
                                            $prefix = substr($label, 0, 6);
                                            // dd($data);
                                        @endphp
                                        <td class="text-xs px-0 py-0 border-r border-black">
                                            {{ $data[$prefix][$label][$time] }}
                                        </td>
                                        <td class="text-xs px-0 py-0 border-r border-black"></td>
                                    @endforeach

                                    @foreach ($top3MaxValues as $d)
                                        @php
                                            $totalColumn1 = 0; // Khởi tạo biến tổng cột 1
                                            $totalColumn2 = 0; // Khởi tạo biến tổng cột 2
                                            $totalColumn3 = 0; // Khởi tạo biến tổng cột 3
                                            $label = $d['label'];
                                            $prefix = substr($label, 0, 6);
                                        @endphp
                                        @foreach ($timeline as $time)
                                            @php
                                                // Tính tổng cho cột 1, 2 và 3 sau mỗi lần lặp
                                                $totalColumn1 += $data[$prefix][$label][$time]; // Tổng cột 1
                                            @endphp
                                        @endforeach
                                        @php
                                            // Lưu giá trị tổng cột cho mỗi cột vào mảng tương ứng
                                            $totalColumn1Array[] = $totalColumn1;
                                        @endphp
                                    @endforeach
                                </tr>
                            @endforeach

                            <tr class="border border-black">
                                <td class="px-4 py-2 border-r border-black"></td>
                                <td class="text-xs px-0 py-0 border-r border-black">Total</td>
                                <td class="text-xs px-0 py-0 border-r border-black"></td>
                                <td class="text-xs px-0 py-0 border-r border-black"></td>
                                <td class="text-xs px-0 py-0 border-r border-black"></td>
                                <td class="text-xs px-0 py-0 border-r border-black"></td>
                                <td class="text-xs px-0 py-0 border-r border-black"></td>
                                <td class="text-xs px-0 py-0 border-r border-black"></td>
                                @foreach ($totalColumn1Array as $total)
                                    <td class="text-xs px-0 py-0 border-r border-black">{{ $total }}</td>
                                    <td class="text-xs px-0 py-0 border-r border-black"></td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                    {{-- show chart --}}
                    <div class="text-center mt-8 ">
                        <div id="chart-{{ $day }}" class="flex justify-center items-center">
                            <div class="mx-1">
                                <canvas class="w-[450px] h-[400px]"
                                    id="total_{{ $day }}_{{ $h }}"></canvas>
                            </div>
                            <div class="mx-1">
                                <canvas class="w-[300px]"
                                    id="myChart_{{ $day }}_{{ $h }}"></canvas>
                            </div>
                        </div>
                    </div>

                    <br>
                    <br>
                    {{-- draw chart --}}
                    <script>
                        // Khai báo biến chart TOTAL
                        const table_{{ $day }}_{{ $h }} = document.getElementById(
                            'customers_{{ $day }}_{{ $h }}');
                        const labels1_{{ $day }}_{{ $h }} = [];

                        const totalTQC1_{{ $day }}_{{ $h }} = [];
                        const totalTQC2_{{ $day }}_{{ $h }} = [];
                        const totalTQC3_{{ $day }}_{{ $h }} = [];
                        const date_{{ $day }}_{{ $h }} = [];
                        const numFooterRows_{{ $day }}_{{ $h }} = 1; // Số hàng ở phần cuối
                        const totalRows_{{ $day }}_{{ $h }} = table_{{ $day }}_{{ $h }}.rows
                            .length;
                        const startIndex_{{ $day }}_{{ $h }} = totalRows_{{ $day }}_{{ $h }} -
                            numFooterRows_{{ $day }}_{{ $h }};

                        // Khai báo biến chart1 với tên duy nhất dựa trên $day
                        const labels_{{ $day }}_{{ $h }} = [];
                        const dataTQC1_{{ $day }}_{{ $h }} = [];
                        const dataTQC2_{{ $day }}_{{ $h }} = [];
                        const dataTQC3_{{ $day }}_{{ $h }} = [];
                        const dataTarget_{{ $day }}_{{ $h }} = [];


                        // Lấy dữ liệu cho biểu đồ 1
                        for (let i = 1; i < (table_{{ $day }}_{{ $h }}.rows.length - 1); i++) {
                            const row = table_{{ $day }}_{{ $h }}.rows[i];


                            labels_{{ $day }}_{{ $h }}.push(row.cells[3].textContent);
                            dataTQC1_{{ $day }}_{{ $h }}.push(parseInt(row.cells[8].textContent));
                            dataTQC2_{{ $day }}_{{ $h }}.push(parseInt(row.cells[10].textContent));
                            dataTQC3_{{ $day }}_{{ $h }}.push(parseInt(row.cells[12].textContent));
                            dataTarget_{{ $day }}_{{ $h }}.push(1);
                        }

                        // Lấy dữ liệu cho biểu đồ TOTAL
                        for (let i = startIndex_{{ $day }}_{{ $h }}; i <
                            totalRows_{{ $day }}_{{ $h }}; i++) {
                            const row = table_{{ $day }}_{{ $h }}.rows[i];
                            labels1_{{ $day }}_{{ $h }}.push(row.cells[1].textContent);
                            totalTQC1_{{ $day }}_{{ $h }}.push(parseInt(row.cells[8].textContent));
                            totalTQC2_{{ $day }}_{{ $h }}.push(parseInt(row.cells[10].textContent));
                            totalTQC3_{{ $day }}_{{ $h }}.push(parseInt(row.cells[12].textContent));

                        }




                        // Vẽ biểu đồ TOTAL
                        const ctx1_{{ $day }}_{{ $h }} = document.getElementById(
                            'myChart_{{ $day }}_{{ $h }}');
                        const myChart1_{{ $day }}_{{ $h }} = new Chart(
                            ctx1_{{ $day }}_{{ $h }}, {
                                type: 'bar',
                                data: {
                                    labels: labels1_{{ $day }}_{{ $h }},
                                    datasets: [{
                                            type: 'bar',
                                            label: 'ERROR1',
                                            data: totalTQC1_{{ $day }}_{{ $h }},
                                            borderColor: 'rgb(255, 99, 132)',
                                            backgroundColor: 'rgba(68, 114, 196, 0.7)'
                                        },
                                        {
                                            type: 'bar',
                                            label: 'ERROR2',
                                            data: totalTQC2_{{ $day }}_{{ $h }},
                                            borderColor: 'rgb(255, 99, 132)',
                                            backgroundColor: 'rgba(237, 125, 49, 0.7)'
                                        },
                                        {
                                            type: 'bar',
                                            label: 'ERROR3',
                                            data: totalTQC3_{{ $day }}_{{ $h }},
                                            borderColor: 'rgba(165, 165, 165, 0.7)',
                                        }
                                    ]
                                },
                                options: {
                                    responsive: false,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    },
                                }
                            });

                        // Vẽ biểu đồ 1
                        const ctx_{{ $day }}_{{ $h }} = document.getElementById(
                            'total_{{ $day }}_{{ $h }}');
                        const myChart_{{ $day }}_{{ $h }} = new Chart(
                            ctx_{{ $day }}_{{ $h }}, {
                                type: 'bar',
                                data: {
                                    labels: labels_{{ $day }}_{{ $h }},
                                    datasets: [{
                                            type: 'bar',
                                            label: 'ERROR1',
                                            data: dataTQC1_{{ $day }}_{{ $h }},
                                            borderColor: 'rgb(255, 99, 132)',
                                            backgroundColor: 'rgba(68, 114, 196, 0.7)',
                                        },
                                        {
                                            type: 'bar',
                                            label: 'ERROR2',
                                            data: dataTQC2_{{ $day }}_{{ $h }},
                                            borderColor: 'rgb(255, 99, 132)',
                                            backgroundColor: 'rgba(237, 125, 49, 0.7)',
                                        },
                                        {
                                            type: 'bar',
                                            label: 'ERROR3',
                                            data: dataTQC3_{{ $day }}_{{ $h }},
                                            borderColor: 'rgb(255, 99, 132)',
                                            backgroundColor: 'rgba(165, 165, 165, 0.7)',
                                        },
                                        {
                                            type: 'line',
                                            label: 'Target',
                                            data: dataTarget_{{ $day }}_{{ $h }},
                                            fill: false,
                                            backgroundColor: 'rgba(240, 5, 5)',
                                            borderColor: 'rgba(240, 5, 5)',
                                        },
                                    ],
                                },
                                options: {
                                    responsive: false,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                        },
                                    },
                                },
                            });
                    </script>
                    @php
                        $h++;
                    @endphp
                @endforeach
            @endforeach
        @endif
    </div>


</body>

</html>
