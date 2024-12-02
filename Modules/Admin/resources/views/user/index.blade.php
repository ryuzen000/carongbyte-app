@extends('admin::layouts.master')

@php
    $title = 'User';
@endphp

@section('title', $title)

@section('breadcrumb_title', 'User')

@section('breadcrumb_nav')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">User</li>
    </ol>
@endsection

@section('content')
    <!--<pre>
        {{ print_r($users) }}
    </pre>-->
    <table id="cr_user_list" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Position</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->nama_pengguna }}</td>
                    <td>{{ $user->user_email }}</td>
                    <td>{{ $user->user_username }}</td>
                    <td>{{ $user->nama_jabatan }}</td>
                    <td><a href="{{ route('admin.user.edit', ['id' => $user->user_id]) }}">Edit</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script type="text/javascript">
        "use strict";

        jQuery(document).ready(function($) {
            if ($().DataTable()) {
                console.log("DataTables ready!");

                $('#cr_user_list').DataTable();
            }
        });
    </script>
@endsection
