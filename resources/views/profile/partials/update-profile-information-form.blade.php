<section>
    <div class="profile-helper-text">
        {{ __("Update your account's profile information and email address.") }}
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- Session error messages --}}
    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ __('Profile updated successfully.') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="form-group row">
            <label for="name" class="col-sm-3 col-form-label font-weight-normal text-muted">{{ __('Full Name') }}</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-user-tag text-muted"></i></span>
                    </div>
                    <input id="name" name="name" type="text" 
                           class="form-control @error('name') is-invalid @enderror border-left-0" 
                           value="{{ old('name', $user->name) }}" 
                           required autofocus autocomplete="name"
                           placeholder="Enter your full name">
                    @error('name')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group row mt-4">
            <label for="email" class="col-sm-3 col-form-label font-weight-normal text-muted">{{ __('Email Address') }}</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-envelope text-muted"></i></span>
                    </div>
                    <input id="email" name="email" type="email" 
                           class="form-control @error('email') is-invalid @enderror border-left-0" 
                           value="{{ old('email', $user->email) }}" 
                           required autocomplete="username"
                           placeholder="Email Address">
                    @error('email')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-3 p-2 bg-light rounded border">
                        <p class="text-sm mb-0">
                            {{ __('Your email address is unverified.') }}
                            <button form="send-verification" class="btn btn-link btn-sm p-0 m-0 align-baseline">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-success font-weight-bold text-sm mb-0">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="form-group row mt-4 mb-0">
            <div class="col-sm-9 offset-sm-3">
                <button type="submit" class="btn btn-primary px-4 shadow-sm font-weight-bold">
                    <i class="fas fa-save mr-1"></i> {{ __('Save Changes') }}
                </button>
            </div>
        </div>
    </form>
</section>
