<?php
// dd($data);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title> PDF</title>
    <style>
        .chart-container {
            display: flex;
            /* Sử dụng flexbox layout để sắp xếp theo hàng ngang */
            justify-content: space-between;
            /* Cách đều hai biểu đồ */
            width: 100%;
        }

        .custom-table {
            font-family: Arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }

        .custom-table th,
        .custom-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .custom-table th {
            background-color: #f2f2f2;
        }

        .custom-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }


        .myChartDiv {
            /* margin: 10px; */
        }

        .total {
            background-color: aqua;
        }
    </style>
</head>

<body>
    <script src="{{ asset('js/app.js') }}" type="text/js"></script>
    <div class="flex justify-center items-center">
    </div>

    <div class="flex justify-center items-center">
        <form action="/show" method="post">
            @csrf
            <input hidden type="text" name="image" id="image" value="">
            <input hidden type="text" name="image1" id="image1" value="">
            từ ngày <input class="border border-black" type="date" name="fromDate" id="" required
                value="2023-10-10">
            đến ngày <input class="border border-black" type="date" name="toDate" id="" required
                value="2023-10-18">
            <select name="qc_dept_code" id="qc_dept_code">
                <option value="A">A</option>
                <option value="C">C</option>
                <option value="D">D</option>
                <option value="E">E</option>
            </select>
            <select name="GL" id="GL">
                <option value="GL1">GL1</option>
                <option value="GL2">GL2</option>
                <option value="GL3">GL3</option>
                <option value="GL4">GL4</option>
            </select>



            <button onclick="downloadPDF()" type="submit" class="mt-20 bg-rose-500 px-4 py-2 rounded-md">show</button>
        </form>

    </div>






    <script>
        const imageCollection = [];
    </script>


    @if (isset($allDataByDayAndDept))
        @foreach ($allDataByDayAndDept as $day => $datas)
            @php

                $day = str_replace('-', '_', $day);
            @endphp
            <h2>Data for {{ $day }}</h2>

            <table class="custom-table" id="customers_{{ $day }}">

                <tr>
                    <th>#NO</th>
                    <th>INSPECTED DATE</th>
                    <th>HOURS</th>
                    <th>BRAND</th>
                    <th>LINE NAME</th>
                    <th>TQC NAME</th>
                    <th>INSPECTED QTY</th>
                    <th>PASSED QTY</th>
                    <th>TQC1 DEFECT QTY</th>
                    <th>TQC2 DEFECT QTY</th>
                    <th>TQC3 DEFECT QTY</th>
                    <th>TQC1 DEFECT RATE % </th>
                    <th>TQC2 DEFECT RATE % </th>
                    <th>TQC3 DEFECT RATE % </th>
                </tr>
                @php
                    $totalTotal = 0;
                    $totalLogValue = 0;
                    $totalDefectQtyATQC20 = 0;
                    $totalDefectQtyATQC30 = 0;
                    $totalDefectQtyATQC40 = 0;
                    $totalPercentATQC20 = 0;
                    $totalPercentATQC30 = 0;
                    $totalPercentATQC40 = 0;
                    $timeline = ['07:00-08:00', '08:00-09:00', '09:00-10:00', '10:00-11:00', '11:00-12:00', '12:00-13:00', '13:00-14:00', '14:00-15:00', '15:00-16:00', '16:00-17:00', '17:00-18:00', '18:00-19:00'];
                    $error = ['ATQC2001', 'ATQC2002', 'ATQC2014', 'ATQC4004', 'ATQC4005', 'ATQC3004', 'ATQC2004', 'ATQC4009', 'ATQC2005', 'ATQC3001', 'ATQC3002', 'ATQC3003', 'ATQC3009', 'ATQC3012', 'ATQC3013', 'ATQC4002', 'ATQC4003', 'ATQC4001', 'ATQC2006', 'ATQC2007', 'ATQC2003', 'ATQC2011', 'ATQC3005', 'ATQC2009', 'ATQC2010', 'ATQC3008', 'ATQC4006', 'ATQC2008', 'ATQC4008', 'ATQC2012', 'ATQC2013', 'ATQC3007', 'ATQC3011', 'ATQC3010', 'ATQC3006', 'ATQC4007'];
                    $count = count($timeline);
                @endphp
                @foreach ($datas as $index => $item)
                    <tr>
                        <td>{{ $index }}</td>
                        <td>{{ date('j-M', strtotime($item->qip_date)) }}</td>
                        <td>{{ isset($timeline[$index]) ? $timeline[$index] : '' }}</td>
                        <td>{{ $item->custbrand_id }}</td>
                        <td>{{ $item->qc_dept_code }}</td>
                        <td>{{ $item->qc_dept_code }}</td>
                        @php
                            $logData = json_decode($item->FRQC10_log, true);
                            $logData1 = json_decode($item->ATQC_time_log, true);
                            $logValue = $logData['log'][$timeline[$index]] ?? null;
                            $log1 = $logData1['log'][$error][$timeline[$index]] ?? null;
                            $maxDefect = max($item->defect_qty_ATQC20, $item->defect_qty_ATQC30, $item->defect_qty_ATQC40);
                            $total = $maxDefect != 0 ? $logValue + $maxDefect : 0;

                            // Tính phần trăm chỉ khi $total khác 0
                            $percentATQC20 = round($total != 0 ? ($item->defect_qty_ATQC20 / $total) * 100 : 0);
                            $percentATQC30 = round($total != 0 ? ($item->defect_qty_ATQC30 / $total) * 100 : 0);
                            $percentATQC40 = round($total != 0 ? ($item->defect_qty_ATQC40 / $total) * 100 : 0);
                        @endphp
                        <td>{{ $total }}</td>
                        <td>
                            {{ $logValue }}
                        </td>
                        <td>{{ $log1 }}</td>
                        <td>{{ $item->defect_qty_ATQC30 }}</td>
                        <td>{{ $item->defect_qty_ATQC40 }}</td>
                        <td> {{ $percentATQC20 }}%</td>
                        <td>{{ $percentATQC30 }}%</td>
                        <td> {{ $percentATQC40 }}%</td>

                    </tr>
                    @php
                        // Tính tổng cho các biến
                        $totalTotal += $total;
                        $totalLogValue += $logValue;
                        $totalDefectQtyATQC20 += $item->defect_qty_ATQC20;
                        $totalDefectQtyATQC30 += $item->defect_qty_ATQC30;
                        $totalDefectQtyATQC40 += $item->defect_qty_ATQC40;
                        $totalPercentATQC20 += $percentATQC20;
                        $totalPercentATQC30 += $percentATQC30;
                        $totalPercentATQC40 += $percentATQC40;
                    @endphp
                @endforeach
                <tr>
                    <td>Total</td>
                    <td>{{ date('j-M', strtotime($item->qip_date)) }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ $totalTotal }}</td>
                    <td>{{ $totalLogValue }}</td>
                    <td>{{ $totalDefectQtyATQC20 }}</td>
                    <td>{{ $totalDefectQtyATQC30 }}</td>
                    <td>{{ $totalDefectQtyATQC40 }}</td>
                    <td>{{ $totalPercentATQC20 }}%</td>
                    <td>{{ $totalPercentATQC30 }}%</td>
                    <td>{{ $totalPercentATQC40 }}%</td>
                </tr>
            </table>
            <div id="chart-{{ $day }}" class="chart-container">
                <div class="myChartDiv">
                    <canvas id="total_{{ $day }}" style="width: 1200px; height: 700px;"></canvas>
                </div>
                <div class="myChartDiv">
                    <canvas id="myChart_{{ $day }}" style="width: 600px; height: 700px;"></canvas>
                </div>
            </div>

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
    @endif
    <br>
</body>

</html>
