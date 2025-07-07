@extends('theme.default')
@section('content')
    <div id="chp-dash">
        test
        <div id="barchart-chp" style="width:99.5%;height:600px"></div>
    </div>
    <script type="text/javascript">
        var ChpDash = {
            initVue: function() {
                ChpDash.active = new Vue({
                    el: '#chp-dash',
                    mounted() {
                        this.getSiteData();
                    },
                    methods: {
                        getSiteData: function() {
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: 'post',
                                url: "{{ route('installation.getinfo', $installation) }}",
                                success: (response) => {
                                    //console.log(response.readings);
                                    ChpDash.monthly = response.readings;
                                    ChpDash.loadYearView();
                                },
                                error: function(response) {
                                    $.each(response.responseJSON.errors, function(key,
                                        value) {
                                        alert(value);
                                    });
                                }
                            });
                        }
                    }
                })
            },
            loadYearView: function() {
                ChpDash.view = 'monthly';
                var elec = [];
                var gas = [];
                var heat = [];
                var elecinput = [];
                var gasinput = [];
                var xaxis = [];
                var arrayLength = ChpDash.monthly.length;
                for (var i = 0; i < arrayLength; i++) {
                    xaxis.push(ChpDash.monthly[i][0]);
                    elec.push(ChpDash.monthly[i][2]);
                    heat.push(ChpDash.monthly[i][1]);
                    gas.push(ChpDash.monthly[i][3]);
                    elecinput.push(ChpDash.monthly[i][4]);
                    gasinput.push(ChpDash.monthly[i][5])
                }
                ChpDash.drawBarChart(xaxis, elec, gas, heat, elecinput, gasinput);
                //document.querySelector('#barchart-chp').innerHTML = '';
            },
            drawBarChart: function(xaxis, elec, gas, heat, elecinput, gasinput) {
                //sconsole.log(xaxis);
                const chart = Highcharts.chart('barchart-chp', {
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: 'Active and Trail users'
                    },
                    subtitle: {
                        text: ''
                    },
                    xAxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep',
                            'Oct', 'Nov', 'Dec'
                        ]
                    },
                    yAxis: {
                        title: {
                            text: 'Users'
                        }
                    },
                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true
                            },
                            enableMouseTracking: false
                        }
                    },
                    series: [{
                        name: 'Active',
                        data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9,
                            9.6
                        ]
                    }, {
                        name: 'Trial',
                        data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
                    }]
                });
            },
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ChpDash.initVue();
        });
    </script>
@endsection
