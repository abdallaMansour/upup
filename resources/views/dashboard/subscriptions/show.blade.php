@extends('dashboard.layouts.master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('dashboard.partials.breadcrumb', [
        'items' => [
            ['label' => 'لوحة التحكم', 'url' => route('dashboard.index')],
            ['label' => 'الإشتراكات', 'url' => route('dashboard.subscriptions.index')],
            ['label' => 'تفاصيل الإشتراك'],
        ]
    ])
    <h4 class="mb-4">تفاصيل الإشتراك</h4>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">بيانات المستخدم</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="140">الاسم</th>
                            <td>{{ $subscription->user->name }}</td>
                        </tr>
                        <tr>
                            <th>البريد الإلكتروني</th>
                            <td>{{ $subscription->user->email }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ التسجيل</th>
                            <td>{{ $subscription->user->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">بيانات الباقة</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="140">الباقة</th>
                            <td>{{ $subscription->package->title }}</td>
                        </tr>
                        <tr>
                            <th>السعر الشهري</th>
                            <td>{{ config('ziina.currency') }} {{ number_format($subscription->package->monthly_price, 2) }}</td>
                        </tr>
                        <tr>
                            <th>السعر السنوي</th>
                            <td>{{ config('ziina.currency') }} {{ number_format($subscription->package->yearly_price, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">تفاصيل الإشتراك</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="180">الحالة</th>
                            <td>
                                @php
                                $statusClass = match($subscription->status) {
                                    'active' => 'bg-label-success',
                                    'pending' => 'bg-label-warning',
                                    'expired' => 'bg-label-secondary',
                                    'cancelled' => 'bg-label-danger',
                                    default => 'bg-label-secondary'
                                };
                                $statusAr = match($subscription->status) {
                                    'active' => 'نشط',
                                    'pending' => 'قيد الانتظار',
                                    'expired' => 'منتهي',
                                    'cancelled' => 'ملغي',
                                    default => $subscription->status
                                };
                                $periodAr = $subscription->period === 'monthly' ? 'شهري' : 'سنوي';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $statusAr }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>المبلغ المدفوع</th>
                            <td>{{ $subscription->currency }} {{ number_format($subscription->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>الفترة</th>
                            <td>{{ $subscription->period === 'monthly' ? 'شهري' : 'سنوي' }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ الانتهاء</th>
                            <td>{{ $subscription->expires_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>معرف الدفع زينه</th>
                            <td>{{ $subscription->ziina_payment_intent_id ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ الإنشاء</th>
                            <td>{{ $subscription->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ التحديث</th>
                            <td>{{ $subscription->updated_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
