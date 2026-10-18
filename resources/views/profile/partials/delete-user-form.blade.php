<div class="card card-custom gutter-b profile-form-card">
    <div class="border-0 card-header">
        <div class="card-title">
            <h3 class="card-label font-weight-bolder text-danger">حذف الحساب</h3>
        </div>
    </div>

    <div class="card-body">
        <div class="alert alert-custom alert-light-danger fade show mb-0" role="alert">
            <div class="alert-icon"><i class="flaticon-warning"></i></div>
            <div class="alert-text">
                بمجرد حذف حسابك سيتم حذف بياناته نهائيا. يرجى التأكد قبل تنفيذ هذه العملية.
            </div>
        </div>
    </div>

    <div class="card-footer">
        <button type="button" class="btn btn-danger font-weight-bolder" data-toggle="modal"
            data-target="#confirm-user-deletion">
            حذف الحساب
        </button>
    </div>
</div>

<div class="modal fade" id="confirm-user-deletion" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="post" action="{{ route('profile.destroy') }}" class="modal-content">
            @csrf
            @method('delete')

            <div class="modal-header">
                <h5 class="modal-title font-weight-bolder" id="deleteUserModalLabel">تأكيد حذف الحساب</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="text-muted font-weight-bold">
                    أدخل كلمة المرور لتأكيد حذف الحساب بشكل دائم.
                </p>

                <div class="form-group mb-0">
                    <label for="delete_password" class="font-weight-bold">كلمة المرور</label>
                    <input id="delete_password" name="password" type="password"
                        class="form-control @if ($errors->userDeletion->has('password')) is-invalid @endif"
                        placeholder="كلمة المرور">
                    @foreach ($errors->userDeletion->get('password') as $message)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @endforeach
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bolder" data-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-danger font-weight-bolder">حذف الحساب</button>
            </div>
        </form>
    </div>
</div>

@if ($errors->userDeletion->isNotEmpty())
    @section('scripts')
        <script>
            $(function() {
                $('#confirm-user-deletion').modal('show');
            });
        </script>
    @endsection
@endif
