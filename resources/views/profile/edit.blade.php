@extends('layout.master')

@section('title')
    الحساب الشخصي
@endsection

@section('page_title')
    لوحة التحكم
@endsection

@section('sub_main')
    الحساب الشخصي
@endsection

@section('sub_title')
    تعديل بيانات الحساب
@endsection

@section('css')
    <style>
        .profile-summary {
            border: 1px solid #EBEDF3;
            border-radius: 8px;
            background: #ffffff;
        }

        .profile-avatar {
            width: 74px;
            height: 74px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #F3F6F9;
            color: #3699FF;
            font-size: 2rem;
            font-weight: 700;
        }

        .profile-form-card .card-header {
            min-height: 64px;
        }

        .profile-form-card .form-control:disabled {
            background-color: #F3F6F9;
            color: #7E8299;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xl-6">
                <div class="p-6 mb-7 profile-summary">
                    <div class="mb-6 d-flex align-items-center">
                        <div class="profile-avatar">
                            {{ mb_substr($user->employee_name ?? ($user->name ?? 'U'), 0, 1) }}
                        </div>
                        <div class="mr-5">
                            <div class="font-size-h4 font-weight-bolder text-dark">
                                {{ $user->employee_name ?? '-' }}
                            </div>
                            <div class="text-muted font-weight-bold">
                                {{ $user->email }}
                            </div>
                        </div>
                    </div>

                    <div class="my-5 separator separator-dashed"></div>

                    <div class="mb-4 d-flex justify-content-between">
                        <span class="text-muted font-weight-bold">الرقم الوظيفي</span>
                        <span class="text-dark font-weight-bolder">{{ $user->name }}</span>
                    </div>
                    <div class="mb-4 d-flex justify-content-between">
                        <span class="text-muted font-weight-bold">القسم</span>
                        <span
                            class="text-dark font-weight-bolder">{{ optional($user->section)->name_section ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted font-weight-bold">الشعبة</span>
                        <span class="text-dark font-weight-bolder">{{ optional($user->subSection)->name ?? '-' }}</span>
                    </div>
                </div>
            </div>
          <div class="col-xl-6">
                @include('profile.partials.delete-user-form')

            </div>
    
        </div>
        <div class="row">
    
        <div class="col-xl-6">
                @include('profile.partials.update-profile-information-form')

            </div>
  
                    <div class="col-xl-6">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                @include('profile.partials.recent-activity')
            </div>
        </div>
    </div>
@endsection
