<section>
    <div class="profile-helper-text">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </div>

    @if (session('status') === 'password-updated')
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ __('Password updated successfully.') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="form-group row">
            <label for="update_password_current_password" class="col-sm-4 col-form-label font-weight-normal text-muted">{{ __('Current Password') }}</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-unlock text-muted"></i></span>
                    </div>
                    <input id="update_password_current_password" name="current_password" 
                           type="password" class="form-control {{ $errors->updatePassword->has('current_password') ? 'is-invalid' : '' }} border-left-0" 
                           autocomplete="current-password" placeholder="••••••••">
                    @if($errors->updatePassword->has('current_password'))
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $errors->updatePassword->first('current_password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group row mt-4">
            <label for="update_password_password" class="col-sm-4 col-form-label font-weight-normal text-muted">{{ __('New Password') }}</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-lock text-muted"></i></span>
                    </div>
                    <input id="update_password_password" name="password" 
                           type="password" class="form-control {{ $errors->updatePassword->has('password') ? 'is-invalid' : '' }} border-left-0" 
                           autocomplete="new-password" placeholder="New Password">
                    @if($errors->updatePassword->has('password'))
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $errors->updatePassword->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group row mt-4">
            <label for="update_password_password_confirmation" class="col-sm-4 col-form-label font-weight-normal text-muted">{{ __('Confirm Password') }}</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-shield-alt text-muted"></i></span>
                    </div>
                    <input id="update_password_password_confirmation" name="password_confirmation" 
                           type="password" class="form-control {{ $errors->updatePassword->has('password_confirmation') ? 'is-invalid' : '' }} border-left-0" 
                           autocomplete="new-password" placeholder="Confirm Password">
                    @if($errors->updatePassword->has('password_confirmation'))
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $errors->updatePassword->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group row mt-4 mb-0">
            <div class="col-sm-8 offset-sm-4">
                <button type="submit" class="btn btn-warning px-4 shadow-sm font-weight-bold">
                    <i class="fas fa-key mr-1"></i> {{ __('Update Password') }}
                </button>
            </div>
        </div>
    </form>
</section>
