import Breadcrumb from '@/Components/Admin/Breadcrumb';
import Action from '@/Components/Admin/CustomTable/Partials/Action';
import Switch from '@/Components/Admin/CustomTable/Partials/Switch';
import Table from '@/Components/Admin/CustomTable/Table';
import CheckAbility from '@/Components/Admin/Permissions/CheckAbility';
import { Permission } from '@/constants/Permission';
import { getFilters, handleDataUpdate } from '@/helpers/crudHelper';
import { sweetAlert } from '@/helpers/sweet-alert';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout';
import { processDate } from '@/utils/admin/dateUtils';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { Head, Link, useForm } from '@inertiajs/react';
import debounce from 'lodash/debounce';
import { useState } from 'react';
import { useSelector } from 'react-redux';
import AddEditProductModal from './Partial/AddEditProductModal';

export default function ProductList({
    auth,
    products,
    pagination,
    success,
    error,
    uuid,
}) {

    const { theme } = useSelector((state) => state.themeConfig);
    const [showProductModal, setShowProductModal] = useState(false);
    const [editingProduct, setEditingProduct] = useState(null);

    // add category form
    const {
        data: productData,
        setData: setProductData,
        post: postProduct,
        errors: productErrors,
        processing: productProcessing,
        reset: resetProduct,
        clearErrors: clearProductErrors,
    } = useForm({
        id: '',
        code: '',
        name: '',
        description: '',
        base_price: '',
        is_active: true,
    });

   
    // list category form
    const {
        data,
        setData,
        post,
        transform,
        delete: destroy,
    } = useForm({
        sort: { created_at: 'desc' },
        filters: [],
        columnFilter: true,
        currentPage: 1,
        totalRecord: 0,
        offset: 10,
        page: 1,
    });

    // handle category submit
    const handleProductSubmit = (e) => {
        e.preventDefault();

        if (editingProduct) {
            postProduct(
                route('admin.products.update', editingProduct?.id),
                {
                    forceFormData: true,
                    preserveScroll: true,
                    onSuccess: () => {
                        resetProduct();
                        clearProductErrors();
                        setShowProductModal(false);
                        setEditingProduct(null);
                    },
                },
            );
        } else {
            postProduct(route('admin.products.store'), {
                forceFormData: true,
                preserveScroll: true,
                onSuccess: () => {
                    resetProduct();
                    clearProductErrors();
                    setShowProductModal(false);
                },
            });
        }
    };

    // handle product edit
    const handleProductEdit = (e, productId) => {
        const product = products?.data?.find(
            (item) => item.id === productId,
        );

        if (!product) {
            console.warn('Category not found: ', productId);
            return;
        }

        setProductData({
            id: product.id,
            code: product.code || '',
            name: product.name || '',
            description: product.description || '',
            base_price: product.base_price || '',
        });

        setEditingProduct(product); // set the product that editing
        setShowProductModal(true);
    };

    const handleSortChange = debounce((column) => {
        let currentSorting = Object.values(data.sort);

        let sortBy = currentSorting[0] === 'desc' ? 'asc' : 'desc';

        handleDataUpdate('sort', { [column]: sortBy }, setData, transform);

        post(route('admin.products.index'), {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    }, 300);

    const handleFilterChange = debounce((value, index) => {
        const column = cols[index].field;

        const filters = getFilters(data.filters, column, value);

        handleDataUpdate('filters', filters, setData, transform);

        post(route('admin.products.index'), {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    }, 300);

    const handlePageChange = (page) => {
        handleDataUpdate('page', page, setData, transform);

        post(route('admin.products.index'), {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    };

    const cols = [
        { field: 'sr_no', title: '#', sort: false, filter: false },
        {
            field: 'code',
            title: 'Code',
            sort: true,
            filter: true,
            type: 'string',
        },
        {
            field: 'name',
            title: 'Name',
            sort: true,
            filter: true,
            type: 'string',
        },
        {
            field: 'description',
            title: 'Description',
            sort: true,
            filter: true,
            type: 'string',
        },
        {
            field: 'base_price',
            title: 'Base Price',
            sort: true,
            filter: true,
            type: 'string',
        },
        {
            field: 'created_at',
            title: 'Created At',
            sort: true,
            filter: true,
            type: 'daterange',
        },
        {
            field: 'is_active',
            title: 'Status',
            sort: false,
            filter: true,
            type: 'select',
            value: '',
            filterOptions: [
                { value: '', label: 'All' },
                { value: true, label: 'Active' },
                { value: false, label: 'Inactive' },
            ],
        },
        ...(auth?.permissions?.includes(Permission.CATEGORY_ADD) ||
        auth?.permissions?.includes(Permission.CATEGORY_VIEW) ||
        auth?.permissions?.includes(Permission.CATEGORY_DELETE)
            ? [
                  {
                      field: 'action',
                      title: 'Action',
                      sort: false,
                      filter: false,
                      headerClass: 'justify-center',
                  },
              ]
            : []),
    ];

    const handleDelete = async (id) => {
        sweetAlert(
            true,
            {
                title: 'Are you sure you want to delete this product?',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'No, Keep',
                preConfirm: async () => {
                    destroy(route('admin.products.destroy', id));
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
            <Head title="Products List" />

            <Breadcrumb
                breadcrumbs={[
                    { to: '#', label: 'Products' },
                    { label: 'List' },
                ]}
            />

            <div className="pt-5">
                <div className="panel space-y-8">
                    <div className="mb-4.5 flex flex-col justify-between gap-5 md:flex-row md:items-center">
                        <h5 className="text-lg font-semibold text-dark dark:text-white-light">
                        Products List
                        </h5>

                        <div className="flex items-center gap-2">
                            <CheckAbility
                                auth={auth}
                                permission={Permission.PRODUCT_ADD}
                            >
                                <Link
                                    onClick={(e) => {
                                        e.preventDefault();
                                        setShowProductModal((prev) => !prev);
                                    }}
                                    className="btn btn-primary"
                                >
                                    <FontAwesomeIcon
                                        icon="fas fa-plus"
                                        className="mr-2 text-lg text-white"
                                    />
                                    Add Product
                                </Link>
                            </CheckAbility>
                           
                        </div>
                    </div>

                    <Table
                        data={products?.data}
                        filters={data}
                        headerData={cols}
                        currentPage={data.currentPage}
                        totalRecord={data.totalRecord}
                        currentSort={data.sort}
                        offset={data.offset}
                        columnFilter={data.columnFilter}
                        handleSortChange={handleSortChange}
                        handlePageChange={handlePageChange}
                        handleFilterChange={handleFilterChange}
                        pagination={pagination}
                    >
                        <Table.Cell field="created_at">
                            {({ value }) => processDate(value)}
                        </Table.Cell>

                        <Table.Cell field="is_active">
                            {({ value }) => (
                                <Switch
                                    auth={auth}
                                    permission={Permission.CATEGORY_EDIT}
                                    id={value.id}
                                    isActive={value.isActive}
                                    url={route(
                                        'admin.categories.change-status',
                                        value.id,
                                    )}
                                    title={`Are you sure you want to {{ACTION}} this category ?`}
                                />
                            )}
                        </Table.Cell>

                        <Table.Cell field="action">
                            {({ value }) => (
                                <>
                                    <Action
                                        auth={auth}
                                        show={{
                                            href: route(
                                                'admin.products.show',
                                                value.id,
                                            ),
                                            permission:
                                                Permission.PRODUCT_VIEW,
                                        }}
                                        edit={{
                                            permission:
                                                Permission.PRODUCT_EDIT,
                                            onClick: (e) =>
                                                handleProductEdit(e, value.id),
                                        }}
                                        destroy={{
                                            href: route(
                                                'admin.products.destroy',
                                                value.id,
                                            ),
                                            permission:
                                                Permission.PRODUCT_DELETE,
                                            onClick: () =>
                                                handleDelete(value.id),
                                        }}
                                    />
                                </>
                            )}
                        </Table.Cell>
                    </Table>
                </div>
            </div>

            {/* Product Modal */}
            <AddEditProductModal
                show={showProductModal}
                onClose={() => {
                    resetProduct();
                    clearProductErrors();
                    setShowProductModal(false);
                    setEditingProduct(null);
                }}
                data={productData}
                setData={setProductData}
                errors={productErrors}
                processing={productProcessing}
                handleSubmit={handleProductSubmit}
                editingProduct={editingProduct}
            />
        </AuthenticatedLayout>
    );
}
