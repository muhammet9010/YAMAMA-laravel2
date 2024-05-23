<div class="modal fade" id="change" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">

      @if (session('success'))
          <div class="alert alert-success">
              {{ session('success') }}
          </div>
      @endif

      @if (session('error'))
          <div class="alert alert-danger">
              {{ session('error') }}
          </div>
      @endif

      <div class="modal-content p-3 p-md-5">
          <div class="modal-body">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              <div class="text-center mb-4">
                  <h3 class="mb-2">تغيير كلمة المرور</h3>
                  <p class="text-muted"></p>
              </div>
              <form id="changePasswordForm" method="POST" action="{{ route('adminPanelSetting.change', $data['id']) }}" class="row g-3">
                  @csrf
                  @method('PUT') <!-- Change method to PUT -->
                  <div class="col-12">
                      <label class="form-label" for="old_password">كلمة المرور القديمة</label>
                      <input type="password" class="form-control" id="old_password" name="old_password">
                      @error('old_password')
                          <span class="text-danger">{{ $message }}</span>
                      @enderror
                  </div>
                  <div class="col-12">
                      <label class="form-label" for="password">كلمة المرور الجديدة</label>
                      <input type="password" class="form-control" id="password" name="password">
                      @error('password')
                          <span class="text-danger">{{ $message }}</span>
                      @enderror
                  </div>
                  <div class="col-12">
                      <label class="form-label" for="password_confirmation">تأكيد كلمة المرور الجديدة</label>
                      <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                  </div>
              </div>
              <div class="col-12 text-center">
                  <button type="submit" class="btn btn-primary me-sm-3 me-1">حفظ</button>
                  <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">إلغاء</button>
              </div>
          </form>
      </div>
  </div>
</div>
