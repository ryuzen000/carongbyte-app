@extends('admin::layouts.master')

@php
    $title = 'Test CSRF: ' . cr_auth_name();
@endphp

@section('title', $title)

@section('breadcrumb_title', 'Test Form')

@section('content')

    <div id="cr_pesan"></div>

    <div class="form-group">
        <label for="frmInputName">Name</label>
        <input type="text" class="cr-input-name form-control" id="frmInputName" aria-describedby="nameHelp" name="name"
            value="">
        <small id="nameHelp" class="form-text text-muted">Lorem ipsum.</small>
    </div>
    <button id="cr_submit" type="submit" class="btn btn-primary" name="frm_submit" value="1">Submit</button>
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

            $("#cr_submit").click(function() {
                $.ajax({
                    url: "{{ route('admin.profile.edit-ajax') }}",
                    method: "POST",
                    data: {
                        name: $("#frmInputName").val()
                    },
                    success: function(result) {
                        console.log(result);
                        $("#cr_pesan").html(
                            "<div class=\"alert alert-success alert-block\">" +
                            "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>" +
                            "<strong>" + result.message + "</strong>" +
                            "</div>"
                        );
                    }
                });
                console.log($("#frmInputName").val());
            });
        });

        let msg = "Hello, World!";

        console.log(msg)
    </script>
@endsection
