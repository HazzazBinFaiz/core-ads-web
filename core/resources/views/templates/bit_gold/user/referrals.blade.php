@extends($activeTemplate.'layouts.master')
@section('content')
<style>
    .inverse {
        -webkit-filter: grayscale(100%); /* Safari 6.0 - 9.0 */
        filter: grayscale(100%);
    }
</style>
<div class="cmn-section">
    <div class="container">
        <div class="card">
            <div class="card-body">
                @if(auth()->user()->referrer)
                    <h4 class="mb-2">@lang('You are referred by') {{ auth()->user()->referrer->fullname }}</h4>
                @endif
                <div class="col-md-12 mb-4">
                    <label>@lang('Referral Link')</label>
                    <div class="input-group">
                        <input type="text" name="text" class="form-control form--control referralURL"
                            value="{{ route('home') }}?reference={{ auth()->user()->username }}" readonly>
                            <span class="input-group-text copytext copyBoard" id="copyBoard"> <i class="fa fa-copy"></i> </span>
                    </div>
                </div>
                @if($user->allReferrals->count() > 0 && $maxLevel > 0)
                <div class="treeview-container">
                    <ul class="treeview">
                      <li class="items-expanded"> {{ $user->fullname }} ( {{ $user->username }} )
                            @include($activeTemplate.'partials.under_tree',['user'=>$user,'layer'=>0,'isFirst'=>true])
                        </li>
                    </ul>
                </div>
                @endif
            </div>
        </div>

        <div class="row mt-50">
            <div class="col-lg-12">
                <div class="table-responsive--md">
                    <table class="table style--two">
                        <thead>
                        <tr>
                            <th>@lang('Date')</th>
                            <th>@lang('Transaction ID')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Wallet')</th>
                            <th>@lang('Details')</th>
                            <th>@lang('Post Balance')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($transactions as $trx)
                            <tr>
                                <td>
                                    {{ showDatetime($trx->created_at,'d/m/Y') }}
                                </td>
                                <td><span
                                        class="text-primary">{{ $trx->trx }}</span></td>

                                <td>
                                    @if($trx->trx_type == '+')
                                        <span class="text-success">+
                                                        {{ __($general->cur_sym) }}{{ getAmount($trx->amount) }}</span>
                                    @else
                                        <span class="text-danger">-
                                                        {{ __($general->cur_sym) }}{{ getAmount($trx->amount) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($trx->wallet_type == 'deposit_wallet')
                                        <span class="badge bg-info">@lang('Deposit Wallet')</span>
                                    @else
                                        <span class="badge bg-primary">@lang('Interest Wallet')</span>
                                    @endif
                                </td>
                                <td>{{ $trx->details }}</td>
                                <td><span>
                                                    {{ __($general->cur_sym) }}{{ getAmount($trx->post_balance) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center">
                                    {{ __('No Transaction Found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style-lib')
    <link href="{{ asset('assets/global/css/jquery.treeView.css') }}" rel="stylesheet" type="text/css">
@endpush

@push('script')
<script src="{{ asset('assets/global/js/jquery.treeView.js') }}"></script>
<script>
    (function($){
    "use strict"
        $('.treeview').treeView();
        $('.copyBoard').click(function(event){
            var parent = event.target.parentElement;
            while (parent.tagName !== 'DIV') {
                parent = parent.parentElement
            }
            var copyText = parent.querySelector(".referralURL");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            copyText.blur();
            this.classList.add('copied');
            setTimeout(() => this.classList.remove('copied'), 1500);
        });
        $(function () {
            $('[data-toggle="tree-tooltip"]').tooltip({
                html: true
            })
        })
    })(jQuery);
</script>
@endpush
