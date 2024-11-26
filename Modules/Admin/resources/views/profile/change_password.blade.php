@extends('admin::layouts.master')

@php
    $title = 'Change Password';
@endphp

@section('title', $title)

@section('breadcrumb_title', 'Change Password')

@section('content')
    <style>
        .cr-alert ul {
            list-style: none;
            margin: 0;
            padding: 0;
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

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    <form action="{{ route('admin.profile.change-password') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="frmOldPassword">Old password</label>
            <input type="password" class="form-control" id="frmOldPassword" aria-describedby="oldPasswordHelp"
                name="old_password" value="">
            <small id="oldPasswordHelp" class="form-text text-muted">Masukan password lama.</small>
        </div>
        <div class="form-group">
            <label for="frmNewPassword">Create new password</label>
            <input type="password" class="form-control" id="frmNewPassword" aria-describedby="newPasswordHelp"
                name="new_password" value="">
            <small id="newPasswordHelp" class="form-text text-muted">Masukan password baru.</small>
        </div>
        <div class="form-group">
            <label for="frmConfirmPassword">Confirm new password</label>
            <input type="password" class="form-control" id="frmConfirmPassword" aria-describedby="confirmPasswordHelp"
                name="confirm_password" value="">
            <small id="confirmPasswordHelp" class="form-text text-muted">Konfirmasi password baru.</small>
        </div>
        <button type="submit" class="btn btn-primary" name="frm_submit" value="1">Change</button>
    </form>
@endsection
