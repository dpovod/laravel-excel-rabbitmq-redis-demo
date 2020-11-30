@extends('layouts.app')

@section('title', 'Upload Excel')

@section('meta')
    @parent
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <excel-upload-form></excel-upload-form>
@endsection
