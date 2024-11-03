@extends('admin::layouts.master')

@php
    $title = 'Edit Profile: ' . cr_auth_name();
@endphp

@section('title', $title)

@section('content')
    <form action="{{ route('admin.profile.edit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="frmInputUserName">Nama Pengguna</label>
            <input type="text" class="form-control" id="frmInputUserName" aria-describedby="userNameHelp" name="user_name"
                value="{{ $name }}">
            <small id="userNameHelp" class="form-text text-muted">Lorem ipsum.</small>
        </div>
        <div class="form-group">
            <label for="frmInputImage">Featured Image</label><br>
            <input type="file" id="frmInputImage" name="product_image">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
