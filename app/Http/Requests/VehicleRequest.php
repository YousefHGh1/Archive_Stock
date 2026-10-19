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
            'plate_number' => ['required', 'string', 'max:50', Rule::unique('vehicles', 'plate_number')->ignore($id)],
            'vin' => ['required', 'string', 'max:50', Rule::unique('vehicles', 'vin')->ignore($id)],
            'manufacture_year' => ['nullable', 'integer', 'min:1950', 'max:' . ((int) date('Y') + 1)],
            'color' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_type.required' => 'نوع المركبة مطلوب.',
            'plate_number.required' => 'رقم المركبة مطلوب.',
            'plate_number.unique' => 'رقم المركبة مسجل مسبقاً.',
            'vin.required' => 'رقم الشاصي مطلوب.',
            'vin.unique' => 'رقم الشاصي مسجل مسبقاً.',
            'image.image' => 'الملف المرفوع يجب أن يكون صورة.',
            'image.mimes' => 'صيغة الصورة غير مدعومة (jpg, jpeg, png, webp).',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}
