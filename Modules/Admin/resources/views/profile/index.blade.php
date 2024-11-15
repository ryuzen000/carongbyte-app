@extends('admin::layouts.master')

@php
    $title = 'Profile ' . cr_auth_name();
@endphp

@section('title', $title)

@section('breadcrumb_title', 'Profile')

@section('content')
    {{-- Ini comment di blade --}}
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
                    <li><b>Address</b>: {{ cr_get_usermeta(Auth::id(), 'user_alamat') }}</li>
                </ul>
            </div>
        </div>
    </div>
    <a href="{{ route('admin.profile.change-password') }}">Ubah Password</a>
@endsection
