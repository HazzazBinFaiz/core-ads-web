@extends('admin.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">

                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Matched')</th>
                                <th>@lang('Achieved At')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($matchingCommissionLogs as $log)
                                <tr>

                                    <td>
                                        <span class="fw-bold">{{ @$log->user->fullname }}</span>
                                        <br>
                                        <span class="small"> <a href="{{ route('admin.users.detail', $log->user_id) }}"><span>@</span>{{ @$log->user->username }}</a> </span>
                                    </td>

                                    <td>{{ $general->cur_sym }}{{ showAmount($log->amount) }}</td>
                                    <td>{{ $log->quantity }}</td>

                                    <td>
                                        {{showDateTime($log->created_at) }} <br> {{diffForHumans($log->created_at) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($matchingCommissionLogs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($matchingCommissionLogs) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
    @if(request()->routeIs('admin.report.rank'))
        <div class="d-flex flex-wrap justify-content-end gap-2 align-items-center">
            <x-search-form placeholder="Enter Username"></x-search-form>
        </div>
    @endif
@endpush
