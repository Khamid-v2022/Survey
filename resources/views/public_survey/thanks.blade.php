@extends('public_survey.layout')
@section('content')
    <div class="container text-center">
        <h2>{{ _('Thank you for your cooperation!') }}</h2>
        <a class="btn btn-success mt-10" href="/"> {{ __('Sign In') }} </a>
    </div>
@endsection