@php
    $totalTotal = 0;
    $totalLogValue = 0;
    $totalDefectQtyATQC20 = 0;
    $totalDefectQtyATQC30 = 0;
    $totalDefectQtyATQC40 = 0;
    $totalPercentATQC20 = 0;
    $totalPercentATQC30 = 0;
    $totalPercentATQC40 = 0;

    $count = count($timeline);
    $ATQC20 = ['ATQC2001', 'ATQC2002', 'ATQC2003', 'ATQC2004', 'ATQC2005', 'ATQC2006', 'ATQC2007', 'ATQC2008', 'ATQC2009', 'ATQC2010', 'ATQC2011', 'ATQC2012', 'ATQC2013', 'ATQC2014', 'ATQC20_qi_others'];
    $ATQC30 = ['ATQC3001', 'ATQC3002', 'ATQC3003', 'ATQC3004', 'ATQC3005', 'ATQC3006', 'ATQC3007', 'ATQC3008', 'ATQC3009', 'ATQC3010', 'ATQC3011', 'ATQC3012', 'ATQC3013', 'ATQC3014', 'ATQC30_qi_others'];
    $ATQC40 = ['ATQC4001', 'ATQC4002', 'ATQC4003', 'ATQC4004', 'ATQC4005', 'ATQC4006', 'ATQC4007', 'ATQC4008', 'ATQC4009', 'ATQC4010', 'ATQC4011', 'ATQC4012', 'ATQC4013', 'ATQC4014', 'ATQC40_qi_others'];
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
    <title>Laravel 7 PDF Example</title>
    {{-- <style>
        .chart-container {
            display: flex;
            /* Sử dụng flexbox layout để sắp xếp theo hàng ngang */
            justify-content: center;
            /* Cách đều hai biểu đồ */
            width: 100%;


        }

        .custom-table {
            font-family: Arial, sans-serif;
            border-collapse: collapse;
            width: 1400px;
            margin-left: 100px;

            text-align: center;
        }

        .custom-table th,
        .custom-table td {
            border: 1px solid #ddd;
        }

        .custom-table th {
            background-color: #f2f2f2;
        }

        .custom-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }


        .myChartDiv {
            align-items: center;
        }

        .total {
            background-color: aqua;
        }
    </style> --}}
</head>

