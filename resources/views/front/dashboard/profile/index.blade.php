@extends('front.layouts.app')
@section('content')
    @include('front.common.breadcrumb')
    <section class="login-seca-all-page">
        <div class="container">
            <div class="row">
                @include('front.dashboard.common.left-sidebar')
               
                <div class="col-lg-8 col-md-8">
                    <div class="supplier-wrapper-boxx">
                        <div class="supplier-profile-parent">
                            <div class="supplier-image-pro">
                                <img src="{{ imageUrl($users->image, 300, 300, 100, 1) }}" class="img-fluid"
                                     alt="image">
                            </div>

                            <div class="parent-for-right-side w-100">
                                <div class="right-side-parent-profile">
                                    <div class="inner-wrapper-content-profile">
                                        <div class="tittle-wrapper-22">
                                            @if ($users->isUser())
                                                <h2 class="profile-tittle-main">{{ $users->user_name }}</h2>
                                            @else
                                                <h2 class="profile-tittle-main">{{ $users->supplier_name['en'] }}</h2>
                                            @endif
                                        </div>
                                        @if($users->isUser())
                                            <ul class="ul-list-profile">
                                                <li class="li-list-sub">{{__('Email')}}: <span
                                                        class="sub-span-tittle">{{$user->email}}</span></li>
                                                <li class="li-list-sub">{{__('Phone No:')}} <span
                                                        class="sub-span-tittle"
                                                        dir="ltr">{{$user->phone}}</span>
                                                </li>
                                                <li class="li-list-sub">{{__('Address:')}} <span
                                                        class="sub-span-tittle">{{ $users->address }}</span></li>
                                            </ul>
                                        @else
                                            <ul class="ul-list-profile">
                                                <li class="suppleir-listing-li-items">
                                            <span class="svg-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10.5" height="14"
                                                     viewBox="0 0 10.5 14">
                                                    <path id="Path_48396" data-name="Path 48396"
                                                          d="M4.7,1.477a.627.627,0,0,0,.547.273A.627.627,0,0,0,5.8,1.477L7.629-1.148Q9-3.117,9.434-3.8a7.7,7.7,0,0,0,.848-1.654A4.977,4.977,0,0,0,10.5-7a5.067,5.067,0,0,0-.711-2.625,5.35,5.35,0,0,0-1.914-1.914A5.067,5.067,0,0,0,5.25-12.25a5.067,5.067,0,0,0-2.625.711A5.35,5.35,0,0,0,.711-9.625,5.067,5.067,0,0,0,0-7,4.977,4.977,0,0,0,.219-5.455,7.7,7.7,0,0,0,1.066-3.8q.438.684,1.8,2.652Q3.992.438,4.7,1.477ZM5.25-4.812a2.106,2.106,0,0,1-1.545-.643A2.106,2.106,0,0,1,3.063-7a2.106,2.106,0,0,1,.643-1.545A2.106,2.106,0,0,1,5.25-9.187a2.106,2.106,0,0,1,1.545.643A2.106,2.106,0,0,1,7.438-7a2.106,2.106,0,0,1-.643,1.545A2.106,2.106,0,0,1,5.25-4.812Z"
                                                          transform="translate(0 12.25)" fill="#45cea2"/>
                                                </svg>

                                            </span>
                                                    {{ $users->address }}
                                                </li>
                                                <li class="suppleir-listing-li-items">
                                            <span class="svg-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10.5" height="14"
                                                     viewBox="0 0 10.5 14">
                                                    <path id="Path_48396" data-name="Path 48396"
                                                          d="M4.7,1.477a.627.627,0,0,0,.547.273A.627.627,0,0,0,5.8,1.477L7.629-1.148Q9-3.117,9.434-3.8a7.7,7.7,0,0,0,.848-1.654A4.977,4.977,0,0,0,10.5-7a5.067,5.067,0,0,0-.711-2.625,5.35,5.35,0,0,0-1.914-1.914A5.067,5.067,0,0,0,5.25-12.25a5.067,5.067,0,0,0-2.625.711A5.35,5.35,0,0,0,.711-9.625,5.067,5.067,0,0,0,0-7,4.977,4.977,0,0,0,.219-5.455,7.7,7.7,0,0,0,1.066-3.8q.438.684,1.8,2.652Q3.992.438,4.7,1.477ZM5.25-4.812a2.106,2.106,0,0,1-1.545-.643A2.106,2.106,0,0,1,3.063-7a2.106,2.106,0,0,1,.643-1.545A2.106,2.106,0,0,1,5.25-9.187a2.106,2.106,0,0,1,1.545.643A2.106,2.106,0,0,1,7.438-7a2.106,2.106,0,0,1-.643,1.545A2.106,2.106,0,0,1,5.25-4.812Z"
                                                          transform="translate(0 12.25)" fill="#45cea2"/>
                                                </svg>
                                            </span>
                                                    {!! translate($users ?->city?->name) ?? '' !!}
                                                </li>
                                                <li class="suppleir-listing-li-items">
                                            <span class="svg-icon">
                                                <svg id="Icon" xmlns="http://www.w3.org/2000/svg" width="10.386"
                                                     height="15.084" viewBox="0 0 10.386 15.084">
                                                    <g id="Group_18" data-name="Group 18">
                                                        <path id="Path_29" data-name="Path 29"
                                                              d="M70.222,11.313a.365.365,0,0,1-.313-.167s0-.008-.007-.011a7.459,7.459,0,0,1-.812-3.593A7.454,7.454,0,0,1,69.9,3.95l.007-.011a.364.364,0,0,1,.313-.167V0c-2.083,0-3.771,3.377-3.771,7.542s1.688,7.542,3.771,7.542Z"
                                                              transform="translate(-66.451)" fill="#45cea2"/>
                                                        <path id="Path_30" data-name="Path 30"
                                                              d="M187.728.453A.453.453,0,0,0,187.276,0h-1.358V3.771h1.358a.453.453,0,0,0,.453-.453Z"
                                                              transform="translate(-181.694 0)" fill="#45cea2"/>
                                                        <path id="Path_31" data-name="Path 31"
                                                              d="M187.728,320.453a.453.453,0,0,0-.453-.453h-1.358v3.771h1.358a.453.453,0,0,0,.453-.453Z"
                                                              transform="translate(-181.694 -308.687)" fill="#45cea2"/>
                                                        <path id="Path_32" data-name="Path 32"
                                                              d="M237.684,176.154l-.566-.566a1.2,1.2,0,0,0,0-1.7l.566-.566a2,2,0,0,1,0,2.829Z"
                                                              transform="translate(-231.084 -167.197)" fill="#45cea2"/>
                                                        <path id="Path_33" data-name="Path 33"
                                                              d="M269.658,146.436l-.566-.566a2.8,2.8,0,0,0,0-3.958l.566-.566a3.6,3.6,0,0,1,0,5.09Z"
                                                              transform="translate(-261.928 -136.349)" fill="#45cea2"/>
                                                        <path id="Path_34" data-name="Path 34"
                                                              d="M301.65,116.707l-.566-.566a4.393,4.393,0,0,0,0-6.22l.566-.566a5.193,5.193,0,0,1,0,7.352Z"
                                                              transform="translate(-292.789 -105.489)" fill="#45cea2"/>
                                                    </g>
                                                </svg>

                                            </span>
                                                    <a href="" class="ancher-p"> {{ $users->phone }}</a>
                                                </li>
                                                <li class="suppleir-listing-li-items">
                                            <span class="svg-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12.883" height="9.662"
                                                     viewBox="0 0 12.883 9.662">
                                                    <path id="Path_50" data-name="Path 50"
                                                          d="M11.272,1.61H1.61L6.441,5.636ZM0,1.61A1.615,1.615,0,0,1,1.61,0h9.662a1.615,1.615,0,0,1,1.61,1.61V8.052a1.615,1.615,0,0,1-1.61,1.61H1.61A1.615,1.615,0,0,1,0,8.052Z"
                                                          fill="#45cea2" fill-rule="evenodd"/>
                                                </svg>
                                            </span>
                                                    {{ $users->email }}
                                                </li>
                                            </ul>
                                        @endif

                                    </div>

                                </div>
                                @if($users->isUser())
                                    <div class="butt-profile-dasboardd mt-29">
                                        <a href="{{ route('front.dashboard.edit.profile') }}"
                                           class="btn btn-primary w-100"> {{ __('Edit Profile') }}</a>
                                    </div>
                                @endif
                            </div>

                        </div>
                        @if(!$users->isUser())
                            <div class="supplier-about-parent-page">
                                <h2 class="about-tittle">{{__('About:')}}</h2>
                                <div class="desp-p-detail">
                                    <p> {!! translate($users->about) !!}</p>
                                </div>
                            </div>

                            <div class="trade-images-supplier">
                                <h3 class="trade-titleee">{{__('Trade License')}}</h3>
                                <div class="multi-trade-images-sup d-flex">
                                    @forelse($users->id_card_images as $image)
                                        <div class="image-block-trade">
                                            <a href="{!! imageUrl($image, 750,900,100, 1) !!}"
                                               data-lightbox="Profile Photo"
                                               class="open-light-box"
                                               data-title="Profile image">
                                                <img src="{{ imageUrl($image, 100, 75, 100, 1) }}" alt="trade-img"
                                                     class="img-fluid trade-img">
                                            </a>
                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                            </div>

                            <div class="butt-profile-dasboardd mt-29">
                                <a href="{{ route('front.dashboard.edit.profile') }}"
                                   class="btn btn-primary w-100">{{ __('Edit Profile') }}</a>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </section>

@endsection
