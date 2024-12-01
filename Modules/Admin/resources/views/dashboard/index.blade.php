@extends('admin::layouts.master')

@php
    $title = 'Dashboard';
@endphp

@section('title', $title)

@section('breadcrumb_title', $title)

@section('breadcrumb_nav')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">Home</li>
    </ol>
@endsection


@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $jml_pengguna }}</h3>

                    <p>Daftar Pengguna</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="{{ route('admin.user') }}" class="small-box-footer">Selengkapnya... <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
@endsection
