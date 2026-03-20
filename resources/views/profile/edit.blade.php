@extends('layouts.app')

@section('title', 'Profile | ' . config('app.name'))

@push('styles')
<style>
    .profile-card-title { font-size: 1.1rem; font-weight: 600; }
    .profile-helper-text { font-size: 0.85rem; color: #6c757d; margin-bottom: 1.5rem; }
</style>
@endpush

@section('content')

{{-- Content Header --}}
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">User Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Profile Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>
{{-- /.content-header --}}

{{-- Main Content --}}
<section class="content">
    <div class="container-fluid">
        <div class="row">
            {{-- Left Column: Profile Info & Password --}}
            <div class="col-md-7">
                {{-- Update Profile Information --}}
                <div class="card card-primary card-outline shadow-sm">
                    <div class="card-header bg-white">
                        <h3 class="card-title profile-card-title text-primary">
                            <i class="fas fa-user-circle mr-2"></i>
                            Personal Information
                        </h3>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Update Password --}}
                <div class="card card-info card-outline shadow-sm mt-4">
                    <div class="card-header bg-white">
                        <h3 class="card-title profile-card-title text-info">
                            <i class="fas fa-key mr-2"></i>
                            Security Settings
                        </h3>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- Right Column: Side Info or Dangers --}}
            <div class="col-md-5">
                {{-- User Quick Info Card --}}
                <div class="card card-widget widget-user-2 shadow-sm">
                    <div class="widget-user-header bg-primary">
                        <div class="widget-user-image">
                            <div class="bg-white elevation-2 d-flex align-items-center justify-content-center" style="width:65px; height:65px; border-radius:50%;">
                                <i class="fas fa-user text-primary fa-2x"></i>
                            </div>
                        </div>
                        <h3 class="widget-user-username">{{ Auth::user()->name }}</h3>
                        <h5 class="widget-user-desc">Member since {{ Auth::user()->created_at->format('M d, Y') }}</h5>
                    </div>
                    <div class="card-footer p-0">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <span class="nav-link">
                                    Email <span class="float-right badge bg-primary">{{ Auth::user()->email }}</span>
                                </span>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link">
                                    Account Status <span class="float-right badge bg-success">Active</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Danger Zone --}}
                <!-- <div class="card card-danger card-outline shadow-sm mt-4">
                    <div class="card-header bg-white">
                        <h3 class="card-title profile-card-title text-danger font-weight-bold">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Danger Zone
                        </h3>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div> -->

            </div>
        </div>
    </div>
    {{-- /.container-fluid --}}
</section>
{{-- /.content --}}

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-fade alerts if any added by session status
        setTimeout(function() {
            $('.alert-success').fadeOut('slow');
        }, 3000);
    });
</script>
@endpush