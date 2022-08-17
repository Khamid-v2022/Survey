@extends('layouts.app')
@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            
            <!--begin::Row-->
            <div class="row gy-5 g-xl-8">
                <!--begin::Col-->
                <div class="col-xl-12">
                    <!--begin::Tables Widget 9-->
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">{{ __('Company Statistics') }}</span>
                                {{-- <span class="text-muted mt-1 fw-bold fs-7">Over 500 members</span> --}}
                            </h3>
                            {{-- <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Click to add a company">
                                <a href="#" class="btn btn-sm btn-success btn-active-success" data-bs-toggle="modal" data-bs-target="#kt_modal_invite_friends">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                                <span class="svg-icon svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                New Company</a>
                            </div> --}}
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body py-3">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="kv_table">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr class="fw-bolder text-muted">
                                            <th class="min-w-100px">{{ __('Company Name') }}</th>
                                            <th class="min-w-80px">{{ __('First name') }}</th>
                                            <th class="min-w-80px">{{ __('Role') }}</th>
                                            <th class="min-w-100px">{{ __('Email') }}</th>
                                            <th class="min-w-100px text-end">{{__('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                        @foreach($companies as $item)
                                        <tr company_id = "{{ $item['id'] }}">
                                            <td>
                                                <a href="#" class="text-dark fw-bolder text-hover-success fs-6">{{ $item['name'] }}</a>    
                                            </td>
                                            <td>
                                                {{ $item['first_name'] . ' ' . $item['last_name'] }}
                                            </td>
                                            <td>
                                                <span class="badge badge-light-dark fs-7 m-1">{{ $item['role'] }}</span>
                                            </td>
                                            <td>
                                                {{ $item['email'] }}
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-end flex-shrink-0">
                                                    <label class="form-check form-switch form-check-custom form-check-solid me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="{{ __('Active') }}">
                                                        <input class="form-check-input active-company-btn" type="checkbox" {{ $item['active']=='active'?'checked':'' }} />
                                                    </label>

                                                    <a href="#" class="btn btn-icon btn-bg-light btn-active-color-success btn-sm info-btn me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="{{ __('View') }}">
                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen027.svg-->
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM12.5 18C12.5 17.4 12.6 17.5 12 17.5H8.5C7.9 17.5 8 17.4 8 18C8 18.6 7.9 18.5 8.5 18.5L12 18C12.6 18 12.5 18.6 12.5 18ZM16.5 13C16.5 12.4 16.6 12.5 16 12.5H8.5C7.9 12.5 8 12.4 8 13C8 13.6 7.9 13.5 8.5 13.5H15.5C16.1 13.5 16.5 13.6 16.5 13ZM12.5 8C12.5 7.4 12.6 7.5 12 7.5H8C7.4 7.5 7.5 7.4 7.5 8C7.5 8.6 7.4 8.5 8 8.5H12C12.6 8.5 12.5 8.6 12.5 8Z" fill="currentColor"/>
                                                            <rect x="7" y="17" width="6" height="2" rx="1" fill="currentColor"/>
                                                            <rect x="7" y="12" width="10" height="2" rx="1" fill="currentColor"/>
                                                            <rect x="7" y="7" width="6" height="2" rx="1" fill="currentColor"/>
                                                            <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor"/>
                                                        </svg>
                                                            
                                                        <!--end::Svg Icon-->
                                                    </a>

                                                    <a href="#" class="btn btn-icon btn-bg-light btn-active-color-success btn-sm delete-company-btn" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="{{ __('Delete') }}">
                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen027.svg-->
                                                        <span class="svg-icon svg-icon-3">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor" />
                                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor" />
                                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon-->
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Table container-->
                        </div>
                        <!--begin::Body-->
                    </div>
                    <!--end::Tables Widget 9-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
           
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->




{{-- modal --}}
<div class="modal fade" id="kt_modal" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header pb-0 border-0 justify-content-end">
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-success" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--begin::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
                <!--begin::Heading-->
                <div class="text-center mb-13">
                    <h1 class="mb-3">{{ _('Company Info') }}</h1>
                </div>
                <!--end::Heading-->
               
                <div class="row g-9 mb-8">
                    <!--begin::Col-->
                    <div class="col-md-6 fv-row">
                        <label class="fs-6 fw-bold mb-2">{{ __('Company Name') }}</label>
                        <input type="text" class="form-control form-control-solid" id="m_company_name"  readonly/>
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-6 fv-row">
                        <label class="fs-6 fw-bold mb-2">{{ __('Organisation Type') }}</label>
                        <input type="text" class="form-control form-control-solid" id="m_org_type" readonly/>
                    </div>
                    <!--end::Col-->
                </div>

                <div class="row g-9 mb-8">
                    <!--begin::Col-->
                    <div class="col-md-6 fv-row">
                        <label class="fs-6 fw-bold mb-2">{{ __('First name') }}</label>
                        <input type="text" class="form-control form-control-solid" id="m_first_name" readonly/>
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-6 fv-row">
                        <label class="fs-6 fw-bold mb-2">{{ __('Last name') }}</label>
                        <input type="text" class="form-control form-control-solid" id="m_last_type" readonly/>
                    </div>
                    <!--end::Col-->
                </div>

                <div class="fv-row mb-7">
                    <label class="form-label fw-bolder text-dark fs-6">KvK#</label>
                    <input class="form-control form-control-solid" type="text" id="m_chamber_commerce" autocomplete="off" readonly/>
                </div>
                <div class="fv-row mb-7">
                    <label class="form-label fw-bolder text-dark fs-6">{{ __('City') }}</label>
                    <input class="form-control form-control-solid" type="text" id="m_city" autocomplete="off" readonly/>
                </div>
                <!--begin::Input group-->
                <div class="fv-row mb-7">
                    <label class="form-label fw-bolder text-dark fs-6">{{ __('Email') }}</label>
                    <input class="form-control form-control-solid" type="email" id="m_email" autocomplete="off" readonly/>
                </div>
                <div class="fv-row mb-7">
                    <label class="form-label fw-bolder text-dark fs-6">{{ __('Tel') }}</label>
                    <input class="form-control form-control-solid" type="tel" id="m_tel" autocomplete="off" readonly/>
                </div>
                
                <!--begin::Actions-->
                <div class="text-center">
                    <button type="reset" id="kt_modal_cancel" data-dismiss="modal" class="btn btn-light me-3">{{ __('Cancel') }}</button>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>





@endsection


@push('scripts')
    <script src="{{ asset('js/survey/admin_dashboard.js')}}"></script>
@endpush

                
            