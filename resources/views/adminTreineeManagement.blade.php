@extends('layouts.app')
@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid">
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
                                    <input type="text" data-kt-permissions-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="" />
                                </div>

                                <div class="d-flex align-items-center position-relative my-1 me-5 w-150px">
                                    <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Selecteer" name="target_assign" id="target_assign">
                                        <option value="">{{ __('Select') }}</option>
                                        <option value=" ">{{ __('All') }}</option>
                                        @foreach($companies as $item)
                                            <option value="{{ $item['name'] }}">{{ $item['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
                                            <th class="min-w-80px">{{ __('First name') }}</th>
                                            <th class="min-w-80px">{{ __('Last name') }}</th>
                                            <th class="min-w-100px">{{ __('Email') }}</th>
                                            <th class="min-w-100px">{{ __('Company') }}</th>
                                            <th class="min-w-100px">{{ __('Coach') }}</th>
                                            <th class="min-w-100px">{{ __('Trainer') }}</th>
                                            <th class="min-w-100px">{{ __('Creation date') }}</th>
                                            <th class="min-w-100px">{{ __('Action') }}</th>
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
                                                <a href="#" class="text-dark fs-6">{{ $item['company_name'] }}</a>
                                            </td>
                                            <td>
                                                <a href="#" class="text-dark fs-6">{{ $item['parent_name'] }}</a>
                                            </td>
                                            <td>
                                                <a href="#" class="text-dark fs-6">{{ $item['trainee_first'] . " " . $item['trainee_last'] }}</a>
                                            </td>
                                            <td>
                                                {{ $item['created_at'] }}
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1 edit-btn">
                                                    <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="{{ __('Edit') }}">
                                                            <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                    <!--end::Svg Icon-->
                                                </a>
                                                <a href="#" class="btn btn-icon btn-bg-light btn-active-color-success btn-sm delete-btn" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="verwijderen">
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
                <div class="mb-13 text-center">
                    <h1 class="mb-3"><span class="action-type">Toevoegen</span> Gebruiker</h1>
                </div>

                <!--begin:Form-->
                <form id="kt_modal_new_target_form" class="form" action="#">
                    <input type="hidden" id="m_user_id" value="">
                    <input type="hidden" id="action_type" value="Add">
                    <div class="row g-9 mb-8">
                        <!--begin::Col-->
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-bold mb-2">{{ __('Organisation') }}</label>
                            <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" name="m_company" id="m_company">
                                @for($i = 0; $i < count($companies); $i++)
                                    <option value="{{ $companies[$i]['id'] }}" {{ $i==0?'selected':'' }}>{{ $companies[$i]['name'] }}</option>
                                @endfor
                            </select>
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-bold mb-2">{{ __('Organisation Type') }}</label>
                            <input type="text" class="form-control form-control-solid" id="m_org_type"  value="" readonly/>
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row g-9 mb-8">
                        @if(isset($user['department_name']))
                            <!--begin::Col-->
                            <div class="col-md-6 fv-row department-field">
                        @else
                            <div class="col-md-6 fv-row department-field" style="display:none">
                        @endif
                                <label class="fs-6 fw-bold mb-2">Afdelingnaam</label>
                                <input type="text" class="form-control form-control-solid" readonly value={{ isset($user['department_name']) ? $user['department_name'] : "" }}  >
                            </div>
                            <!--end::Col-->
                        
                        @if(isset($user['program_name']))
                            <!--begin::Col-->
                            <div class="col-md-6 fv-row program-field">
                        @else
                            <div class="col-md-6 fv-row program-field" style="display:none">
                        @endif
                                <label class="fs-6 fw-bold mb-2">Opleidingsnaam</label>
                                <input type="text" class="form-control form-control-solid" readonly value={{ isset($user['program_name']) ?  $user['program_name'] : "" }}  >
                            </div>
                            <!--end::Col-->
                    </div>

                    <!--begin::Input group-->
                    <div class="row g-9 mb-8">
                        <!--begin::Col-->
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-bold mb-2">{{ __('Role') }}</label>
                            <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="{{ __('Select role...') }}" name="m_user_role" id="m_user_role">
                                {{-- @for($i = 0; $i < count($roles); $i++)
                                    @if($roles[$i] != "Company")
                                    <option value="{{ $roles[$i] }}" {{ $i==0?'selected':'' }}>{{ $roles[$i] }}</option>
                                    @endif
                                @endfor --}}
                                <option value="Trainee" selected>Trainee</option>
                            </select>
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        {{-- @if($user['role'] == 'Coach' || $user['role'] == 'Trainer')
                            <div class="col-md-6 fv-row parent-div" style="display: none">
                        @else
                            <div class="col-md-6 fv-row parent-div">
                        @endif --}}
                            <div class="col-md-6 fv-row parent-div">
                                <label class="required fs-6 fw-bold mb-2 parent-org-name">Parent Org</label>
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

                     <!--begin:: Trainer selection for Trainee input opti-->
                     <div class="flex-column mb-8 fv-row trainer-div">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Trainer Naam</label>
                        <!--end::Label-->
                        <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Select Trainer" id="m_trainer" name="m_trainer">
                            {{-- <option value="{{ $user['id'] }}">{{ $user['first_name'] . ' ' . $user['last_name'] }} ({{ $user['role'] }})
                            </option> --}}
                        </select>
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
                                <span class="required">{{ __('First name') }}</span>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-solid" placeholder="" id="m_first_name" name="m_first_name" />
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-md-6 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">{{ __('Last name') }}</span>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-solid" placeholder="" id="m_last_name" name="m_last_name" />
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="d-flex flex-column mb-8 fv-row">
                        <label for="m_email" class="form-label fs-6 fw-bolder mb-3">
                            <span class="required">{{ __('Email') }}</span>
                        </label>
                        <input type="email" class="form-control form-control-lg form-control-solid" id="m_email" placeholder="Email Address" name="m_email" />
                    </div>

                    <div class="flex-column mb-8 fv-row coach-trainer">
                        <label for="m_address" class="form-label fs-6 fw-bolder mb-3">
                            {{ __('Address') }}
                        </label>
                        <input type="text" class="form-control form-control-lg form-control-solid" id="m_address" name="m_address" placeholder="" />
                    </div>
                    <div class="row g-9 mb-8">
                        <!--begin::Col-->
                        <div class="col-md-6 fv-row  coach-trainer">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">{{ __('City') }}</label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-solid" placeholder="" id="m_city" name="m_city" />
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-md-6 fv-row  coach-trainer">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                {{ __('Postcode') }}
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
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">{{ __('Number') }}</label>
                            <!--end::Label-->
                            <input type="number" min=0 class="form-control form-control-solid" placeholder="" id="m_num_add" name="m_num_add" />
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-md-8 fv-row  coach-trainer">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                {{ __('Tel') }}
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control form-control-solid" placeholder="" id="m_tel" name="m_tel" />
                        </div>
                        <!--end::Col-->
                    </div>


                    <!--begin::Actions-->
                    <div class="text-center">
                        <button type="reset" id="kt_modal_new_target_cancel" data-dismiss="modal" class="btn btn-light me-3">{{ __('Cancel') }}</button>
                        <button type="submit" id="kt_modal_new_target_submit" class="btn btn-success">
                            <span class="indicator-label">{{ __('Submit') }}</span>
                            <span class="indicator-progress">{{ __('Please wait...') }}
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
    <script src="{{ asset('js/survey/admin_treinee_management.js')}}"></script>
@endpush

                
            