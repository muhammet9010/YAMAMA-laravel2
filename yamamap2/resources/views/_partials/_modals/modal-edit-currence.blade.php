<!-- Edit User Modal -->

<div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
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
                    <h3 class="mb-2">تعديل معلومات المدير</h3>
                    <p class="text-muted"></p>
                </div>
                <form id="editUserForm" method="POST" action="{{ route('currency-exchange.update',  optional($exchanges)->id) }}"
                    class="row g-3">

                    @method('put')
                    @csrf

                    <div class="col-12">
                        <label class="form-label" for="modalEditUserName">الاسم</label>
                        <input type="text" id="modalEditUserName" name="name" class="form-control"
                            oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('name', $data['name']) }}'
                            placeholder="أدخل  اسم لادمن" />
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>



                    <div class="col-12 ">
                        <label class="form-label" for="modalEditUserEmail">الايميل</label>
                        <input type="text" id="modalEditUserEmail" name="email" class="form-control"
                            oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('email', $data['email']) }}'
                            placeholder="example@gmail.com" />
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="modalEditUseraddress">العنوان</label>
                        <input type="text" id="modalEditUseraddress" name="address" class="form-control"
                            oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}"
                            value='{{ old('address', $data['address']) }}' placeholder="أدخل  عنوان الادمن">

                        @error('address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-12 ">
                        <label class="form-label" for="modalEditUserPhone">رقم الهاتف </label>
                        <div class="input-group">
                            <input type="text" id="modalEditUserPhone" name="phone"
                                class="form-control phone-number-mask" id="phone"
                                oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                                onchange="try{setCustomValidity('')}catch(e){}"
                                value='{{ old('phone', $data['phone']) }}' placeholder="أدخل  هاتف الادمن">
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 ">
                        <label class="form-label" for="modalEditUserPhone">كلمة سر الادمن </label>
                        <div class="input-group">
                            <input type="text" name="password" class="form-control" id="password"
                                oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                                onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('password') }}'
                                placeholder="ادخل كلمة السر ان اردت تغيرها">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">حفظ</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">الغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ Edit User Modal -->
