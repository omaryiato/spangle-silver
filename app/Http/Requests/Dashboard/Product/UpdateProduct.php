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
        $id = $this->route('product');

        $rules = [
            'product_variants.*' => ['array:id,color_id,size_id,sku,stock,price,status'],
            'product_images.*' => ['array:image,is_primary,sort_order'],

            // Product
            'product_en_name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'product_en_name')
                    ->where('category_id', $this->input('category_id', $this->route('product')?->category_id))
                    ->ignore($id),
            ],

            'product_ar_name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'product_ar_name')
                    ->where('category_id', $this->input('category_id', $this->route('product')?->category_id))
                    ->ignore($id),
            ],

            'product_en_description' => [
                'sometimes',
                'nullable',
                'string',
                'max:1000',
            ],

            'product_ar_description' => [
                'sometimes',
                'nullable',
                'string',
                'max:1000',
            ],

            'product_material' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:lookup_values,id',
            ],

            'product_stone' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:lookup_values,id',
            ],

            'product_reels' => [
                'sometimes',
                'nullable',
                'file',
                'mimes:mp4',
                'max:51200',
            ],

            'product_price' => [
                'sometimes',
                'required',
                'numeric',
                'min:0',
            ],

            'product_status' => [
                'sometimes',
                'required',
                'integer',
                'in:0,1',
            ],

            'category_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:category,id',
            ],

            // Images
            'product_images' => [
                'sometimes',
                'nullable',
                'array', 'list', 'max:100',
            ],

            'product_images.*.image' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'product_images.*.is_primary' => [
                'nullable',
                'integer',
                'in:0,1',
            ],

            'product_images.*.sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            // Variants
            'product_variants.*.id' => ['sometimes', 'integer', 'distinct', Rule::exists('product_variants', 'id')->where('product_id', $id->getKey())],
            'product_variants' => [
                'sometimes',
                'nullable',
                'array', 'list', 'max:100',
            ],

            'product_variants.*.color_id' => [
                'nullable',
                'integer',
                'exists:lookup_values,id',
            ],

            'product_variants.*.size_id' => [
                'nullable',
                'integer',
                'exists:lookup_values,id',
            ],

            'product_variants.*.sku' => [
                'nullable',
                'string',
                'max:100',
                'distinct',

                // Rule::unique('product_variants', 'sku')
                //         ->ignore($id)
                //         ,
            ],

            'product_variants.*.stock' => [
                'sometimes',
                'required',
                'integer',
                'min:0',
            ],

            'product_variants.*.price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'product_variants.*.status' => [
                'sometimes',
                'required',
                'integer',
                'in:0,1',
            ],
        ];
        $submitted = $this->input('product_variants', []);
        $submitted = is_array($submitted) ? $submitted : [];
        $variantIds = [];
        foreach ($submitted as $variant) {
            if (is_array($variant) && filter_var($variant['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) !== false) {
                $variantIds[] = (int) $variant['id'];
            }
        }
        $ownedVariants = $id->variants()->whereIn('id', $variantIds)->get(['id'])->keyBy('id');
        foreach ($submitted as $index => $variant) {
            if (! is_int($index)) {
                continue;
            }
            if (! is_array($variant)) {
                continue;
            }
            $rules['product_variants.'.$index.'.sku'] = ['sometimes', 'nullable', 'string', 'max:100', 'distinct', Rule::unique('product_variants', 'sku')->ignore($ownedVariants->get(is_scalar($variant['id'] ?? null) ? (int) $variant['id'] : 0))];
            if (! isset($variant['id'])) {
                $rules['product_variants.'.$index.'.stock'] = ['required', 'integer', 'min:0'];
            }
        }

        return $rules;

    }
}
