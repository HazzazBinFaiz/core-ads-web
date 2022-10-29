@extends($activeTemplate.'layouts.app')
@section('panel')
@php
    $authContent = getContent('authentication.content',true);
@endphp
<!-- Account Section -->
<section class="account-section position-relative">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7 col-md-8">
                <a href="{{ route('home') }}" class="text-center d-block mb-3 mb-sm-4 auth-page-logo"><img src="{{ getImage(getFilePath('logoIcon').'/logo_2.png') }}" alt="logo"></a>
                <form action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha account-form">
                    @csrf
                    <div class="mb-4">
                        <h4 class="mb-2">{{ __(@$authContent->data_values->register_title) }}</h4>
                        <p>{{ __(@$authContent->data_values->register_subtitle) }}</p>
                    </div>
                    <div class="row">
                        @if(session()->get('reference') != null)
                            <div class="col-12">
                                <p>@lang('You\'re referred by') <i class="fw-bold text--base">{{ session()->get('reference') }}</i></p>
                            </div>
                        @endif
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">@lang('Username')</label>
                                <input type="text" class="form-control form--control checkUser h-45" name="username"
                                    value="{{ old('username') }}" required>
                                <small class="text-danger usernameExist"></small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">@lang('E-Mail Address')</label>
                                <input type="email" class="form-control form--control h-45 checkUser" name="email"
                                    value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Country')</label>
                                <select name="country" class="form--control form-select">
                                    @foreach($countries as $key => $country)
                                        <option data-mobile_code="{{ $country->dial_code }}"
                                            value="{{ $country->country }}" data-code="{{ $key }}">
                                            {{ __($country->country) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Mobile')</label>
                                <div class="input-group ">
                                    <span class="input-group-text mobile-code">

                                    </span>
                                    <input type="hidden" name="mobile_code">
                                    <input type="hidden" name="country_code">
                                    <input type="number" name="mobile"
                                        value="{{ old('mobile') }}"
                                        class="form-control form--control checkUser" required>
                                </div>
                                <small class="text-danger mobileExist"></small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">@lang('Password')</label>
                                <input type="password" class="form-control form--control h-45" name="password"
                                    required>
                                @if($general->secure_password)
                                    <div class="input-popup">
                                        <p class="error lower">@lang('1 small letter minimum')</p>
                                        <p class="error capital">@lang('1 capital letter minimum')</p>
                                        <p class="error number">@lang('1 number minimum')</p>
                                        <p class="error special">@lang('1 special character minimum')</p>
                                        <p class="error minimum">@lang('6 character password')</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">@lang('Confirm Password')</label>
                                <input type="password" class="form-control form--control h-45"
                                    name="password_confirmation" required>
                            </div>
                        </div>
                        @if($general->agree)
                        @php
                            $policyPages = getContent('policy_pages.element',false,null,true);
                        @endphp
                        <div class="col-12">
                            <x-captcha></x-captcha>
                        </div>
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-2 justify-content-between">
                                <div class="form-group custom--checkbox">
                                    <input type="checkbox" id="agree" @checked(old('agree')) name="agree" class="form-check-input" required>
                                    <label for="agree">@lang('I agree with') @foreach($policyPages as $policy) <a
                                            href="{{ route('policy.pages',[slug($policy->data_values->title),$policy->id]) }}" class="link-color">{{ __($policy->data_values->title) }}</a>
                                        @if(!$loop->last), @endif @endforeach</label>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-12">

                            <button type="submit" class="btn btn--base w-100">@lang('Create Account')</button>
                        </div>
                        <div class="col-12 mt-4">
                            <p class="text-center">@lang('Already have an account?') <a href="{{ route('user.login') }}" class="fw-bold text--base">@lang('Login Account')</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- Account Section -->


<div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <h6 class="text-center">@lang('You already have an account please Login ')</h6>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">@lang('Close')</button>
                <a href="{{ route('user.login') }}" class="btn btn--base">@lang('Login')</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush
@push('script')
    <script>
      "use strict";
        (function ($) {
            @if($mobileCode)
            $(`option[data-code={{ $mobileCode }}]`).attr('selected','');
            @endif
            $('select[name=country]').change(function(){
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));
            @if($general->secure_password)
                $('input[name=password]').on('input',function(){
                    secure_password($(this));
                });
                $('[name=password]').focus(function () {
                    $(this).closest('.form-group').addClass('hover-input-popup');
                });
                $('[name=password]').focusout(function () {
                    $(this).closest('.form-group').removeClass('hover-input-popup');
                });
            @endif
            $('.checkUser').on('focusout',function(e){
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {mobile:mobile,_token:token}
                }
                if ($(this).attr('name') == 'email') {
                    var data = {email:value,_token:token}
                }
                if ($(this).attr('name') == 'username') {
                    var data = {username:value,_token:token}
                }
                $.post(url,data,function(response) {
                  if (response.data != false && response.type == 'email') {
                    $('#existModalCenter').modal('show');
                  }else if(response.data != false){
                    $(`.${response.type}Exist`).text(`${response.type} already exist`);
                  }else{
                    $(`.${response.type}Exist`).text('');
                  }
                });
            });
        })(jQuery);
    </script>
@endpush
