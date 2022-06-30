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
        <div class="w-lg-600px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
            <!--begin::Form-->
            <form class="form w-100" novalidate="novalidate" id="kt_sign_up_form">
                <!--begin::Heading-->
                <div class="mb-10 text-center">
                    <!--begin::Title-->
                    <h1 class="text-dark mb-3">Register a Company</h1>
                    <!--end::Title-->
                    <!--begin::Link-->
                    <div class="text-gray-400 fw-bold fs-4">Already have an account?
                    <a href="/login" class="link-primary fw-bolder">Sign in here</a></div>
                    <!--end::Link-->
                </div>
                <!--end::Heading-->
                <div class="fv-row mb-7">
                    <label class="form-label fw-bolder text-dark fs-6">Bedrijfsnaam</label>
                    <input class="form-control form-control-lg form-control-solid" type="text" placeholder="" name="company_name" id="company_name" autocomplete="off" />
                </div>
                <!--begin::Input group-->
                <div class="row fv-row mb-7">
                    <!--begin::Col-->
                    <div class="col-xl-6">
                        <label class="form-label fw-bolder text-dark fs-6">Voornaam*</label>
                        <input class="form-control form-control-lg form-control-solid" type="text" placeholder="" name="first_name" id="first_name" autocomplete="off" />
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-xl-6">
                        <label class="form-label fw-bolder text-dark fs-6">Achternaam*</label>
                        <input class="form-control form-control-lg form-control-solid" type="text" placeholder="" name="last_name"  id="last_name" autocomplete="off" />
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <div class="fv-row mb-7">
                    <label class="form-label fw-bolder text-dark fs-6">KvK#*</label>
                    <input class="form-control form-control-lg form-control-solid" type="text" placeholder="" name="chamber_commerce" id="chamber_commerce" autocomplete="off" />
                </div>
                <div class="fv-row mb-7">
                    <label class="form-label fw-bolder text-dark fs-6">Stad*</label>
                    <input class="form-control form-control-lg form-control-solid" type="text" placeholder="" name="city" id="city" autocomplete="off" />
                </div>
                <!--begin::Input group-->
                <div class="fv-row mb-7">
                    <label class="form-label fw-bolder text-dark fs-6">Email</label>
                    <input class="form-control form-control-lg form-control-solid" type="email" placeholder="" name="email" id="email" autocomplete="off" />
                </div>
                <div class="fv-row mb-7">
                    <label class="form-label fw-bolder text-dark fs-6">Tel*</label>
                    <input class="form-control form-control-lg form-control-solid" type="tel" placeholder="" name="tel" id="tel" autocomplete="off" />
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-10 fv-row" data-kt-password-meter="true">
                    <!--begin::Wrapper-->
                    <div class="mb-1">
                        <!--begin::Label-->
                        <label class="form-label fw-bolder text-dark fs-6">Password</label>
                        <!--end::Label-->
                        <!--begin::Input wrapper-->
                        <div class="position-relative mb-3">
                            <input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="password" id="password" autocomplete="off" />
                            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                                <i class="bi bi-eye-slash fs-2"></i>
                                <i class="bi bi-eye fs-2 d-none"></i>
                            </span>
                        </div>
                        <!--end::Input wrapper-->
                        <!--begin::Meter-->
                        <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                        </div>
                        <!--end::Meter-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Hint-->
                    <div class="text-muted">Use 8 or more characters with a mix of letters, numbers &amp; symbols.</div>
                    <!--end::Hint-->
                </div>
                <!--end::Input group=-->
                <!--begin::Input group-->
                <div class="fv-row mb-5">
                    <label class="form-label fw-bolder text-dark fs-6">Confirm Password</label>
                    <input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="confirm-password" autocomplete="off" />
                </div>
                <!--end::Input group-->
               
                <!--begin::Actions-->
                <div class="text-center">
                    <button type="button" id="kt_sign_up_submit" class="btn btn-lg btn-primary">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
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
    <script src="{{ asset('js/survey/signup.js')}}"></script>
@endpush