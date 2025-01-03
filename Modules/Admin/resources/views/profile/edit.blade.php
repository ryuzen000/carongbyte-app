@extends('admin::layouts.master')

@php
    $title = 'Edit Profile: ' . cr_auth_name();
@endphp

@section('title', $title)

@section('breadcrumb_title', 'Edit Profile')

@section('breadcrumb_nav')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.profile.index') }}">Profile</a></li>
        <li class="breadcrumb-item active">Edit Profile</li>
    </ol>
@endsection

@section('content')
    <style>
        .cr-alert ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .cr-avatar-preview {
            width: 100px;
            height: 100px;
        }

        .cr-avatar-preview .thumb {
            overflow: hidden;
            width: 100%;
            height: 100%;
        }

        .cr-avatar-preview .thumb img {
            width: 100%;
            height: auto;
        }
    </style>
    @if ($errors->any())
        <div class="cr-alert alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.profile.edit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="frmInputName">Name</label>
            <input type="text" class="form-control" id="frmInputName" aria-describedby="nameHelp" name="name"
                value="{{ $name }}">
            <small id="nameHelp" class="form-text text-muted">Lorem ipsum.</small>
        </div>
        <div class="form-group">
            <label for="frmInputEmail">Email</label>
            <input type="text" class="form-control" id="frmInputEmail" aria-describedby="emailHelp" name="email"
                value="{{ $email }}">
            <small id="emailHelp" class="form-text text-muted">Lorem ipsum.</small>
        </div>
        <div class="form-group">
            <label for="frmInputAddress">Address</label>
            <input type="text" class="form-control" id="frmInputAddress" aria-describedby="addressHelp" name="address"
                value="{{ cr_get_usermeta(Auth::id(), 'user_alamat') }}">
            <small id="addressHelp" class="form-text text-muted">Lorem ipsum.</small>
        </div>
        <div class="form-group">
            <div class="cr-avatar-preview">
                <div class="thumb rounded shadow">
                    <img src="{{ asset('uploads/' . $foto) }}" alt="">
                </div>
            </div>

            <label for="frmInputImage">Photo Profile</label><br>
            <input type="file" id="frmInputImage" name="product_image">
            <small id="addressHelp" class="form-text text-muted">Gambar harus format JPG/PNG.</small>
        </div>
        <button type="submit" class="btn btn-primary" name="frm_submit" value="1">Update</button>
    </form>
    <script>
        "use strict";

        jQuery(document).ready(function($) {
            console.log("jQuery ready!");
            console.log($('meta[name="csrf-token"]').attr('content'));

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        let msg = "Hello, World!";

        console.log(msg)
    </script>
@endsection
