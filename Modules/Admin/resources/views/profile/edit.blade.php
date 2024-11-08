@extends('admin::layouts.master')

@php
    $title = 'Edit Profile: ' . cr_auth_name();
@endphp

@section('title', $title)

@section('content')
    <style>
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
    <form action="{{ route('admin.profile.edit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="frmInputUserName">Name</label>
            <input type="text" class="form-control" id="frmInputUserName" aria-describedby="userNameHelp" name="user_name"
                value="{{ $name }}">
            <small id="userNameHelp" class="form-text text-muted">Lorem ipsum.</small>
        </div>
        <div class="form-group">
            <label for="frmInputUserEmail">Email</label>
            <input type="text" class="form-control" id="frmInputUserEmail" aria-describedby="userEmailHelp"
                name="user_email" value="{{ $email }}">
            <small id="userEmailHelp" class="form-text text-muted">Lorem ipsum.</small>
        </div>
        <div class="form-group">
            <label for="frmInputUserAddress">Address</label>
            <input type="text" class="form-control" id="frmInputUserAddress" aria-describedby="userAddressHelp"
                name="user_address" value="{{ cr_get_usermeta(Auth::id(), 'user_alamat') }}">
            <small id="userAddressHelp" class="form-text text-muted">Lorem ipsum.</small>
        </div>
        <div class="form-group">
            <div class="cr-avatar-preview">
                <div class="thumb rounded shadow">
                    <img src="{{ asset('uploads/' . $foto) }}" alt="">
                </div>
            </div>

            <label for="frmInputImage">Featured Image</label><br>
            <input type="file" id="frmInputImage" name="product_image">
        </div>
        <button type="submit" class="btn btn-primary" name="frm_submit" value="1">Update</button>
    </form>
@endsection
