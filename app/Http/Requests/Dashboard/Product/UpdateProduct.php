<?php

namespace App\Http\Requests\Dashboard\Product;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;



class UpdateProduct extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        $id = $this->route("product_id");

        return [

            // Product
            'product_en_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'product_en_name')
                        ->ignore($id)
                        ->where('id', $this->input('id')),
            ],

            'product_ar_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'product_ar_name')
                        ->ignore($id)
                        ->where('id', $this->input('id')),
            ],

            'product_en_description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'product_ar_description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'product_material' => [
                'nullable',
                'integer',
                'exists:lookup_values,id',
            ],

            'product_stone' => [
                'nullable',
                'integer',
                'exists:lookup_values,id',
            ],

            'product_reels' => [
                'nullable',
                'file',
                'mimes:mp4',
                'max:50000',
            ],

            'product_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'product_status' => [
                'nullable',
                'integer',
                'in:0,1',
            ],

            'category_id' => [
                'required',
                'integer',
                'exists:category,id',
            ],


            // Images
            'images' => [
                'nullable',
                'array',
            ],

            'images.*.image' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'images.*.is_primary' => [
                'nullable',
                'integer',
                'in:0,1',
            ],

            'images.*.sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],


            // Variants
            'variants' => [
                'nullable',
                'array',
            ],

            'variants.*.color_id' => [
                'nullable',
                'integer',
                'exists:lookup_values,id',
            ],

            'variants.*.size_id' => [
                'nullable',
                'integer',
                'exists:lookup_values,id',
            ],

            'variants.*.sku' => [
                'nullable',
                'string',
                'max:100',
                'distinct',
                'unique:product_variants,sku',
                // Rule::unique('product_variants', 'sku')
                //         ->ignore($id)
                //         ->where('id', $this->input('id')),
            ],

            'variants.*.stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'variants.*.price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'variants.*.status' => [
                'nullable',
                'integer',
                'in:0,1',
            ],
        ];
    }

}
