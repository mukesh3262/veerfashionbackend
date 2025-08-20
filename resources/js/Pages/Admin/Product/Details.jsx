import Breadcrumb from '@/Components/Admin/Breadcrumb';
import Switch from '@/Components/Admin/CustomTable/Partials/Switch';
import { Permission } from '@/constants/Permission';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout';
import { processDate } from '@/utils/admin/dateUtils';
import { formatCurrency } from '@/utils/formatCurrency';
import { Head, Link } from '@inertiajs/react';
import { Eye } from 'lucide-react';
import { sweetAlert } from '@/helpers/sweet-alert';
import { useSelector } from 'react-redux';
import { router } from '@inertiajs/react';

export default function ProductDetail({ auth, product, success, error, uuid }) {
    const { theme } = useSelector((state) => state.themeConfig);

    const handleDelete = async (id) => {
        sweetAlert(
            true,
            {
                title: 'Are you sure you want to delete this product?',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'No, Keep',
                preConfirm: async () => {
                    router.delete(route('admin.products.destroy', id));
                },
            },
            theme,
        );
    };


    return (
        <AuthenticatedLayout
            auth={auth}
            success={success}
            error={error}
            uuid={uuid}
        >
            <Head title="Product Detail" />

            <Breadcrumb
                breadcrumbs={[
                    {
                        to: route('admin.products.index'),
                        label: 'Product',
                    },
                    { label: 'Detail' },
                ]}
            />

            <div className="flex items-center justify-end">
                <Link
                    href={route('admin.products.edit', product?.id)}
                    className="btn btn-primary mr-2"
                >
                    Edit
                </Link>
                <button
                    onClick={() => handleDelete(product?.id)}
                    className="btn btn-danger"
                >
                    Delete
                </button>
            </div>
            <div className="pt-6">
                <div className="grid grid-cols-1 gap-6">
                    <div className="panel rounded-2xl p-6 shadow-lg">
                        <div className="mb-6 flex items-center justify-between">
                            <h5 className="text-xl font-bold text-gray-600 dark:text-gray-400">
                                Product Information
                            </h5>
                        </div>

                        {/* Info Grid */}
                        <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <h6 className="text-sm text-gray-500">Name</h6>
                                <p className="text-lg font-semibold">
                                    {product?.name}
                                </p>
                            </div>

                            <div>
                                <h6 className="text-sm text-gray-500">
                                    Description
                                </h6>
                                <p className="text-gray-700">
                                    {product?.description}
                                </p>
                            </div>

                            <div>
                                <h6 className="text-sm text-gray-500">
                                    Status
                                </h6>
                                <Switch
                                    auth={auth}
                                    permission={Permission.PRODUCT_EDIT}
                                    id={product?.id}
                                    isActive={product?.is_active?.isActive}
                                    url={route(
                                        'admin.products.change-status',
                                        product?.id,
                                    )}
                                    title={`Are you sure you want to {{ACTION}} this product ?`}
                                />
                            </div>

                            <div>
                                <h6 className="text-sm text-gray-500">
                                    Created Date
                                </h6>
                                <p className="font-medium">
                                    {processDate(product?.created_at)}
                                </p>
                            </div>

                            <div>
                                <h6 className="text-sm text-gray-500">
                                    Base Price
                                </h6>
                                <p className="text-lg font-semibold text-indigo-600">
                                    {formatCurrency(product?.base_price)}
                                </p>
                            </div>

                            <div>
                                <h6 className="text-sm text-gray-500">
                                    Category
                                </h6>
                                <p className="font-medium">
                                    {product?.category?.name}{' '}
                                    <span className="text-gray-400">
                                        (
                                        {
                                            product?.category?.parent_category
                                                ?.name
                                        }
                                        )
                                    </span>
                                </p>
                            </div>
                        </div>

                        {/* Images */}
                        <div className="mt-8">
                            <h6 className="mb-3 text-sm text-gray-500">
                                Product Images
                            </h6>
                            <div className="grid grid-cols-2 gap-4 md:grid-cols-10">
                                {product?.product_images?.map((img) => (
                                    <div
                                        key={img.id}
                                        className="group relative aspect-square overflow-hidden rounded-lg border border-gray-200 shadow-sm"
                                    >
                                        <img
                                            src={img.image_url}
                                            alt={img.image || 'product'}
                                            className="h-full w-full object-contain p-2 transition-transform group-hover:scale-105"
                                        />
                                        <a
                                            href={img.image_url}
                                            target="_blank"
                                            rel="noreferrer"
                                            className="text-white"
                                        >
                                            <div className="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition group-hover:opacity-100">
                                                <Eye className="h-6 w-6" />
                                            </div>
                                        </a>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Variant Information */}
                        <div className="mb-6 mt-10 flex items-center justify-between">
                            <h5 className="text-xl font-bold text-gray-600 dark:text-gray-400">
                                Variant Information
                            </h5>
                        </div>

                        <div className="grid grid-cols-1 gap-6">
                            {product?.variants?.map((variant) => (
                                <div
                                    key={variant.id}
                                    className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:bg-gray-800"
                                >
                                    <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                                        <div>
                                            <h6 className="text-sm text-gray-500">
                                                SKU
                                            </h6>
                                            <p className="text-lg font-semibold">
                                                {variant?.sku}
                                            </p>
                                        </div>

                                        <div>
                                            <h6 className="text-sm text-gray-500">
                                                Brand
                                            </h6>
                                            <p className="font-medium">
                                                {variant?.brand}
                                            </p>
                                        </div>

                                        <div>
                                            <h6 className="text-sm text-gray-500">
                                                Attributes
                                            </h6>
                                            <ul className="list-disc pl-6 font-medium">
                                                {Object.entries(
                                                    variant?.attributes || {},
                                                ).map(([key, value]) => (
                                                    <li
                                                        key={key}
                                                    >{`${key.charAt(0).toUpperCase()}${key.slice(1)}: ${value}`}</li>
                                                ))}
                                            </ul>
                                        </div>

                                        <div>
                                            <h6 className="text-sm text-gray-500">
                                                Price
                                            </h6>
                                            <p className="text-lg font-semibold text-indigo-600">
                                                {formatCurrency(variant?.price)}
                                            </p>
                                        </div>

                                        <div>
                                            <h6 className="text-sm text-gray-500">
                                                Stock
                                            </h6>
                                            <p className="font-medium">
                                                {variant?.stock}
                                            </p>
                                        </div>
                                    </div>

                                    {/* Variant Images */}
                                    {variant?.variant_images?.length > 0 && (
                                        <div className="mt-6">
                                            <h6 className="mb-3 text-sm text-gray-500">
                                                Variant Images
                                            </h6>
                                            <div className="grid grid-cols-2 gap-4 md:grid-cols-10">
                                                {variant.variant_images.map(
                                                    (img) => (
                                                        <div
                                                            key={img.id}
                                                            className="group relative aspect-square overflow-hidden rounded-lg border border-gray-200 shadow-sm"
                                                        >
                                                            <img
                                                                src={
                                                                    img.image_url
                                                                }
                                                                alt={
                                                                    img.image ||
                                                                    'variant'
                                                                }
                                                                className="h-full w-full object-contain p-2 transition-transform group-hover:scale-105"
                                                            />
                                                            <a
                                                                href={
                                                                    img.image_url
                                                                }
                                                                target="_blank"
                                                                rel="noreferrer"
                                                                className="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition group-hover:opacity-100"
                                                            >
                                                                <Eye className="h-6 w-6 text-white" />
                                                            </a>
                                                        </div>
                                                    ),
                                                )}
                                            </div>
                                        </div>
                                    )}
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
