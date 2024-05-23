@extends('layouts.admin')
@section('title')
   حسابات الادمن
@endsection
@section('contentHeader')
الحسابات
@endsection
@section('contentHeaderLink')
    <a href="{{ route('adminPanelSetting.index') }}"> اضافة</a>
@endsection
@section('contentHeaderActive')
   حسابات المدراء
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card_title_center">إضافة  ادمن جديد</h4>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form action="{{ route('adminPanelSetting.store') }}" method="POST">
                <div class="row">
                    @csrf
                    <div class="form-group col-md-6">
                        <label> اسم الادمن</label>
                        <input type="text" name="name" class="form-control" id="name"
                            oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('name') }}'
                            placeholder="أدخل  اسم الادمن ">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label> ايميل الادمن</label>
                        <input type="text" name="email" class="form-control" id="email"
                            oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('email') }}'
                            placeholder="أدخل  ايميل الادمن ">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label> كلمة سر  الادمن</label>
                        <input type="text" name="password" class="form-control" id="password"
                            oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('password') }}'
                            placeholder="أدخل  اسم الادمن ">
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label> هاتف  الادمن</label>
                        <input type="text" name="phone" class="form-control" id="phone"
                            oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('phone') }}'
                            placeholder="أدخل  هاتف الادمن ">
                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label> عنوان  الادمن</label>
                        <input type="text" name="address" class="form-control" id="address"
                            oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('address') }}'
                            placeholder="أدخل  عنوان الادمن ">
                        @error('address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- <div class="form-group col-md-6">
                        <label>صندوق الادمن بالليرة</label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/,'')"
                         type="text" name="boxTl"
                            class="form-control" id="boxTl"
                             oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('boxTl') }}'
                            placeholder="أدخل  اسم الحساب المالي">
                        @error('boxTl')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>صندوق الادمن بالدولار</label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/,'')"
                         type="text" name="boxUsd"
                            class="form-control" id="boxUsd"
                             oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}"
                            value='{{ old('boxUsd') }}'
                            placeholder="أدخل  اسم الحساب المالي">
                        @error('boxUsd')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>                   --}}

                    <div class="form-group text-center col-md-12"> <button class="btn btn-success" type="submit"
                            id="do_add_item_card0">
                            اضافة ادمن جديد</button>
                        <a href="{{ route('branch.index') }}" class="btn btn-sm btn-danger">الغاء</a>

                    </div>
                </div>

            </form>

        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/customer.js') }}"></script>
@endsection
