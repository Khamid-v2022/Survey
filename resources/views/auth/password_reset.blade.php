@extends('layouts.public_app')
@section('content')
<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed">
    <!--begin::Content-->
    <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
        <!--begin::Logo-->
        <a href="{{ route('dashboard') }}" class="mb-12">
            <img alt="Logo" src="{{ asset('images/logo.png') }}" class="h-60px" />
        </a>
        <!--end::Logo-->
        <!--begin::Wrapper-->
        <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
            <!--begin::Form-->
            <form class="form w-100" novalidate="novalidate" id="kt_password_reset_form">
                <!--begin::Heading-->
                <div class="text-center mb-10">
                    <!--begin::Title-->
                    <h1 class="text-dark mb-3">{{ __('Forgot password?') }}</h1>
                    <!--end::Title-->
                    <!--begin::Link-->
                    <div class="text-gray-400 fw-bold fs-4">{{ __('Enter your email address to reset your password') }}</div>
                    <!--end::Link-->
                </div>
                <!--begin::Heading-->
                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <label class="form-label fw-bolder text-gray-900 fs-6">{{ __('Email') }}</label>
                    <input class="form-control form-control-solid" type="email" placeholder="" name="email" autocomplete="off" id="email"/>
                </div>
                <!--end::Input group-->
                <!--begin::Actions-->
                <div class="d-flex flex-wrap justify-content-center pb-lg-0">
                    <button type="button" id="kt_password_reset_submit" class="btn btn-lg btn-success fw-bolder me-4">
                        <span class="indicator-label">{{ __('Send') }}</span>
                        <span class="indicator-progress">{{ __('Please wait...') }}
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <a href="{{ route('login') }}" class="btn btn-lg btn-light-success fw-bolder">{{ __('Cancel') }}</a>
                </div>
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Content-->
</div>
@endsection


@push('scripts')
    <script src="{{ asset('js/survey/password_reset.js')}}"></script>
@endpush