@extends('theme.default')
@section('content')
    <div id="chp-dash">
        <div id="graphHolder-chp"></div>
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
                                },
                                error: function(response) {
                                    $.each(response.responseJSON.errors, function(key,value) {
                                        alert(value);
                                    });
                                }
                            });
                        }
                    }
                })
            }
        }
        ChpDash.initVue();
    </script>
@endsection
