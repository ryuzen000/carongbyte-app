@extends('admin::layouts.master')

@php
    $title = 'Dashboard';
@endphp

@section('title', $title)

@section('content')
    <pre>
    @php
        print_r($users);
    @endphp
    </pre>
@endsection
