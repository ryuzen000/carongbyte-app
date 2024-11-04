@extends('admin::layouts.master')

@php
    $title = 'User';
@endphp

@section('title', $title)

@section('content')
    @php
        $navbars = cr_nav_items();
    @endphp
    <pre>
        {{ print_r($navbars) }}
    </pre>
@endsection
