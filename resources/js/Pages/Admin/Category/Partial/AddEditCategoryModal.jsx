import PrimaryButton from '@/Components/Admin/Buttons/PrimaryButton';
import SecondaryButton from '@/Components/Admin/Buttons/SecondaryButton';
import ImageUploader from '@/Components/Admin/Form/ImageUploader';
import InputError from '@/Components/Admin/Form/InputError';
import InputLabel from '@/Components/Admin/Form/InputLabel';
import TextareaInput from '@/Components/Admin/Form/TextareaInput';
import TextInput from '@/Components/Admin/Form/TextInput';
import Modal from '@/Components/Admin/Modals/Modal';
import { useEffect, useState } from 'react';
import CategorySelect from './CategorySelect';

export default function AddEditCategoryModal({
    show,
    onClose,
    data,
    setData,
    errors,
    processing,
    handleSubmit,
    editingCategory,
}) {
    const [selectedCategory, setSelectedCategory] = useState(null);
    // set selected category when editing
    useEffect(() => {
        if (editingCategory?.parent_category) {
            setSelectedCategory({
                value: editingCategory.parent_category.id,
                label: editingCategory.parent_category.name,
            });
        } else {
            setSelectedCategory(null);
        }
    }, [editingCategory]);

    const handleCategoryChange = (category) => {
        setSelectedCategory(category);
        setData('parent_id', category.value);
    };

    const handleModalClose = () => {
        onClose();
        setSelectedCategory(null);
    };

    return (
        <Modal show={show} onClose={handleModalClose}>
            <div className="pt-5">
                <div className="panel space-y-8">
                    <div className="mb-4.5 flex flex-col justify-between gap-5 md:flex-row md:items-center">
                        <h5 className="text-lg font-semibold text-dark dark:text-white-light">
                            {editingCategory ? 'Edit Category' : 'Add Category'}
                        </h5>
                    </div>

                    <form onSubmit={handleSubmit}>
                        {/* Category Icon Uploader */}
                        <div className="flex w-full flex-col items-center">
                            <InputLabel
                                htmlFor="category_icon"
                                value="Icon"
                                required
                            />
                            <ImageUploader
                                id="category_icon"
                                prevImage={editingCategory?.icon_url}
                                onImageChange={(file) =>
                                    setData('category_icon', file)
                                }
                                containerClass="w-full max-w-xs aspect-square border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 flex items-center justify-center overflow-hidden"
                                uploadImgClass="w-full h-full object-cover"
                                prevImgClass="w-full h-full object-cover"
                            />
                            <InputError
                                message={errors.category_icon}
                                className="mt-2"
                            />
                        </div>
                        {/* Parent Category */}
                        {Object.keys(data).includes('parent_id') && (
                            <div className="mb-3">
                                <InputLabel
                                    htmlFor="parent_id"
                                    value="Parent Category"
                                    required
                                />

                                <CategorySelect
                                    selectedCategory={selectedCategory}
                                    handleCategoryChange={handleCategoryChange}
                                />

                                <InputError
                                    message={errors.parent_id}
                                    className="mt-2"
                                />
                            </div>
                        )}

                        {/* Name */}
                        <div className="mb-3">
                            <InputLabel htmlFor="name" value="Name" required />

                            <TextInput
                                id="name"
                                name="name"
                                value={data.name}
                                placeholder="Enter Category Name"
                                isFocused={true}
                                onChange={(e) =>
                                    setData('name', e.target.value)
                                }
                            />

                            <InputError
                                message={errors.name}
                                className="mt-2"
                            />
                        </div>

                        {/* Description */}
                        <div className="mb-3">
                            <InputLabel
                                htmlFor="description"
                                value="Description"
                                required
                            />

                            <TextareaInput
                                id="description"
                                name="description"
                                value={data?.description}
                                placeholder="Enter Description"
                                onChange={(e) =>
                                    setData('description', e.target.value)
                                }
                            />

                            <InputError
                                message={errors.description}
                                className="mt-2"
                            />
                        </div>

                        <div className="!mt-6 flex items-center justify-end gap-3">
                            <SecondaryButton
                                type="button"
                                onClick={handleModalClose}
                            >
                                Cancel
                            </SecondaryButton>

                            <PrimaryButton disabled={processing}>
                                {editingCategory ? 'Update' : 'Save'}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </Modal>
    );
}
