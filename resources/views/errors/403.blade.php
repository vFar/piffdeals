@extends('errors::minimal')

@section('title', __('AIZLIEGTS'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'AIZLIEGTS'))
