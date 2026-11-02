<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('vehicle') ?? $this->route('id');

        return [
            'vehicle_type' => ['required', 'string', 'max:255'],
            'plate_number' => ['nullable', 'string', 'max:50', Rule::unique('vehicles', 'plate_number')->ignore($id)],
            'vin' => ['nullable', 'string', 'max:50', Rule::unique('vehicles', 'vin')->ignore($id)],
            'manufacture_year' => ['nullable', 'integer', 'min:1950', 'max:' . ((int) date('Y') + 1)],
            'color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'driver_id' => ['nullable', 'exists:drivers,id'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_type.required' => 'نوع المركبة مطلوب.',
            'plate_number.unique' => 'رقم المركبة مسجل مسبقاً.',
            'vin.unique' => 'رقم الشاصي مسجل مسبقاً.',
            'color.regex' => 'اللون المختار غير صالح.',
            'image.image' => 'الملف المرفوع يجب أن يكون صورة.',
            'image.mimes' => 'صيغة الصورة غير مدعومة (jpg, jpeg, png, webp).',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}
