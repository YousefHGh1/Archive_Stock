<div class="card card-custom gutter-b profile-form-card">
    <div class="border-0 card-header">
        <div class="card-title">
            <h3 class="card-label font-weight-bolder text-dark">معلومات الحساب</h3>
        </div>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="form">
        @csrf
        @method('patch')

        <div class="card-body">
            <div class="form-group row">
                <label for="name" class="col-lg-3 col-form-label font-weight-bold">الرقم الوظيفي</label>
                <div class="col-lg-8">
                    <input id="name" name="name" type="text"
                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}"
                        autocomplete="name" readonly>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="employee_name" class="col-lg-3 col-form-label font-weight-bold">الاسم</label>
                <div class="col-lg-8">
                    <input id="employee_name" name="employee_name" type="text"
                        class="form-control @error('employee_name') is-invalid @enderror"
                        value="{{ old('employee_name', $user->employee_name) }}" autocomplete="employee_name" required>
                    @error('employee_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-0 form-group row">
                <label for="email" class="col-lg-3 col-form-label font-weight-bold">البريد الإلكتروني</label>
                <div class="col-lg-8">
                    <input id="email" name="email" type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $user->email) }}" autocomplete="email" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                        <div class="mt-4 alert alert-custom alert-light-warning fade show" role="alert">
                            <div class="alert-icon"><i class="flaticon-warning"></i></div>
                            <div class="alert-text">
                                لم يتم التحقق من عنوان بريدك الإلكتروني.
                                <button form="send-verification" class="p-0 btn btn-link font-weight-bold"
                                    type="submit">
                                    إرسال رابط تحقق جديد
                                </button>

                                @if (session('status') === 'verification-link-sent')
                                    <div class="mt-2 text-success font-weight-bold">
                                        تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-8">
                    <button type="submit" class="mr-2 btn btn-success font-weight-bolder">حفظ</button>

                    @if (session('status') === 'profile-updated')
                        <span class="ml-3 text-success font-weight-bold">تم حفظ بيانات الحساب.</span>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>
