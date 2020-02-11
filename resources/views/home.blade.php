@extends('layouts.app')

@section('content')
    <tasks-component :auth="{{ Auth::id()  }}"></tasks-component>
@endsection
