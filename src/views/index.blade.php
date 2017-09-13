@extends('layouts.app')

@section('content')
    <div class="container">
        <electrum-wallet prefix="{{ config('electrum.web_interface.prefix') }}" currency="{{ config('electrum.web_interface.currency') }}"></electrum-wallet>
    </div>
@endsection
