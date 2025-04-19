@extends('layouts.app')
@section('content')
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <h2 class="page-title">My Account</h2>
        <div class="row">
            <div class="col-lg-3">
                <ul class="account-nav">
                @include('user.nav-menu-account')
                </ul>
            </div>
            <div class="col-lg-9">
                <div class="page-content my-account__dashboard">
                    <p>Hello <strong>{{ Auth::user()->name }}</strong></p>
                    <p>
                        From your account dashboard you can view your
                        <a class="unerline-link" href="{{ route('orders.index') }}">recent orders</a>,
                        manage your
                        <a class="unerline-link" href="{{ route('account.address') }}">shipping addresses</a>,
                        and
                        <a class="unerline-link" href="{{ route('account.details') }}">edit your password and account details</a>.
                    </p>
                </div>
            </div>

        </div>
    </section>
@endsection
