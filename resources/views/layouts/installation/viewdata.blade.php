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
                            var data = {'installation_id': {{ $installation->id  }}};
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: 'POST',
                                url: "{{ route('installation.getinfo') }}",
                                data: data,
                                contentType: false,
                                processData: false,
                                success: (response) => {
                                    alert('success');
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
            }
        }
        ChpDash.initVue();
    </script>
@endsection
