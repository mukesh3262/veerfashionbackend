import Breadcrumb from '@/Components/Admin/Breadcrumb';
import LinkButton from '@/Components/Admin/Buttons/LinkButton';
import PrimaryButton from '@/Components/Admin/Buttons/PrimaryButton';
import InputError from '@/Components/Admin/Form/InputError';
import InputLabel from '@/Components/Admin/Form/InputLabel';
import MultiImageUploader from '@/Components/Admin/Form/MultipleImageUploader';
import TextInput from '@/Components/Admin/Form/TextInput';
import TextAreaInput from '@/Components/Admin/Form/TextareaInput';
// import SwitchToggle from '@/Components/Admin/Form/SwitchToggle'; // create small toggle component
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';
import Select from 'react-select';

export default function ProductAddEdit({
    auth,
    product,
    success,
    error,
    uuid,
    categories,
    assignedCategory,
}) {

    const { data, setData, post, patch, errors, processing } = useForm({
        id: product?.id ?? '',
        name: product?.name ?? '',
        description: product?.description ?? '',
        base_price: product?.base_price ?? '',
        variants: product?.variants?.map(variant => ({
            ...variant,
            variant_images: variant?.variant_images?.map(img => img.image) ?? []
        })) ?? [
            {
                sku: '',
                brand: '',
                attributes: { color: '', size: '' },
                price: '',
                variant_images: [],
            },
        ],
        product_images: product?.product_images?.map(img => img.image) ?? [],
    });

    console.log(data);
    const [selectedCategory, setSelectedCategory] = useState(
        assignedCategory ?? null,
    );

    const handleSubmit = (e) => {

        e.preventDefault();
        if (product?.id) {
            post(route('admin.products.update', product?.id));
        } else {
            post(route('admin.products.store'));
        }
    };

    const addVariant = () => {
        setData('variants', [
            ...data.variants,
            {
                sku: '',
                brand: '',
                attributes: { color: '', size: '' },
                price: '',
                variant_images: [],
            },
        ]);
    };

    const removeVariant = (index) => {
        setData(
            'variants',
            data.variants.filter((_, i) => i !== index),
        );
    };

    const handleVariantChange = (index, field, value) => {
        const updated = [...data.variants];
        updated[index][field] = value;
        setData('variants', updated);
    };
    const handleVariantImageChange = (index, images) => {
        const updated = [...data.variants];
        updated[index].variant_images = images;
        setData('variants', updated);
    };

    const handleCategoryChange = (selectedOption) => {
        setData('category', selectedOption?.value);
        setSelectedCategory(selectedOption);
    };

    return (
        <AuthenticatedLayout
            auth={auth}
            success={success}
            error={error}
            uuid={uuid}
        >
            <Head title={`Products ${product?.id ? 'Edit' : 'Add'}`} />

            <Breadcrumb
                breadcrumbs={[
                    { to: route('admin.products.index'), label: 'Products' },
                    { label: product?.id ? 'Edit' : 'Add' },
                ]}
            />

            <div className="pt-5">
                <div className="panel space-y-8">
                    <div className="mb-4.5 flex flex-col justify-between gap-5 md:flex-row md:items-center">
                        <h5 className="text-lg font-semibold text-dark dark:text-white-light">
                            Products {product?.id ? 'Edit' : 'Add'}
                        </h5>
                    </div>

                    <form onSubmit={handleSubmit}>
                        {/* ================= Product Info ================= */}
                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            {/* Name */}
                            <div>
                                <InputLabel
                                    htmlFor="name"
                                    value="Name"
                                    required
                                />
                                <TextInput
                                    id="name"
                                    name="name"
                                    value={data.name}
                                    onChange={(e) =>
                                        setData('name', e.target.value)
                                    }
                                    placeholder="Enter product name"
                                />
                                <InputError
                                    message={errors.name}
                                    className="mt-2"
                                />
                            </div>

                            {/* Base Price */}
                            <div>
                                <InputLabel
                                    htmlFor="base_price"
                                    value="Base Price"
                                    required
                                />
                                <TextInput
                                    id="base_price"
                                    type="number"
                                    step="0.01"
                                    name="base_price"
                                    value={data.base_price}
                                    onChange={(e) =>
                                        setData('base_price', e.target.value)
                                    }
                                    placeholder="Enter base price"
                                />
                                <InputError
                                    message={errors.base_price}
                                    className="mt-2"
                                />
                            </div>

                            {/* Description */}
                            <div className="col-span-2">
                                <InputLabel
                                    htmlFor="description"
                                    value="Description"
                                    required
                                />
                                <TextAreaInput
                                    id="description"
                                    name="description"
                                    rows={5}
                                    value={data.description}
                                    onChange={(e) =>
                                        setData('description', e.target.value)
                                    }
                                    placeholder="Enter description"
                                />
                                <InputError
                                    message={errors.description}
                                    className="mt-2"
                                />
                            </div>

                            <div className="mb-3">
                                <InputLabel
                                    htmlFor="category"
                                    value="Category"
                                    required
                                />

                                <Select
                                    defaultValue={selectedCategory}
                                    value={selectedCategory}
                                    options={categories}
                                    isClearable={true}
                                    placeholder={'Select Category'}
                                    onChange={handleCategoryChange}
                                    noOptionsMessage={() => 'No Role Found'}
                                />

                                <InputError
                                    message={errors.category}
                                    className="mt-2"
                                />
                            </div>

                            <div className="mb-3">
                                <InputLabel value="Product Images" required />
                                <MultiImageUploader
                                    id="product_images_uploader"
                                    prevImages={data?.product_images ?? []} 
                                    onImagesChange={(files) =>
                                        setData('product_images', files)
                                    } 
                                    maxImages={3}
                                />

                                <InputError
                                    message={errors.product_images}
                                    className="mt-2"
                                />
                            </div>
                        </div>

                        {/* ================= Variants Section ================= */}
                        <div className="mt-8">
                            <h6 className="text-md mb-4 font-semibold">
                                Variants
                            </h6>

                            {data.variants.map((variant, index) => (
                                <div
                                    key={index}
                                    className={`mb-4 space-y-4 rounded-md border p-4 variant-${index}`}
                                >
                                    <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                                        <div>
                                            <InputLabel value="SKU" required />
                                            <TextInput
                                                value={variant.sku}
                                                onChange={(e) =>
                                                    handleVariantChange(
                                                        index,
                                                        'sku',
                                                        e.target.value,
                                                    )
                                                }
                                                placeholder="Unique SKU"
                                            />
                                            <InputError
                                                message={errors[`variants.${index}.sku`]}
                                                className="mt-2"
                                            />
                                        </div>

                                        <div>
                                            <InputLabel value="Brand" />
                                            <TextInput
                                                value={variant.brand}
                                                onChange={(e) =>
                                                    handleVariantChange(
                                                        index,
                                                        'brand',
                                                        e.target.value,
                                                    )
                                                }
                                                placeholder="Brand name"
                                            />
                                            <InputError
                                                message={errors[`variants.${index}.brand`]}
                                                className="mt-2"
                                            />
                                        </div>

                                        <div>
                                            <InputLabel
                                                value="Price"
                                                required
                                            />
                                            <TextInput
                                                type="number"
                                                step="0.01"
                                                value={variant.price}
                                                onChange={(e) =>
                                                    handleVariantChange(
                                                        index,
                                                        'price',
                                                        e.target.value,
                                                    )
                                                }
                                                placeholder="Variant price"
                                            />
                                            <InputError
                                                message={errors[`variants.${index}.price`]}
                                                className="mt-2"
                                            />
                                        </div>
                                        <div>
                                            <InputLabel value="Color" />
                                            <TextInput
                                                value={
                                                    variant.attributes?.color ||
                                                    ''
                                                }
                                                onChange={(e) => {
                                                    const updated = [
                                                        ...data.variants,
                                                    ];
                                                    updated[
                                                        index
                                                    ].attributes.color =
                                                        e.target.value;
                                                    setData(
                                                        'variants',
                                                        updated,
                                                    );
                                                }}
                                                placeholder="e.g. Red"
                                            />
                                            <InputError
                                                message={errors[`variants.${index}.attributes.color`]}
                                                className="mt-2"
                                            />
                                        </div>

                                        <div>
                                            <InputLabel value="Size" />
                                            <TextInput
                                                value={
                                                    variant.attributes?.size ||
                                                    ''
                                                }
                                                onChange={(e) => {
                                                    const updated = [
                                                        ...data.variants,
                                                    ];
                                                    updated[
                                                        index
                                                    ].attributes.size =
                                                        e.target.value;
                                                    setData(
                                                        'variants',
                                                        updated,
                                                    );
                                                }}
                                                placeholder="e.g. M"
                                            />
                                            <InputError
                                                message={errors[`variants.${index}.attributes.size`]}
                                                className="mt-2"
                                            />
                                        </div>
                                    </div>
                                    <div>
                                        <InputLabel value="Variant Images" />

                                        <MultiImageUploader
                                            id={`variant_images_uploader.${index}`}
                                            prevImages={
                                                variant.variant_images ?? []
                                            }
                                            onImagesChange={(files) =>
                                                handleVariantImageChange(
                                                    index,
                                                    files,
                                                )
                                            }
                                            maxImages={5} // you can limit per variant (ex. 3 variant_images max)
                                            containerClass="gap-2"
                                            imgClass="!w-[120px] !h-[120px] !rounded-lg"
                                        />
                                        <InputError
                                            message={
                                                errors[
                                                    `variants.${index}.variant_images`
                                                ]
                                            }
                                            className="mt-2"
                                        />
                                    </div>
                                    <button
                                        type="button"
                                        onClick={() => removeVariant(index)}
                                        className="mt-2 text-sm text-red-500"
                                    >
                                        Remove Variant
                                    </button>
                                </div>
                            ))}

                            <button
                                type="button"
                                onClick={addVariant}
                                className="mt-2 rounded-md bg-gray-200 px-3 py-1"
                            >
                                + Add Variant
                            </button>
                        </div>

                        {/* ================= Save / Cancel ================= */}
                        <div className="!mt-6 flex items-center justify-end gap-3">
                            <LinkButton href={route('admin.products.index')}>
                                Cancel
                            </LinkButton>
                            <PrimaryButton disabled={processing}>
                                Save
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
