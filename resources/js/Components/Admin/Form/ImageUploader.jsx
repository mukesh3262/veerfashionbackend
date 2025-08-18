import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
    forwardRef,
    useEffect,
    useImperativeHandle,
    useRef,
    useState,
} from 'react';

export default forwardRef(function ImageUploader(
    {
        id = 'file_input',
        prevImage = '',
        onImageChange = () => {},
        prevImgClass = '',
        uploadImgClass = '',
        containerClass = '',
        ...props
    },
    ref,
) {
    const [image, setImage] = useState(prevImage);
    const localRef = useRef(null);

    useImperativeHandle(ref, () => ({
        focus: () => localRef.current?.focus(),
    }));

    // Set initial preview image when `current_value` changes
    useEffect(() => {
        if (prevImage) {
            setImage(prevImage);
        }
    }, [prevImage]);

    // Handle file input change
    const handleImageChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onloadend = () => {
                setImage(reader.result); // Set preview immediately
            };
            reader.readAsDataURL(file);
            onImageChange(file); // Send selected file to parent component
        }
    };

    return (
        <div className={`flex items-center justify-center ${containerClass}`}>
            <div className={`relative`}>
                <input
                    type="file"
                    accept="image/*"
                    onChange={handleImageChange}
                    style={{ display: 'none' }}
                    id={props.id ?? id}
                    {...props}
                    ref={localRef}
                />
                <label htmlFor={props.id ?? id} className="cursor-pointer">
                    {image ? (
                        <div className="relative">
                            <img
                                src={image}
                                alt="Preview"
                                className={`h-20 w-20 rounded-full border-2 border-gray-300 object-fill md:h-24 md:w-24 lg:h-28 lg:w-28 ${prevImgClass}`}
                            />
                            {/* Pencil icon */}
                            <FontAwesomeIcon
                                icon="fas fa-pencil"
                                className="absolute bottom-1 right-1 cursor-pointer rounded-full border border-gray-300 bg-white p-1 text-gray-500"
                            />
                        </div>
                    ) : (
                        <div
                            className={`relative flex h-20 w-20 items-center justify-center rounded-full border-2 border-dashed border-gray-300 text-center text-gray-500 md:h-24 md:w-24 lg:h-28 lg:w-28 ${uploadImgClass}`}
                        >
                            <span>Click to upload image</span>
                            {/* Pencil icon */}
                            <FontAwesomeIcon
                                icon="fas fa-pencil"
                                className="absolute bottom-1 right-1 cursor-pointer rounded-full border border-gray-300 bg-white p-1 text-gray-500"
                            />
                        </div>
                    )}
                </label>
            </div>
        </div>
    );
});
