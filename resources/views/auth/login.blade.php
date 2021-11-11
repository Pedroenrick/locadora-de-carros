@extends('layouts.app')

@section('content')

<login-component token_csrf="{{@csrf_token()}}" abc="teste 2">

</login-component>
@endsection
