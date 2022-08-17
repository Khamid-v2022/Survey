<!--begin::Aside-->
<div id="kt_aside" class="aside aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <!--begin::Brand-->
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">
        <!--begin::Logo-->
        <a href="{{ route('dashboard') }}">
            <img alt="Logo" src="{{ asset('images/logo.png') }}" class="h-75px logo" />
        </a>
        <!--end::Logo-->
        <!--begin::Aside toggler-->
        <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize" style="position: absolute; right: 25px; top: 8px">
            <span class="svg-icon svg-icon-1 rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.5" d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z" fill="currentColor" />
                    <path d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z" fill="currentColor" />
                </svg>
            </span>
        </div>
        <!--end::Aside toggler-->
    </div>
    <!--end::Brand-->
    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid">
        <!--begin::Aside Menu-->
        <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">
            <!--begin::Menu-->
            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true" data-kt-menu-expand="false">
                @if($user['role'] == 'Admin')
                    <div class="menu-item">
                        <a class="menu-link {{ Illuminate\Support\Facades\Route::is('admin_dashboard.index')?'active':'' }}" href="/admin_dashboard">
                            <span class="menu-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" />
                                    <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="currentColor" />
                                    <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="currentColor" />
                                    <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="currentColor" />
                                </svg>
                            </span>
                            <span class="menu-title">Company</span>
                            <span class="menu-arrow"></span>
                        </a>
                    </div>
                @else
                    <div class="menu-item">
                        <a class="menu-link {{ Illuminate\Support\Facades\Route::is('dashboard')?'active':'' }}" href="{{ route('dashboard') }}">
                            <span class="menu-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" />
                                    <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="currentColor" />
                                    <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="currentColor" />
                                    <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="currentColor" />
                                </svg>
                            </span>
                            <span class="menu-title">Dashboard</span>
                            <span class="menu-arrow"></span>
                        </a>
                    </div>
                    @if(in_array($user['role'], ['Company', 'Department', 'Program', 'Coach', 'Trainer']))
                    <div class="menu-item">
                        <a class="menu-link {{ Illuminate\Support\Facades\Route::is('user_management')?'active':'' }}" href="{{ route('user_management') }}">
                            <span class="menu-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16.0173 9H15.3945C14.2833 9 13.263 9.61425 12.7431 10.5963L12.154 11.7091C12.0645 11.8781 12.1072 12.0868 12.2559 12.2071L12.6402 12.5183C13.2631 13.0225 13.7556 13.6691 14.0764 14.4035L14.2321 14.7601C14.2957 14.9058 14.4396 15 14.5987 15H18.6747C19.7297 15 20.4057 13.8774 19.912 12.945L18.6686 10.5963C18.1487 9.61425 17.1285 9 16.0173 9Z" fill="currentColor"/>
                                    <rect opacity="0.3" x="14" y="4" width="4" height="4" rx="2" fill="currentColor"/>
                                    <path d="M4.65486 14.8559C5.40389 13.1224 7.11161 12 9 12C10.8884 12 12.5961 13.1224 13.3451 14.8559L14.793 18.2067C15.3636 19.5271 14.3955 21 12.9571 21H5.04292C3.60453 21 2.63644 19.5271 3.20698 18.2067L4.65486 14.8559Z" fill="currentColor"/>
                                    <rect opacity="0.3" x="6" y="5" width="6" height="6" rx="3" fill="currentColor"/>
                                </svg>
                            </span>
                            <span class="menu-title">Gebruikersbeheer</span>
                            <span class="menu-arrow"></span>
                        </a>
                    </div>
                    @endif
                    @if(in_array($user['role'], ['Company', 'Coach', 'Trainer']))
                        <div class="menu-item">
                            <a class="menu-link {{ Illuminate\Support\Facades\Route::is('trainee_management')?'active':'' }}" href="{{ route('trainee_management') }}">
                                <span class="menu-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16.0173 9H15.3945C14.2833 9 13.263 9.61425 12.7431 10.5963L12.154 11.7091C12.0645 11.8781 12.1072 12.0868 12.2559 12.2071L12.6402 12.5183C13.2631 13.0225 13.7556 13.6691 14.0764 14.4035L14.2321 14.7601C14.2957 14.9058 14.4396 15 14.5987 15H18.6747C19.7297 15 20.4057 13.8774 19.912 12.945L18.6686 10.5963C18.1487 9.61425 17.1285 9 16.0173 9Z" fill="currentColor"/>
                                        <rect opacity="0.3" x="14" y="4" width="4" height="4" rx="2" fill="currentColor"/>
                                        <path d="M4.65486 14.8559C5.40389 13.1224 7.11161 12 9 12C10.8884 12 12.5961 13.1224 13.3451 14.8559L14.793 18.2067C15.3636 19.5271 14.3955 21 12.9571 21H5.04292C3.60453 21 2.63644 19.5271 3.20698 18.2067L4.65486 14.8559Z" fill="currentColor"/>
                                        <rect opacity="0.3" x="6" y="5" width="6" height="6" rx="3" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span class="menu-title">Trainee</span>
                                <span class="menu-arrow"></span>
                            </a>
                        </div>

                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ Illuminate\Support\Facades\Route::is('enquetes')||Illuminate\Support\Facades\Route::is('distributie')?'show':'' }}">
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen022.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.2929 2.70711C11.6834 2.31658 12.3166 2.31658 12.7071 2.70711L15.2929 5.29289C15.6834 5.68342 15.6834 6.31658 15.2929 6.70711L12.7071 9.29289C12.3166 9.68342 11.6834 9.68342 11.2929 9.29289L8.70711 6.70711C8.31658 6.31658 8.31658 5.68342 8.70711 5.29289L11.2929 2.70711Z" fill="currentColor" />
                                            <path d="M11.2929 14.7071C11.6834 14.3166 12.3166 14.3166 12.7071 14.7071L15.2929 17.2929C15.6834 17.6834 15.6834 18.3166 15.2929 18.7071L12.7071 21.2929C12.3166 21.6834 11.6834 21.6834 11.2929 21.2929L8.70711 18.7071C8.31658 18.3166 8.31658 17.6834 8.70711 17.2929L11.2929 14.7071Z" fill="currentColor" />
                                            <path opacity="0.3" d="M5.29289 8.70711C5.68342 8.31658 6.31658 8.31658 6.70711 8.70711L9.29289 11.2929C9.68342 11.6834 9.68342 12.3166 9.29289 12.7071L6.70711 15.2929C6.31658 15.6834 5.68342 15.6834 5.29289 15.2929L2.70711 12.7071C2.31658 12.3166 2.31658 11.6834 2.70711 11.2929L5.29289 8.70711Z" fill="currentColor" />
                                            <path opacity="0.3" d="M17.2929 8.70711C17.6834 8.31658 18.3166 8.31658 18.7071 8.70711L21.2929 11.2929C21.6834 11.6834 21.6834 12.3166 21.2929 12.7071L18.7071 15.2929C18.3166 15.6834 17.6834 15.6834 17.2929 15.2929L14.7071 12.7071C14.3166 12.3166 14.3166 11.6834 14.7071 11.2929L17.2929 8.70711Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title">Enquete</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link {{ Illuminate\Support\Facades\Route::is('enquetes')?'active':'' }}" href="{{ route('enquetes') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Enquetes</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Illuminate\Support\Facades\Route::is('distributie')?'active':'' }}" href="{{ route('distributie') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Distributie</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                    @endif
                @endif
                <div class="menu-item">
                    <div class="menu-content">
                        <div class="separator mx-1 my-4"></div>
                    </div>
                </div>
                
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Aside Menu-->
    </div>
    <!--end::Aside menu-->
    <!--begin::Footer-->
    <div class="aside-footer flex-column-auto pt-5 pb-7 px-5" id="kt_aside_footer">
        <div class="menu-item">
            <div class="menu-content pb-2">
                <span class="menu-section text-success fs-6 fw-bolder">Coaching Support</span>
                <ul class="support-contact">
                    <li>
                        <span class="text-success fw-bolder ls-1">T:</span><span class="text-muted">0612345678</span>
                    </li>
                    <li>
                        <span class="text-success fw-bolder ls-1">E:</span><span class="text-muted">info@coachingsupport.nl</span>
                    </li>
                    <li>
                        <span class="text-success fw-bolder ls-1">W:</span><span class="text-muted">www.coachingsupport.nl</span>
                    </li>
                </ul>
                <div class="social-icons">
                    <a href="https://facebook.com" class="text-success" target="_black">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.5 0H2.5C1.12125 0 0 1.12125 0 2.5V17.5C0 18.8787 1.12125 20 2.5 20H17.5C18.8787 20 20 18.8787 20 17.5V2.5C20 1.12125 18.8787 0 17.5 0Z" fill="currentColor"/>
                            <path d="M16.875 10H13.75V7.5C13.75 6.81 14.31 6.875 15 6.875H16.25V3.75H13.75C11.6788 3.75 10 5.42875 10 7.5V10H7.5V13.125H10V20H13.75V13.125H15.625L16.875 10Z" fill="#FAFAFA"/>
                        </svg>
                    </a>
                    <a href="https://linkedin.com" class="text-success mx-2" target="_black">
                        <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_1625_88)">
                                <path d="M0 1.4727C0 0.681914 0.662031 0.0400391 1.47813 0.0400391H18.5219C19.3383 0.0400391 20 0.681914 20 1.4727V18.6076C20 19.3986 19.3383 20.04 18.5219 20.04H1.47813C0.662109 20.04 0 19.3987 0 18.6079V1.47246V1.4727Z" fill="currentColor"/>
                                <path d="M6.07791 16.7777V7.77358H3.0851V16.7777H6.07822H6.07791ZM4.58213 6.54444C5.62557 6.54444 6.27518 5.85303 6.27518 4.98897C6.25564 4.10522 5.62557 3.43311 4.60197 3.43311C3.57768 3.43311 2.90869 4.10522 2.90869 4.98889C2.90869 5.85295 3.55807 6.54436 4.56252 6.54436H4.58189L4.58213 6.54444ZM7.73447 16.7777H10.727V11.7499C10.727 11.4812 10.7466 11.2117 10.8256 11.0198C11.0419 10.4819 11.5343 9.92507 12.3613 9.92507C13.4439 9.92507 13.8773 10.7507 13.8773 11.9612V16.7777H16.8698V11.615C16.8698 8.84944 15.3935 7.56249 13.4245 7.56249C11.8103 7.56249 11.1013 8.46475 10.7074 9.07929H10.7273V7.77389H7.73463C7.77369 8.61858 7.73439 16.778 7.73439 16.778L7.73447 16.7777Z" fill="white"/>
                            </g>
                            <defs>
                                <clipPath id="clip0_1625_88">
                                    <rect width="20" height="20.0803" rx="4" fill="white"/>
                                </clipPath>
                            </defs>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="menu-item">
            <div class="menu-content">
                <div class="separator mx-1 my-4"></div>
            </div>
        </div>
        <div class="menu-item">
            <span class="btn-label">© Copyright 2022 by Solvware B.V.</span>
        </div>
    </div>
    <!--end::Footer-->
</div>
<!--end::Aside-->