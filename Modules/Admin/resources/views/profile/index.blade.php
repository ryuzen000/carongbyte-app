@extends('admin::layouts.master')

@php
    $title = 'Profile ' . cr_auth_name();
@endphp

@section('title', $title)

@section('breadcrumb_title', 'Profile')

@section('breadcrumb_nav')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
        <li class="breadcrumb-item active">Profile</li>
    </ol>
@endsection

@section('content')
    {{-- Ini comment di blade --}}
    <style>
        .cr-edit-profile-btn {
            position: relative;
            overflow: hidden;
            padding-bottom: 12px;
        }

        .cr-edit-profile-btn a {
            color: #222;
            display: inline-block;
            background: lightblue;
            padding: 4px 17px;
            transition: .5s;
            float: right;
        }

        .cr-edit-profile-btn a:hover {
            background-color: lime;
        }

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

        .cr-user-info ul li {
            padding-bottom: 7px;
        }

        .cr-user-info ul li>b {
            display: inline-block;
            min-width: 100px;
        }
    </style>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    <div class="cr-edit-profile-btn">
        <a class="rounded" href="{{ route('admin.profile.edit') }}">Edit Profile</a>
    </div>

    <div class="row">
        <div class="col-12 col-md-2">
            <div class="cr-user-avatar">
                <div class="inner">
                    <div class="avatar rounded shadow">
                        <img src="{{ asset('uploads/' . cr_auth_foto()) }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-10">
            <div class="cr-user-info">
                <ul>
                    <li><b>Name:</b> <span>{{ $name }}</span></li>
                    <li><b>Email:</b> <span>{{ $email }}</span></li>
                    <li><b>Address:</b> <span>{{ cr_get_usermeta(Auth::id(), 'user_alamat') }}</span></li>
                </ul>
            </div>
        </div>
    </div>
    <a class="d-none" href="{{ route('admin.profile.change-password') }}">Ubah Password</a>
@endsection
