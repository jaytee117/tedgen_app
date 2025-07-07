@extends('theme.default')
@section('content')
    <div id="chp-dash">
        <div id="barchart-chp"></div>
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
                                    console.log(response.readings);
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
                document.querySelector('#barchart-chp').innerHTML = '';
            },
            drawBarChart: function(xaxis, elec, gas, heat, elecinput, gasinput) {
                ChpDash.chart = Highcharts.chart('barchart-chp', {
                    title: {
                        //text: ChpDash.graphTitle
                        text: ''
                    },
                    subtitle: {
                        text: ''
                    },
                    credits: {
                        enabled: false
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
                    legend: {
                        layout: 'horizontal',
                        align: 'right',
                        x: -20,
                        verticalAlign: 'top',
                        y: 0,
                        floating: true,
                        itemStyle: {
                            color: '#000000',
                            fontWeight: 'bold',
                            fontSize: '12px'
                        },
                        backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || // theme
                            'rgba(255,255,255,0.25)'
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
                        },
                        {
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
                            stacking: false,
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
                        },
                        {
                            name: 'Electricity To Site',
                            type: 'spline',
                            data: elecinput,
                            color: 'orange',
                            dashStyle: 'dash',
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
                            name: 'Gas To Site',
                            type: 'spline',
                            data: gasinput,
                            color: 'red',
                            dashStyle: 'dash',
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

                        }
                    ]
                });
            },
        }
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        ChpDash.initVue();
    });
</script>
@endsection
