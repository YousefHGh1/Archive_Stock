<div class="card card-custom gutter-b profile-form-card">
    <div class="border-0 card-header">
        <div class="card-title">
            <h3 class="card-label font-weight-bolder text-dark">تعديل كلمة المرور</h3>
        </div>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="form">
        @csrf
        @method('put')

        <div class="card-body">
            <div class="form-group row">
                <label for="current_password" class="col-lg-3 col-form-label font-weight-bold">كلمة المرور الحالية</label>
                <div class="col-lg-8">
                    <input id="current_password" name="current_password" type="password"
                        class="form-control @if ($errors->updatePassword->has('current_password')) is-invalid @endif"
                        autocomplete="current-password">
                    @foreach ($errors->updatePassword->get('current_password') as $message)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @endforeach
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-lg-3 col-form-label font-weight-bold">كلمة المرور الجديدة</label>
                <div class="col-lg-8">
                    <input id="password" name="password" type="password"
                        class="form-control @if ($errors->updatePassword->has('password')) is-invalid @endif"
                        autocomplete="new-password">
                    @foreach ($errors->updatePassword->get('password') as $message)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @endforeach
                </div>
            </div>

            <div class="form-group row mb-0">
                <label for="password_confirmation" class="col-lg-3 col-form-label font-weight-bold">تأكيد كلمة المرور</label>
                <div class="col-lg-8">
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        class="form-control @if ($errors->updatePassword->has('password_confirmation')) is-invalid @endif"
                        autocomplete="new-password">
                    @foreach ($errors->updatePassword->get('password_confirmation') as $message)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-8">
                    <button type="submit" class="mr-2 btn btn-primary font-weight-bolder">حفظ كلمة المرور</button>

                    @if (session('status') === 'password-updated')
                        <div class="alert alert-success mt-3" role="alert">
                            تم حفظ كلمة المرور بنجاح.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>