<body>
    <script src="{{ asset('js/app.js') }}" type="text/js"></script>
    <script>
        const imageCollection = [];
    </script>

    <div class="justify-center items-center text-center mt-8">
        {{-- @dd($allDataByDayAndDept); --}}
        @if (isset($allDataByDayAndDept))
            @foreach ($allDataByDayAndDept as $day => $datas)
                @php
                    $day = str_replace('-', '_', $day);
                @endphp

                @foreach ($datas as $index => $items)
                    @php
                        $maxValues = [];

                        $timeSlot = '07:00-08:00';

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
                    <table class="border border-black mx-auto" id="customers_{{ $day }}">
                        <thead>
                            <tr>
                                <th class="w-10 bg-gray-400 px-4 py-2 border-r border-black">#id</th>
                                <th class="w-10 bg-gray-400 px-4 py-2 border-r border-black">#NO</th>
                                <th class="w-16 bg-gray-400 px-4 py-2 border-r border-black">INSPECTED DATE</th>
                                <th class="w-32 bg-gray-400 px-4 py-2 border-r border-black">HOURS</th>
                                <th class="w-16 bg-gray-400 px-4 py-2 border-r border-black">BRAND</th>
                                <th class="w-16 bg-gray-400 px-4 py-2 border-r border-black">LINE NAME</th>
                                <th class="w-16 bg-gray-400 px-4 py-2 border-r border-black">TQC NAME</th>
                                <th class="w-16 bg-gray-400 px-4 py-2 border-r border-black">DEFECT QTY</th>
                                @foreach ($top3MaxValues as $d)
                                    <th class="w-16 bg-gray-400 px-4 py-2 border-r border-black">{{ $d['label'] }}
                                    </th>
                                    <th class="w-16 bg-gray-400 px-4 py-2 border-r border-black">{{ $d['label'] }} %
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                        @foreach ($top3MaxValues as $h)
                    {{-- @dd($h); --}}
                            @php
                              $label = $h['label'];
                                $prefix = substr($label, 0, 6);
                                $i = 0;
                                $data = [
                                    'ATQC20' => $logData20new[$day],
                                    'ATQC30' => $logData30new[$day],
                                    'ATQC40' => $logData40new[$day],
                                ];
                            @endphp

                            @foreach ($data[$prefix][$label] as $index => $d)
                                <tr class="border border-black">
                                    <td class="px-4 py-2 border-r border-black">{{ $items->keyid }}</td>
                                    <td class="px-4 py-2 border-r border-black">

                                    </td>
                                    <td class="px-4 py-2 border-r border-black">
                                        {{ date('j-M', strtotime($items->qip_date)) }}
                                    </td>
                                    <td class="px-4 py-2 border-r border-black">{{ $index }}</td>
                                    <td class="px-4 py-2 border-r border-black">{{ $items->custbrand_id }}</td>
                                    <td class="px-4 py-2 border-r border-black">{{ $items->qc_dept_code }}</td>
                                    <td class="px-4 py-2 border-r border-black">{{ $items->qc_dept_code }}</td>
                                    <td class="px-4 py-2 border-r border-black"></td>
                                    <td name="ATQC20" class="px-4 py-2 border-r border-black">
                                        {{ $d }}
                                    </td>
                                    <td name="ATQC20 %" class="px-4 py-2 border-r border-black"></td>
                                    <td name="ATQC30" class="px-4 py-2 border-r border-black">

                                    </td>
                                    <td name="ATQC30 %" class="px-4 py-2 border-r border-black"></td>
                                    <td name="ATQC40" class="px-4 py-2 border-r border-black">

                                    </td>
                                    <td name="ATQC40 %" class="px-4 py-2 border-r border-black"></td>
                                    @php
                                        $i++;
                                    @endphp
                                </tr>
                            @endforeach
                        </tbody>
    @endforeach
                    </table>
                    <div class="text-center mt-8 ">
                        <div id="chart-{{ $day }}" class="flex justify-center items-center">
                            <div class="mx-4">
                                <canvas class="w-[800px]" id="total_{{ $day }}"></canvas>
                            </div>
                            <div class="mx-4">
                                <canvas class="w-[600px]" id="myChart_{{ $day }}"></canvas>
                            </div>
                        </div>
                    </div>

                    <br>
                    <br>

                    <script>
                        // Khai báo biến chart TOTAL
                        const table_{{ $day }} = document.getElementById('customers_{{ $day }}');
                        const labels1_{{ $day }} = [];

                        const totalTQC1_{{ $day }} = [];
                        const totalTQC2_{{ $day }} = [];
                        const totalTQC3_{{ $day }} = [];
                        const date_{{ $day }} = [];
                        const numFooterRows_{{ $day }} = 1; // Số hàng ở phần cuối
                        const totalRows_{{ $day }} = table_{{ $day }}.rows.length;
                        const startIndex_{{ $day }} = totalRows_{{ $day }} - numFooterRows_{{ $day }};

                        // Khai báo biến chart1 với tên duy nhất dựa trên $day
                        const labels_{{ $day }} = [];
                        const dataTQC1_{{ $day }} = [];
                        const dataTQC2_{{ $day }} = [];
                        const dataTQC3_{{ $day }} = [];
                        const dataTarget_{{ $day }} = [];


                        // Lấy dữ liệu cho biểu đồ 1
                        for (let i = 1; i < (table_{{ $day }}.rows.length - 1); i++) {
                            const row = table_{{ $day }}.rows[i];
                            labels_{{ $day }}.push(row.cells[2].textContent);
                            dataTQC1_{{ $day }}.push(parseInt(row.cells[11].textContent));
                            dataTQC2_{{ $day }}.push(parseInt(row.cells[12].textContent));
                            dataTQC3_{{ $day }}.push(parseInt(row.cells[13].textContent));
                            dataTarget_{{ $day }}.push(1);
                        }

                        // Lấy dữ liệu cho biểu đồ TOTAL
                        for (let i = startIndex_{{ $day }}; i < totalRows_{{ $day }}; i++) {
                            const row = table_{{ $day }}.rows[i];
                            labels1_{{ $day }}.push(row.cells[1].textContent);
                            totalTQC1_{{ $day }}.push(parseInt(row.cells[11].textContent));
                            totalTQC2_{{ $day }}.push(parseInt(row.cells[12].textContent));
                            totalTQC3_{{ $day }}.push(parseInt(row.cells[13].textContent));

                        }

                        // Vẽ biểu đồ TOTAL
                        const ctx1_{{ $day }} = document.getElementById('myChart_{{ $day }}');
                        const myChart1_{{ $day }} = new Chart(ctx1_{{ $day }}, {
                            type: 'bar',
                            data: {
                                labels: labels1_{{ $day }},
                                datasets: [{
                                        type: 'bar',
                                        label: 'TQC1',
                                        data: totalTQC1_{{ $day }},
                                        borderColor: 'rgb(255, 99, 132)',
                                        backgroundColor: 'rgba(68, 114, 196, 0.7)'
                                    },
                                    {
                                        type: 'bar',
                                        label: 'TQC2',
                                        data: totalTQC2_{{ $day }},
                                        borderColor: 'rgb(255, 99, 132)',
                                        backgroundColor: 'rgba(237, 125, 49, 0.7)'
                                    },
                                    {
                                        type: 'bar',
                                        label: 'TQC3',
                                        data: totalTQC3_{{ $day }},
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
                        const ctx_{{ $day }} = document.getElementById('total_{{ $day }}');
                        const myChart_{{ $day }} = new Chart(ctx_{{ $day }}, {
                            type: 'bar',
                            data: {
                                labels: labels_{{ $day }},
                                datasets: [{
                                        type: 'bar',
                                        label: 'TQC1',
                                        data: dataTQC1_{{ $day }},
                                        borderColor: 'rgb(255, 99, 132)',
                                        backgroundColor: 'rgba(68, 114, 196, 0.7)',
                                    },
                                    {
                                        type: 'bar',
                                        label: 'TQC2',
                                        data: dataTQC2_{{ $day }},
                                        borderColor: 'rgb(255, 99, 132)',
                                        backgroundColor: 'rgba(237, 125, 49, 0.7)',
                                    },
                                    {
                                        type: 'bar',
                                        label: 'TQC3',
                                        data: dataTQC3_{{ $day }},
                                        borderColor: 'rgb(255, 99, 132)',
                                        backgroundColor: 'rgba(165, 165, 165, 0.7)',
                                    },
                                    {
                                        type: 'line',
                                        label: 'Target',
                                        data: dataTarget_{{ $day }},
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
                @endforeach
            @endforeach
        @endif
    </div>


</body>

</html>
