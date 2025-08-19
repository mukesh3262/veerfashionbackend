import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { useState } from 'react';

export default function MultiImageUploader({
    id = 'multi_file_input',
    prevImages = [], // array of existing images (e.g., from DB)
    onImagesChange = () => {},
    maxImages = 5,
    containerClass = '',
    imgClass = '!w-[200px] !h-[150px] !rounded-lg !border-solid',
}) {
    const [images, setImages] = useState(prevImages);

    const handleImageChange = (e) => {
        const files = Array.from(e.target.files);

        if (images.length + files.length > maxImages) {
            alert(`You can only upload up to ${maxImages} images`);
            return;
        }

        const readers = files.map((file) => {
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.onloadend = () => resolve({ file, preview: reader.result });
                reader.readAsDataURL(file);
            });
        });

        Promise.all(readers).then((newImages) => {
            const updatedImages = [...images, ...newImages];
            setImages(updatedImages);
            onImagesChange(updatedImages.map((img) => img.file));
        });
    };

    const handleRemoveImage = (index) => {
        const updatedImages = images.filter((_, i) => i !== index);
        setImages(updatedImages);
        onImagesChange(updatedImages.map((img) => img.file));
    };

    return (
        <div className={`flex flex-wrap gap-4 ${containerClass}`}>
            {images.map((img, index) => (
                <div key={index} className="relative">
                    <img
                        src={img.preview || img}
                        alt="Preview"
                        className={`object-cover ${imgClass}`}
                    />
                    {/* Remove button */}
                    <button
                        type="button"
                        onClick={() => handleRemoveImage(index)}
                        className="absolute top-1 right-1 rounded-full bg-red-500 px-2 py-1 text-xs text-white"
                    >
                        ✕
                    </button>
                </div>
            ))}

            {/* Upload new image button */}
            {images.length < maxImages && (
                <label
                    htmlFor={id}
                    className={`flex cursor-pointer items-center justify-center border-2 border-dashed border-gray-300 text-gray-500 ${imgClass}`}
                >
                    + Add
                    <input
                        id={id}
                        type="file"
                        accept="image/*"
                        multiple
                        className="hidden"
                        onChange={handleImageChange}
                    />
                </label>
            )}
        </div>
    );
}
