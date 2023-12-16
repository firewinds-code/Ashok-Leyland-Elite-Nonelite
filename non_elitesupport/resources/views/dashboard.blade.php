@extends('layouts.masterlayout')
@section('title', 'Dashboard')
@section('bodycontent')
    {{-- <!--<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"> </script>--> --}}
    <script type="text/javascript" src="{{ asset('js/google-chart.js') }}"></script>
    @php
        $current_month = date('m');
        $current_quarter_start = ceil($current_month / 4) * 3 + 1; // get the starting month of the current quarter
        $fromDate = date('Y-m-d', mktime(0, 0, 0, $current_quarter_start, 1, date('Y')));
        $toDate = date('Y-m-d', strtotime($fromDate . ' - 3 month'));
        $currentQuarterDate = "'" . $fromDate . '~' . $toDate . "'";
        $midcontentHeader = 'click';
        $sixmonth = 180;
        $oneYear = 365;
        $twoYear = 730;
        $currentDate = date('Y-m-d', strtotime('now'));
        $twoQuaterDate = date('Y-m-d', strtotime($toDate . ' - 6 month'));
        $fromtwoQuaterDate = "'" . $toDate . '~' . $twoQuaterDate . "'";
        $oneYearDate = date('Y-m-d', strtotime($toDate . ' - 12 month'));
        $fromOneYearDate = "'" . $currentDate . '~' . $toDate . "'";
        $twoYearDate = date('Y-m-d', strtotime($toDate . ' - 24 month'));
        $fromTwoYearDate = "'" . $toDate . '~' . $twoYearDate . "'";
    @endphp
    <script type="text/javascript">
        //function pieChartFunction(complaintId, productValue, segmentValue, regionIdnew, datefilter, midcontentheader){
        function pieChartFunction(datefrom1, dateto1, zone, state, city, dealer, ticketStatus, tat, del) {

            var AllZone = [];
            var Allstate = [];
            var Allcity = [];
            var Alldealer = [];
            var AllticketStatus = [];
            if (datefrom1 == '') {
                //alert("Please fill Date from");
                //$( "#datefrom1" ).focus();
            } else if (dateto1 == '') {
                //alert("Please fill Date to");
                //$( "#dateto1" ).focus();
            }
            if (zone == '') {
                //alert("Please fill zone");
                //$( "#zone" ).focus();
            } else {
                var AllZone_ = [];
                $('#zone :selected').each(function(i, sel) {
                    AllZone_.push($(this).val());
                });
                AllZone = AllZone_.join(',');

            }
            if (state == '') {
                //alert("Please fill region");
                //$( "#state" ).focus();
            } else {
                var Allstate_ = [];
                $('#state :selected').each(function(i, sel) {
                    Allstate_.push($(this).val());
                });
                Allstate = Allstate_.join(',');
            }
            if (city == '') {
                //alert("Please fill area");
                //$( "#city" ).focus();
            } else {
                var Allcity_ = [];
                $('#city :selected').each(function(i, sel) {
                    Allcity_.push($(this).val());
                });
                Allcity = Allcity_.join(',');
            }
            if (dealer == '') {
                //alert("Please fill dealer");
                //$( "#dealer" ).focus();
            } else {
                var Alldealer_ = [];
                $('#dealer :selected').each(function(i, sel) {

                    Alldealer_.push($(this).val());
                });
                Alldealer = Alldealer_.join(',');
                del = del != '' ? del : Alldealer;

            }
            if (ticketStatus == '') {
                //alert("Please fill ticket Status");
                //$( "#ticketStatus" ).focus();
            } else {
                var AllticketStatus_ = [];
                $('#ticketStatus :selected').each(function(i, sel) {
                    AllticketStatus_.push("'" + $(this).val() + "'");
                });
                AllticketStatus = AllticketStatus_.join(',');

            }
            if (tat == '') {
                //alert("Please fill tat");
                //$( "#tat" ).focus();
            }

            /*in pie chart section breakdown ticket count and vehicle in workshop count*/
            $.ajax({
                url: '{{ url('ajax-ticket-type') }}',
                data: {
                    'datefrom1': datefrom1,
                    'dateto1': dateto1,
                    'AllZone': AllZone,
                    'Allstate': Allstate,
                    'Allcity': Allcity,
                    'Alldealer': del,
                    'AllticketStatus': AllticketStatus,
                    'tat': tat,
                    '_token': "{{ csrf_token() }}"
                },
                success: function(result) {
                    console.log(result);
                    $('#tickettypedata').html(result);
                }
            });


            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ url('ajax-complaint-search') }}',
                type: 'POST',
                data: {
                    _token: CSRF_TOKEN,
                    'datefrom1': datefrom1,
                    'dateto1': dateto1,
                    'AllZone': AllZone,
                    'Allstate': Allstate,
                    'Allcity': Allcity,
                    'Alldealer': del,
                    'AllticketStatus': AllticketStatus,
                    'tat': tat,
                    '_token': "{{ csrf_token() }}"
                },
                success: function(data) {
                    console.log(data);
                    $('#dashboardtabledata').html(data.html);
                }
            });


            $.ajax({
                url: '{{ url('ajax-pie-search') }}',
                data: {
                    'datefrom1': datefrom1,
                    'dateto1': dateto1,
                    'AllZone': AllZone,
                    'Allstate': Allstate,
                    'Allcity': Allcity,
                    'Alldealer': del,
                    'AllticketStatus': AllticketStatus,
                    'tat': tat,
                    '_token': "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    $('#ajaxLoader').show();
                },
                success: function(data1) {
                    console.log("pie", data1);
                    var strLen1 = data1.length;
                    data1 = data1.slice(0, strLen1 - 1);
                    //console.log(data1);
                    google.charts.load('current', {
                        'packages': ['corechart']
                    });
                    google.charts.setOnLoadCallback(rexSurvey);
                    $('#ajaxLoader').hide();
                    var result = data1.split('~');

                    function rexSurvey() {
                        var data = new google.visualization.DataTable();
                        data.addColumn('string', 'Topping');
                        data.addColumn('number', 'Slices');
                        for (item in result) {
                            Result2 = result[item].split(",");
                            data.addRow([Result2[0], parseInt(Result2[1])]);
                            /* Result2[0]- contains the name of the options in pie chart and Result2[1]- contains the count*/
                        }
                        var options = {
                            title: 'Ticket Chart',
                            legend: 'left',
                            pieHole: 0.4,
                            width: '100%',
                            titleFontSize: 14,
                            height: '100%',
                            'is3D': false,
                            pieSliceText: "value",
                            tooltip: {
                                text: 'value'
                            }
                        };
                        // Instantiate and draw the chart for Sarah's pizza.
                        var chart = new google.visualization.PieChart(document.getElementById('rex_survey'));
                        chart.draw(data, options);
                    }

                }
            });
        }
    </script>
    <div class="mobcss"></div>
    <div class="container-fluid" style="background: #e5e5e5;">
        <div class="card" style="background: #e5e5e5;">
            <div class="card-body" style="background: #e5e5e5;">
                <div class="row">
                    <input type="hidden" name="dateValue" id="dateValue">
                    <input type="hidden" name="complaintValue" id="complaintValue">
                    <input type="hidden" name="productValue" id="productValue">
                    <input type="hidden" name="segmentValue" id="segmentValue">
                    <input type="hidden" name="regionValue" id="regionValue">
                    <input type="hidden" name="regionStatus" id="regionStatus">
                    <div class="col-lg-2 box" style="background: #fff;">
                        <div class="box-inner">
                            <a class="nav-link" href="">
                                <p style="background: #111;color: #fff;" class="sidenav_p">Dashboard</p>
                            </a>

                            <p class="sub_p">
                                Date From
                                <input type="text" name="datefrom" id="datefrom1" autocomplete="off" class="form-control"
                                    placeholder="Date From"
                                    onchange="pieChartFunction(this.value,dateto1.value,zone.value,state.value,city.value,dealer.value,ticketStatus.value,tat.value,'')" />
                            </p>
                            <p class="sub_p">
                                Date To
                                <input type="text" name="dateto" id="dateto1" autocomplete="off" class="form-control"
                                    placeholder="Date To"
                                    onchange="pieChartFunction(datefrom1.value,this.value,zone.value,state.value,city.value,dealer.value,ticketStatus.value,tat.value,'')" />
                            </p>
                            <p class="sub_p">
                                Zone
                                <span style="float: right;"></span>
                            <div>
                                <select name="zone[]" multiple id="zone" class="form-control"
                                    onchange="fn_zone_change(this.value,''),pieChartFunction(datefrom1.value,dateto1.value,this.value,state.value,city.value,dealer.value,ticketStatus.value,tat.value,'')"
                                    required>
                                    @isset($regionData)
                                        @foreach ($regionData as $regionRow)
                                            @if (isset($zone))
                                                <option value="{{ $regionRow->id }}"
                                                    {{ in_array($regionRow->id, $zone) ? 'Selected' : '' }}>
                                                    {{ $regionRow->region }}
                                                </option>
                                            @else
                                                <option value="{{ $regionRow->id }}">{{ $regionRow->region }}</option>
                                            @endif
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            </p>
                            <p class="sub_p">
                                Region
                                <span style="float: right;"></span>
                            <div>
                                <select name="state[]" multiple id="state" class="form-control"
                                    onchange="Dealer_State_change(zone.value,this.value,''),pieChartFunction(datefrom1.value,dateto1.value,zone.value,this.value,city.value,dealer.value,ticketStatus.value,tat.value,'')"
                                    required></select>
                            </div>
                            </p>
                            <p class="sub_p">
                                Area
                                <span style="float: right;"></span>
                                <select name="city[]" multiple id="city" class="form-control"
                                    onchange="getCityChangeDealer(zone.value,state.value,this.value,''),pieChartFunction(datefrom1.value,dateto1.value,zone.value,state.value,this.value,dealer.value,ticketStatus.value,tat.value,'')"
                                    required></select>
                            </p>
                            <p class="sub_p">
                                Dealer
                                <span style="float: right;"></span>
                            <div>
                                <select name="dealer[]" multiple id="dealer" class="form-control"
                                    onchange="pieChartFunction(datefrom1.value,dateto1.value,zone.value,state.value,city.value,dealer.value,ticketStatus.value,tat.value,'')"></select>
                            </div>
                            </p>

                            <p class="sub_p">
                                Ticket Status
                            <div>

                                <select name="ticketStatus[]" multiple id="ticketStatus" class="form-control"
                                    onchange="pieChartFunction(datefrom1.value,dateto1.value,zone.value,state.value,city.value,dealer.value,this.value,tat.value,'')">
                                    @if (isset($statusData))
                                        @foreach ($statusData as $row)
                                            @if (isset($ticketStatus))
                                                <option value="{{ $row->type }}"
                                                    {{ in_array($row->type, $ticketStatus) ? 'Selected' : '' }}>
                                                    {{ $row->type }}</option>
                                            @else
                                                <option value="{{ $row->type }}">{{ $row->type }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            </p>
                            <p class="sub_p">
                                TAT (Hour)
                                <span style="float: right;"></span><br>
                            <div>
                                <select name="tat" id="tat" class="form-control" style="width: 100%"
                                    onchange="pieChartFunction(datefrom1.value,dateto1.value,zone.value,state.value,city.value,dealer.value,ticketStatus.value,this.value,'')">
                                    <option value="">--Select--</option>

                                    <option value="12"
                                        @isset($tat) {{ '12' == $tat ? 'Selected' : '' }} @endisset>
                                        12
                                    </option>
                                    <option value="24"
                                        @isset($tat) {{ '24' == $tat ? 'Selected' : '' }} @endisset>
                                        24
                                    </option>
                                    <option value="48"
                                        @isset($tat) {{ '48' == $tat ? 'Selected' : '' }} @endisset>
                                        48
                                    </option>
                                    <option value="60"
                                        @isset($tat) {{ '60' == $tat ? 'Selected' : '' }} @endisset>
                                        60
                                    </option>
                                    <option value="72"
                                        @isset($tat) {{ '72' == $tat ? 'Selected' : '' }} @endisset>
                                        72
                                    </option>
                                    <option value="96"
                                        @isset($tat) {{ '96' == $tat ? 'Selected' : '' }} @endisset>
                                        96
                                    </option>
                                    <option value="120"
                                        @isset($tat) {{ '120' == $tat ? 'Selected' : '' }} @endisset>
                                        120</option>
                                    <option value="0"
                                        @isset($tat) {{ '0' == $tat ? 'Selected' : '' }} @endisset>
                                        All
                                    </option>
                                </select>
                            </div>
                            </p>
                            <div style="clear: both"></div>
                        </div>
                    </div>

                    <div class="col-lg-10" id="col10">


                        <div class="row">
                            <div class="col-lg-12">
                                <div style="background: #fff;padding: 8px;border-radius: 4px;">
                                    <span style="font-size: 16px;">Ticket Chart</span>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row" style="background: #fff;margin-left: 0.1rem;margin-right: 0.01rem;">
                                    <div class="col-lg-8" style="border-radius: 4px;">
                                        <div id="rex_survey"></div>
                                    </div>
                                    <div class="col-lg-4" style="border-radius: 4px;">
                                        <div id="tickettypedata"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="dashboardtabledata"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="clear:both;"></div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        text: 'Excel',
                        className: 'exportExcel',
                        filename: 'Test_Excel',
                        exportOptions: {
                            modifier: {
                                page: 'all'
                            }
                        }
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        className: 'exportExcel',
                        filename: 'Test_Csv',
                        exportOptions: {
                            modifier: {
                                page: 'all'
                            }
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'exportExcel',
                        filename: 'Test_Pdf',
                        exportOptions: {
                            modifier: {
                                page: 'all'
                            }
                        }
                    }
                ]
            });

        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var df = '{{ $datefrom }}';
            var dt = '{{ $dateto }}';
            $('#datefrom1').val(df);
            var datefrom1 = df;
            $('#dateto1').val(dt);
            var dateto1 = dt;
            var ticketStatus = $('#ticketStatus').val();
            console.log(ticketStatus);
            var tat = $('#tat').val();
            @if (Auth::user()->role == '87' || Auth::user()->role == '29' || Auth::user()->role == '30')
                var zone = "1,2,3,4,5,6,7";
                var state = "{{ $stateId }}";
                var city = "{{ $cityId }}";
                var dealer = "{{ $dealerAllId }}";
            @else
                var zone = "{{ Auth::user()->zone }}";
                var state = "{{ Auth::user()->state }}";
                var city = "{{ Auth::user()->city }}";
                var dealer = "{{ Auth::user()->dealer_id }}";
            @endif

            var del = dealer;
            fn_zone_change(zone, state);
            Dealer_State_change(zone, state, city);
            getCityChangeDealer(zone, state, city, dealer);
            pieChartFunction(datefrom1, dateto1, zone, state, city, dealer, ticketStatus, tat, del);
        });

        function fn_zone_change(zoneId, ell) {
            var myarray = [];
            var favorite = [];
            if (ell != '') {
                $('#zone :selected').each(function(i, sel) {
                    //favorite.push(ell);
                });

                //var zz=favorite.join(",");
                var zz = zoneId;
            } else {
                $('#zone :selected').each(function(i, sel) {
                    favorite.push($(this).val());
                });
                var zz = favorite.join(",");
            }
            $.ajax({
                url: '{{ url('get-multiple-zone-change') }}',
                data: {
                    'zoneId': zz,
                    '_token': "{{ csrf_token() }}"
                },
                success: function(data) {
                    var Result = data.split(",");
                    var str = '';
                    Result.pop();
                    for (item in Result) {
                        Result2 = Result[item].split("~");
                        var mith = ell.split(",");

                        if (ell != '') {
                            if (jQuery.inArray(Result2[0], mith) != '-1') //if(ell==Result[item])
                            {
                                str += "<option value='" + Result2[0] + "' selected>" + Result2[1] +
                                    "</option>";
                            } else {
                                str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
                            }
                        } else {
                            str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
                        }
                    }
                    document.getElementById('state').innerHTML = str;
                }
            });
        }

        function Dealer_State_change(el, ell, elll) {
            var favorite = [];
            var AllZone_ = [];
            var AllState_ = [];
            if (elll != '') {
                //var state=favorite.join(",");

                AllZone = el;
                AllState = ell;
            } else {

                $('#zone :selected').each(function(i, sel) {
                    AllZone_.push($(this).val());
                });
                var AllZone = AllZone_.join(',');

                $('#state :selected').each(function(i, sel) {
                    AllState_.push($(this).val());
                });

                var AllState = AllState_.join(',');
            }

            //$('#City').val('NA');


            /*if(ell!=''){var state = el;}*/
            $.ajax({
                url: '{{ url('get-multiple-state-id-city') }}',
                data: {
                    'r_id': AllZone,
                    's_id': AllState,
                    '_token': "{{ csrf_token() }}"
                },
                success: function(data) {
                    var Result = data.split(",");
                    var str = '';
                    Result.pop();
                    for (item in Result) {
                        Result2 = Result[item].split("~");
                        var mith = elll.split(",");
                        if (elll != '') {
                            if (jQuery.inArray(Result2[0], mith) != '-1') //if(ell==Result[item])
                            {
                                str += "<option value='" + Result2[0] + "' selected>" + Result2[1] +
                                    "</option>";
                            } else {
                                str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
                            }
                        } else {
                            str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
                        }
                    }
                    document.getElementById('city').innerHTML = str;
                }
            });
        }

        function getCityChangeDealer(zone, region, area, dealer) {
            var favorite = [];
            var AllZone_ = [];
            var AllState_ = [];
            var AllCity_ = [];
            var AllDealer_ = [];
            if (dealer != '') {
                var AllZone = zone;
                var AllState = region;
                var AllCity = area;
                var AllDealer = dealer;
            } else {
                $('#zone :selected').each(function(i, sel) {
                    AllZone_.push($(this).val());
                });
                var AllZone = AllZone_.join(',');

                $('#state :selected').each(function(i, sel) {
                    AllState_.push($(this).val());
                });
                var AllState = AllState_.join(',');

                $('#city :selected').each(function(i, sel) {
                    AllCity_.push($(this).val());
                });
                var AllCity = AllCity_.join(',');
            }

            $.ajax({
                url: '{{ url('get-city-change-dealer') }}',
                data: {
                    'zone': AllZone,
                    'region': AllState,
                    'city': AllCity,
                    '_token': "{{ csrf_token() }}"
                },
                success: function(data) {
                    var Result = data.split(",");
                    var str = '';
                    var value = dealer.split(",");
                    Result.pop();
                    for (item in Result) {
                        Result2 = Result[item].split("~");
                        if (dealer != '') {
                            if (jQuery.inArray(Result2[0], value) != '-1') {
                                str += "<option value='" + Result2[0] + "' selected>" + Result2[1] +
                                    "</option>";
                            } else {
                                str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
                            }
                        } else {
                            str += "<option value='" + Result2[0] + "'>" + Result2[1] + "</option>";
                        }
                    }
                    document.getElementById('dealer').innerHTML = str;
                }
            });
        }
    </script>
    <style>
        .activeclass {
            font-weight: 800 !important;
            text-decoration: none;
            border-bottom: 4px solid #000;

        }

        /* .horizontal-menu.fixed-on-scroll + .page-body-wrapper {
                  padding-top: 4rem;
                  background: #e5e5e5;
                  } */
        .middlecontent {
            float: right;
            font-size: 12px;
            margin-left: 10px;
            text-decoration: underline;
        }

        .tabledash {
            width: 80%;
            border: 1px solid #fff;
            margin: 0 auto;
            color: #fff;
        }

        .tabledash tr td {
            padding: 2px 19px;
            font-size: 12px;
            text-align: center;
        }

        .sidenav {
            height: 100%;
            width: 200px;
            position: relative;
            z-index: 1;
            left: 0;
            overflow-x: hidden;
            background: #ffffff;
        }

        .sidenav_a {
            padding: 6px 8px 6px 16px;
            text-decoration: none;
            font-size: 15px;
            color: #818181;
            display: block;
        }

        .sidenav_p {
            padding: 6px 8px 6px 16px;
            text-decoration: none;
            font-size: 20px;
            color: #111;
            display: block;
        }

        .sub_p {

            text-decoration: none;
            font-size: 15px !important;
            color: #818181;
            display: block;
        }

        .sidenav a:hover {
            color: #111;
        }

        .main {
            margin-left: 160px;
            /* Same as the width of the sidenav */
            font-size: 28px;
            /* Increased text to enable scrolling */
            padding: 0px 10px;
        }

        @media screen and (max-height: 450px) {
            .sidenav {}

            .sidenav a {
                font-size: 18px;
            }
        }
    </style>
@endsection
