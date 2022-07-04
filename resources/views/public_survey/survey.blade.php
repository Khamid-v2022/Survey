@extends('public_survey.layout')
@section('content')
    <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20 step-1">

    {{-- {{ var_dump(count($form)) }} --}}
        <button class="btn btn-success"> Start</button>
    </div>

@endsection


@push('scripts')
    <script src="{{ asset('js/survey/public_survey.js')}}"></script>
@endpush