<footer class="footer-sec-top just-use-ft-index">
    <div class="custom-div-border-news">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-four-box-ft">
                        <div class="four-side-footer-main">
                            <div class="inner-four-ma">
                                <div class="left-tittle-news-let">
                                    <h2 class="custom-heading-footer-s mb-11">{{__('Sign Up For Newsletters')}}</h2>
                                    <h3 class="get-email-tittle-ft">{{__('Get E-mail updates about our latest shop and special offers.')}}</h3>
                                </div>
                                <div class="footer-input-plus">
                                    <div class="input-style">
                                        <form action="{!! route('front.email.subscribe') !!}" method="post"
                                              id="subscribe-form">
                                            @csrf
                                            <input type="email" name="email_for_subscribe" required class="ctm-input"
                                                   placeholder="{{__('Enter email address & press enter')}}">
                                            <button type="submit" class="btn btn-primary">{{__('subscribe')}}</button>
                                            @include('front.common.alert', ['input' => 'email_for_subscribe'])
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">

        <div class="custom-clas-for-footer">
            <div class="main-left-box-1-ft">
                <div class="left-box-footer-area">
                    <h2 class="custom-heading-footer-s">{{__('Contact Us')}}</h2>
                    <div class="location-ph-mail">
                        <div class="box-img">
                            <div class="svg-box-wid">
                                <svg id="Group_24" data-name="Group 24" xmlns="http://www.w3.org/2000/svg" width="10"
                                     height="14.524" viewBox="0 0 10 14.524">
                                    <path id="Path_29" data-name="Path 29"
                                          d="M70.082,10.893a.352.352,0,0,1-.3-.161s0-.007-.007-.011a7.182,7.182,0,0,1-.782-3.459A7.177,7.177,0,0,1,69.774,3.8l.007-.011a.351.351,0,0,1,.3-.161V0c-2.005,0-3.631,3.251-3.631,7.262s1.626,7.262,3.631,7.262Z"
                                          transform="translate(-66.451)" fill="#45cea2"/>
                                    <path id="Path_30" data-name="Path 30"
                                          d="M187.661.436A.436.436,0,0,0,187.225,0h-1.307V3.631h1.307a.436.436,0,0,0,.436-.436Z"
                                          transform="translate(-181.851)" fill="#45cea2"/>
                                    <path id="Path_31" data-name="Path 31"
                                          d="M187.661,320.436a.436.436,0,0,0-.436-.436h-1.307v3.631h1.307a.436.436,0,0,0,.436-.436Z"
                                          transform="translate(-181.851 -309.107)" fill="#45cea2"/>
                                    <path id="Path_32" data-name="Path 32"
                                          d="M237.663,176.049l-.545-.545a1.156,1.156,0,0,0,0-1.634l.545-.545a1.926,1.926,0,0,1,0,2.724Z"
                                          transform="translate(-231.308 -167.425)" fill="#45cea2"/>
                                    <path id="Path_33" data-name="Path 33"
                                          d="M269.637,146.247l-.545-.545a2.692,2.692,0,0,0,0-3.811l.545-.545a3.462,3.462,0,0,1,0,4.9Z"
                                          transform="translate(-262.194 -136.534)" fill="#45cea2"/>
                                    <path id="Path_34" data-name="Path 34"
                                          d="M301.629,116.434l-.545-.545a4.23,4.23,0,0,0,0-5.989l.545-.545a5,5,0,0,1,0,7.079Z"
                                          transform="translate(-293.097 -105.632)" fill="#45cea2"/>
                                </svg>
                            </div>
                            <h3 class="tittle-name-cont-listi">{{__('Phone:')}}</h3>
                        </div>
                        <a href="tel:{{config('settings.contact_number')}}" rel="{{config('settings.contact_number')}}"
                           class="ft-ancher-" dir="ltr">{{config('settings.contact_number')}}</a>
                    </div>

                    <div class="location-ph-mail">
                        <div class="box-img">
                            <div class="svg-box-wid">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14">
                                    <path id="Path_48396" data-name="Path 48396"
                                          d="M13.535,4.262A1.246,1.246,0,0,1,14,5.273v7.164a1.307,1.307,0,0,1-1.312,1.313H1.313a1.266,1.266,0,0,1-.93-.383A1.266,1.266,0,0,1,0,12.438V5.246A1.268,1.268,0,0,1,.492,4.234q1.094-.9,3.691-2.953l.273-.246Q5.168.461,5.578.188A2.9,2.9,0,0,1,7-.25,2.9,2.9,0,0,1,8.422.188a10.611,10.611,0,0,1,1.121.848l.273.246Q12.551,3.441,13.535,4.262Zm-.848,8.012V5.328a.158.158,0,0,0-.055-.109Q10.418,3.414,9,2.293l-.273-.219a8.523,8.523,0,0,0-.848-.656A1.664,1.664,0,0,0,7,1.063a1.664,1.664,0,0,0-.875.355,8.523,8.523,0,0,0-.848.656L1.367,5.219a.11.11,0,0,0-.055.109v6.945a.158.158,0,0,0,.055.109.158.158,0,0,0,.109.055H12.523a.193.193,0,0,0,.164-.164Zm-.875-5.141-.41-.492a.3.3,0,0,0-.219-.123.34.34,0,0,0-.246.068L8.723,8.363a8.523,8.523,0,0,1-.848.656A1.664,1.664,0,0,1,7,9.375a1.664,1.664,0,0,1-.875-.355,8.523,8.523,0,0,1-.848-.656L3.063,6.586a.34.34,0,0,0-.246-.068.3.3,0,0,0-.219.123l-.41.492a.34.34,0,0,0-.068.246.3.3,0,0,0,.123.219L4.457,9.4a10.611,10.611,0,0,0,1.121.848A2.9,2.9,0,0,0,7,10.688a2.9,2.9,0,0,0,1.422-.437q.41-.273,1.121-.848l2.215-1.8a.3.3,0,0,0,.123-.219A.34.34,0,0,0,11.813,7.133Z"
                                          transform="translate(0 0.25)" fill="#45cea2"/>
                                </svg>
                            </div>
                            <h3 class="tittle-name-cont-listi">{{__('Email:')}}</h3>
                        </div>
                        <a href="mailto:{{config('settings.email')}}"
                           class="ft-ancher-">{{config('settings.email')}}</a>
                    </div>

                    <div class="location-ph-mail mrgin-set-ft  ">
                        <div class="box-img">
                            <div class="svg-box-wid">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10.5" height="14" viewBox="0 0 10.5 14">
                                    <path id="Path_48396" data-name="Path 48396"
                                          d="M4.7,14.477a.627.627,0,0,0,.547.273.627.627,0,0,0,.547-.273l1.832-2.625Q9,9.883,9.434,9.2a7.7,7.7,0,0,0,.848-1.654A4.977,4.977,0,0,0,10.5,6a5.067,5.067,0,0,0-.711-2.625A5.35,5.35,0,0,0,7.875,1.461,5.067,5.067,0,0,0,5.25.75a5.067,5.067,0,0,0-2.625.711A5.35,5.35,0,0,0,.711,3.375,5.067,5.067,0,0,0,0,6,4.977,4.977,0,0,0,.219,7.545,7.7,7.7,0,0,0,1.066,9.2q.438.684,1.8,2.652Q3.992,13.438,4.7,14.477ZM5.25,8.188A2.194,2.194,0,0,1,3.063,6,2.194,2.194,0,0,1,5.25,3.813,2.194,2.194,0,0,1,7.438,6,2.194,2.194,0,0,1,5.25,8.188Z"
                                          transform="translate(0 -0.75)" fill="#45cea2"/>
                                </svg>
                            </div>
                            <h3 class="tittle-name-cont-listi">{{__('Address:')}}</h3>
                        </div>
                        <a target="_blank"
                           href="https://www.google.com/maps/dir//{!! config('settings.latitude') !!},{!! config('settings.longitude') !!}/@ {!! config('settings.latitude') !!},{!! config('settings.longitude') !!},12z"
                           class="ft-ancher-">{{config('settings.address')}}</a>
                    </div>
                </div>
            </div>

            <div class="main-second-box-ft">
                <div class="second-box-footer-main">
                    <h2 class="custom-heading-footer-s">{{__('Information')}}</h2>
                    <ul class="ul-second-ft">
                        <li><a href="{{ route('front.pages', [config('settings.about_us')]) }}">{{__('About Us')}}</a>
                        </li>
                        <li><a href="#">{{__('Delivery Information')}}</a></li>
                        <li><a href="{{ route('front.pages', [config('settings.privacy_policy')]) }}"
                               target="_blank">{{__('Privacy Policy')}}</a></li>
                        <li><a target="_blank"
                               href="{{ route('front.pages', [config('settings.terms_and_conditions')]) }}">{{__('Terms & Conditions')}}</a>
                        </li>
                        <li><a href="#">{{__('Return Policy')}}</a></li>
                        <li><a href="#">{{__('Order History')}}</a></li>
                    </ul>
                </div>
            </div>

            <div class="main-third-box-ft">
                <div class="second-box-footer-main thrid-main-aea-ft">
                    <h2 class="custom-heading-footer-s">{{__('My Account')}}</h2>
                    <ul class="ul-second-ft">
                        <li><a href="{{route('front.dashboard.index')}}">{{__('My Account')}} </a></li>
                        <li><a href="#">{{__('Shopping Cart')}}</a></li>
                        <li><a href="#">{{__('Wishlist')}}</a></li>
                        <li><a href="#">{{__('Newsletter')}}</a></li>
                        <li><a href="#">{{__('Order History')}}</a></li>
                    </ul>
                </div>
            </div>


            <div class="main-third-box-ft-repeatt">
                <div class="second-box-footer-main thrid-main-aea-ft">
                    <h2 class="custom-heading-footer-s">{{__('Quick Links')}}</h2>
                    <ul class="ul-second-ft">
                        <li><a href="#">{{__('Products')}}</a></li>
                        <li><a href="{{route('front.categories')}}">{{__('Categories')}}</a></li>
                        <li><a href="#">{{__('Suppliers')}}</a></li>
                        <li><a href="{{route('front.gallery')}}">{{__('Gallery')}}</a></li>
                        <li><a href="{{ route('front.pages', 'contact-us') }}">{{__('Contact us')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<footer class="footer-bottom-2">
    <div class="container">
        <div class="row">
            <!-- bottom area -->
            <div class="col-md-12">
                <div class="bottom-area-footer-ma">
                    <p class="footer-p-end"><a href="">{{config('settings.company_name')}}</a> {{__('Copyright')}} ©
                        2022 {{config('settings.company_name')}}.
                        {{__('All Right Reserved.')}} </p>
                </div>
            </div>
        </div>
    </div>
</footer>


