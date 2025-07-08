@extends('theme.default')
@section('content')
    <div id="chp-dash">
        <div id="barchart-chp" style="width:99.5%;height:600px"></div>
    </div>
    <script type="text/javascript">
        var ChpDash = {
            monthsview: true,
            selectedMonth: false,
            view: 'monthly',
            selectedDate: '',
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
                                    ChpDash.monthly = response.readings;
                                    ChpDash.machineType = response.machine_type;
                                    if (ChpDash.machineType == 1) {
                                        ChpDash.elecLabel = 'Electricty Generated Line 1';
                                        ChpDash.heatLabel = 'Electricty Generated Line 2';
                                        ChpDash.heatPrefix = ' kWh';
                                        ChpDash.graphTitle = 'GENSET Usage';
                                        ChpDash.graphStack = 'normal';
                                    } else {
                                        ChpDash.elecLabel = 'Electricty Generated';
                                        ChpDash.heatLabel = 'Heat Generated';
                                        ChpDash.heatPrefix = ' kWth';
                                        ChpDash.graphTitle = 'CHP Usage';
                                        ChpDash.graphStack = false;
                                    }
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

            getDailys: function() {
                ChpDash.view = 'daily';
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: "{{ route('installation.getinfoMonthly', $installation) }}",
                    data: {'month': ChpDash.selectedDate},
                    success: (response) => {
                        var elec = [];
                        var gas = [];
                        var heat = [];
                        var elecinput = [];
                        var gasinput = [];
                        var xaxis = [];
                        var arrayLength = response.data.length;
                        for (var i = 0; i < arrayLength; i++) {
                            xaxis.push(response.data[i][0]);
                            elec.push(response.data[i][2]);
                            heat.push(response.data[i][1]);
                            gas.push(response.data[i][3]);
                            elecinput.push(response.data[i][4]);
                            gasinput.push(response.data[i][5]);
                        }
                        ChpDash.drawBarChart(xaxis, elec, gas, heat, elecinput, gasinput);
                        ChpDash.active.selectedDate = ChpDash.selectedDate;
                    },
                    error: function(response) {
                        $.each(response.responseJSON.errors, function(key,
                            value) {
                            alert(value);
                        });
                    }
                });
            },

            drawBarChart: function(xaxis, elec, gas, heat, elecinput, gasinput) {
                const chart = Highcharts.chart('barchart-chp', {
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: 'CHP Usage Graphs'
                    },
                    subtitle: {
                        text: ''
                    },
                    xAxis: [{
                        categories: xaxis,
                        type: 'category',
                        labels: {
                            style: {
                                fontSize: '12px'
                            }
                        }
                    }],
                    yAxis: [{ // Primary yAxis
                        labels: {
                            format: '{value}kWh',
                            style: {
                                color: Highcharts.getOptions().colors[1],
                                fontSize: '12px'
                            }
                        },
                        title: {
                            text: 'Amount Consumed/Generated',
                            style: {
                                color: Highcharts.getOptions().colors[1],
                                fontSize: '12px'
                            }
                        }
                    }],
                    tooltip: {
                        shared: true,
                        crosshairs: true,
                        useHTML: true,
                        headerFormat: '<table><tr><th colspan="2">{point.key}</th></tr>',
                        pointFormat: '<tr><td style="">{series.name}: </td><td style="text-align: right"><b>{point.y}</b></td></tr>',
                        footerFormat: '</table>',
                    },
                    plotOptions: {
                        column: {
                            stacking: ChpDash.graphStack,
                            dataLabels: {
                                enabled: false
                            }
                        },
                        series: {
                            centerInCategory: false,
                            shadow: true
                        }
                    },
                    series: [{
                            name: ChpDash.heatLabel,
                            type: 'column',
                            data: heat,
                            color: '#6390BA',
                            tooltip: {
                                valueSuffix: ChpDash.heatPrefix,
                                valueDecimals: 0,
                            },
                            events: {
                                click: function(event) {
                                    if (ChpDash.view == 'monthly') {
                                        ChpDash.selectedDate = event.point.category;
                                        ChpDash.getDailys();
                                        return;
                                    }
                                    if (ChpDash.view == 'daily') {
                                        ChpDash.getHHs(event.point.category);
                                        return;
                                    }
                                }
                            }
                        }, {
                            name: ChpDash.elecLabel,
                            type: 'column',
                            data: elec,
                            color: '#7cb5ec',
                            tooltip: {
                                valueSuffix: ' kWh',
                                valueDecimals: 0,
                            },
                            events: {
                                click: function(event) {
                                    if (ChpDash.view == 'monthly') {
                                        ChpDash.selectedDate = event.point.category;
                                        ChpDash.getDailys();
                                        return;
                                    }
                                    if (ChpDash.view == 'daily') {
                                        ChpDash.getHHs(event.point.category);
                                        return;
                                    }
                                }
                            }
                        },
                        {
                            name: 'Gas Consumed',
                            type: 'column',
                            data: gas,
                            color: 'lightgreen',
                            tooltip: {
                                valueSuffix: ' kWh',
                                valueDecimals: 0
                            },
                            events: {
                                click: function(event) {
                                    if (ChpDash.view == 'monthly') {
                                        ChpDash.selectedDate = event.point.category;
                                        ChpDash.getDailys();
                                        return;
                                    }
                                    if (ChpDash.view == 'daily') {
                                        ChpDash.getHHs(event.point.category);
                                        return;
                                    }
                                }
                            }
                        }
                    ]
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
