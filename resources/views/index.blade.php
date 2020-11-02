<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">

    <title>explorer</title>

    {{--StyleSheet--}}
    <link href="{{ asset('css/app.css') }}?v={{ get_version() }}" rel="stylesheet">

</head>
<body>


<div id="explorer"></div>


<script>
    const config = {
        host: '{{ url('/') }}',
        api: '{{ url('/api') }}',
        locale: '{{ app()->getLocale() }}',
        chunkSize: {{ format_bytes(config('explorer.chunk_size')) }},
        hasAuthCookie: {{ Cookie::has('token') ? 1 : 0 }},
    }
</script>

@if(env('APP_ENV') !== 'local')

    {{--Application production script--}}
    <script src="{{ asset('js/main.js') }}?v={{ get_version() }}"></script>

@else
    {{--Application development script--}}
    <script src="{{ mix('js/main.js') }}"></script>
@endif


</body>
</html>
