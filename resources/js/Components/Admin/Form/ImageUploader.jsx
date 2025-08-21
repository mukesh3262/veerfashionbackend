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
        <div className={`relative ${containerClass}`}>
            {/* Hidden input */}
            <input
                type="file"
                accept="image/*"
                onChange={handleImageChange}
                style={{ display: 'none' }}
                id={props.id ?? id}
                {...props}
                ref={localRef}
            />

            {/* Full clickable area */}
            <label
                htmlFor={props.id ?? id}
                className="cursor-pointer w-full h-full flex items-center justify-center"
            >
                {image ? (
                    <div className="relative w-full h-full">
                        <img
                            src={image}
                            alt="Preview"
                            className={`w-full h-full object-cover ${prevImgClass}`}
                        />
                        <FontAwesomeIcon
                            icon="fas fa-pencil"
                            className="absolute bottom-2 right-2 cursor-pointer rounded-full border border-gray-300 bg-white p-2 text-gray-500"
                        />
                    </div>
                ) : (
                    <div
                        className={`w-full h-full flex items-center justify-center text-gray-500 ${uploadImgClass}`}
                    >
                        <span>Click to upload image</span>
                    </div>
                )}
            </label>
        </div>
    );
});