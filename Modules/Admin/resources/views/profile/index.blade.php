@extends('admin::layouts.master')

@php
    $title = 'Profile ' . cr_auth_name();
@endphp

@section('title', $title)

@section('content')
    <style>
        .cr-user-avatar .inner .avatar {
            overflow: hidden;
            width: 100%;
            height: 100%;
        }

        .cr-user-avatar .inner .avatar img {
            width: 100%;
            height: auto;
        }

        .cr-user-info ul {
            list-style: none;
            margin: 0;
            padding: 0
        }
    </style>
    <div>Profile page</div>
    @php
        $foto = cr_auth_foto();
    @endphp
    <pre>
        {{ print_r(cr_auth_foto()) }}
    </pre>
    <a href="{{ route('admin.profile.edit') }}">Edit Profile</a>
    <div class="row">
        <div class="col-12 col-md-2">
            <div class="cr-user-avatar">
                <div class="inner">
                    <div class="avatar">
                        <img src="{{ asset('uploads/' . cr_auth_foto()) }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-10">
            <div class="cr-user-info">
                <ul>
                    <li><b>Name</b>: {{ $name }}</li>
                    <li><b>Email</b>: {{ $email }}</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
