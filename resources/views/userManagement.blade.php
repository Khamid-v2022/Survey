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
                            <div class="card-title">
                                {{-- <span class="card-label fw-bolder fs-3 mb-1">Users</span> --}}
                                <div class="d-flex align-items-center position-relative my-1 me-5">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <input type="text" data-kt-permissions-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search Permissions" />
                                </div>
                                <div class="d-flex align-items-center position-relative my-1 me-5 w-150px">
                                    <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Select a Role" name="target_assign" id="target_assign">
                                        <option value="">Select role...</option>
                                        <option value=" ">All</option>
                                        @foreach($roles as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Click to add a company">
                                <a href="#" class="btn btn-sm btn-success btn-active-success" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                                <span class="svg-icon svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                New User</a>
                            </div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body py-3">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table id="kt_datatable" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr class="fw-bolder text-muted">
                                            <th class="min-w-80px">Voornaam</th>
                                            <th class="min-w-80px">Achternaam</th>
                                            <th class="min-w-100px">Email</th>
                                            <th class="min-w-100px">Role</th>
                                            <th class="min-w-100px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                        @foreach($users as $item)
                                        <tr user_id = "{{ $item['id'] }}">
                                            <td>
                                                {{ $item['first_name'] }}
                                            </td>
                                            <td>
                                                {{ $item['last_name'] }}
                                            </td>
                                            <td>
                                                <a href="#" class="user-email">{{ $item['email'] }}</a>
                                            </td>
                                            <td>
                                                @switch($item['role'])
                                                    @case('department')
                                                        <span class="badge badge-light-danger fs-7 m-1 ms-0">{{ $item['role'] }}</span>
                                                        @break
                                                    @case('program')
                                                        <span class="badge badge-light-primary fs-7 m-1 ms-5">{{ $item['role'] }}</span>
                                                        @break
                                                    @case('coach')
                                                        <span class="badge badge-light-info fs-7 m-1 ms-10">{{ $item['role'] }}</span>
                                                        @break
                                                    @case('trainer')
                                                        <span class="badge badge-light-success fs-7 m-1 ms-15">{{ $item['role'] }}</span>
                                                        @break
                                                    @case('trainee')
                                                        <span class="badge badge-light-warning fs-7 m-1 ms-20">{{ $item['role'] }}</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-light-dark fs-7 m-1">{{ $item['role'] }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-end flex-shrink-0">
                                                    <label class="form-check form-switch form-check-custom form-check-solid me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Active">
                                                        <input class="form-check-input active-company-btn" type="checkbox" {{ $item['active']=='active'?'checked':'' }} />
                                                    </label>
                                                    
                                                    <a href="#" class="btn btn-icon btn-bg-light btn-active-color-success btn-sm delete-company-btn" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Delete">
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


<div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content rounded">
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
            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                <!--begin:Form-->
                <form id="kt_modal_new_target_form" class="form" action="#">
                    <!--begin::Heading-->
                    <div class="mb-13 text-center">
                        <!--begin::Title-->
                        <h1 class="mb-3"><span class="action-type">Add</span> User</h1>
                        <!--end::Title-->
                    </div>
                    <!--end::Heading-->
                    
                    <!--begin::Input group-->
                    <div class="row g-9 mb-8">
                        <input type="hidden" id="m_user_id" value="">
                        <!--begin::Col-->
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-bold mb-2">Role</label>
                            <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Select a Role" name="m_user_role" id="m_user_role">
                                {{-- @foreach($roles as $item) --}}
                                @for($i = 0; $i < count($roles); $i++)
                                    <option value="{{ $roles[$i] }}" {{ $i==0?'selected':'' }}>{{ $roles[$i] }}</option>
                                @endfor
                            </select>
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-bold mb-2">Organisatie</label>
                            <!--begin::Input-->
                            <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Select Organisatie" id="m_user_parent" name="m_user_parent">
                                <option value="{{ $user['id'] }}">{{ $user['first_name'] . ' ' . $user['last_name'] }} ({{ $user['role'] }})
                                </option>
                            </select>
                            <!--end::Input-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="flex-column mb-8 fv-row department-program">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                            <span class="required"><span id="m_name_type"></span>naam</span>
                        </label>
                        <!--end::Label-->
                        <input type="text" class="form-control form-control-solid" placeholder="" id="m_name" name="m_name" />
                    </div>
                    <!--end::Input group-->

                    <div class="row g-9 mb-8">
                        <!--begin::Col-->
                        <div class="col-md-6 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">Voornaam</span>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-solid" placeholder="" id="m_first_name" name="m_first_name" />
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-md-6 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">Achternaam</span>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-solid" placeholder="" id="m_last_name" name="m_last_name" />
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="d-flex flex-column mb-8 fv-row">
                        <label for="m_email" class="form-label fs-6 fw-bolder mb-3">
                            <span class="required">Email</span>
                        </label>
                        <input type="email" class="form-control form-control-lg form-control-solid" id="m_email" placeholder="Email Address" name="m_email" />
                    </div>

                    <div class="flex-column mb-8 fv-row coach-trainer">
                        <label for="m_address" class="form-label fs-6 fw-bolder mb-3">
                            Adres
                        </label>
                        <input type="text" class="form-control form-control-lg form-control-solid" id="m_address" name="m_address" placeholder="" />
                    </div>
                    <div class="row g-9 mb-8">
                        <!--begin::Col-->
                        <div class="col-md-6 fv-row  coach-trainer">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">Stad</label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-solid" placeholder="" id="m_city" name="m_city" />
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-md-6 fv-row  coach-trainer">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                Postcode
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-solid" placeholder="" id="m_post_code" name="m_post_code" />
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row g-9 mb-8">
                        <!--begin::Col-->
                        <div class="col-md-4 fv-row  coach-trainer">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">Nummer</label>
                            <!--end::Label-->
                            <input type="number" min=0 class="form-control form-control-solid" placeholder="" id="m_num_add" name="m_num_add" />
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-md-8 fv-row  coach-trainer">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                Telefoon
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-solid" placeholder="" id="m_tel" name="m_tel" />
                        </div>
                        <!--end::Col-->
                    </div>


                    <!--begin::Actions-->
                    <div class="text-center">
                        <button type="reset" id="kt_modal_new_target_cancel" data-dismiss="modal" class="btn btn-light me-3">Cancel</button>
                        <button type="submit" id="kt_modal_new_target_submit" class="btn btn-success">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end:Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>




@endsection


@push('scripts')
    <script src="{{ asset('js/survey/user_management.js')}}"></script>
@endpush

                
            