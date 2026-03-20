<section>
    <div class="profile-helper-text">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
    </div>

    {{-- Delete Button --}}
    <button type="button" class="btn btn-danger btn-block shadow-sm font-weight-bold" 
            data-toggle="modal" data-target="#confirm-user-deletion">
        <i class="fas fa-trash-alt mr-1"></i> {{ __('Delete Account') }}
    </button>

    {{-- Bootstrap Modal for Deletion Confirmation --}}
    <div class="modal fade" id="confirm-user-deletion" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title font-weight-bold" id="deleteModalLabel">
                            <i class="fas fa-exclamation-triangle mr-2"></i>{{ __('Confirm Account Deletion') }}
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-4">
                        <p class="text-dark font-weight-bold h6 mb-3">
                            {{ __('Are you sure you want to delete your account?') }}
                        </p>

                        <p class="text-muted text-sm mb-4">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>

                        <div class="form-group mb-0">
                            <label for="password" class="sr-only">{{ __('Password') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text border-right-0"><i class="fas fa-lock text-muted"></i></span>
                                </div>
                                <input id="password" name="password" type="password" 
                                       class="form-control {{ $errors->userDeletion->has('password') ? 'is-invalid' : '' }} border-left-0" 
                                       placeholder="{{ __('Type your password to confirm') }}"
                                       required>
                                @if($errors->userDeletion->has('password'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->userDeletion->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">
                            {{ __('Delete Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script to open modal if errors exist in deletion --}}
    @if ($errors->userDeletion->isNotEmpty())
        @push('scripts')
        <script>
            $(document).ready(function() {
                $('#confirm-user-deletion').modal('show');
            });
        </script>
        @endpush
    @endif
</section>
