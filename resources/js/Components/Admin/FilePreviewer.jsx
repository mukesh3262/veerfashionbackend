import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import unidentified from '@image/unidentified.png';

export default function FilePreviewer({ fileUrl, fileName }) {
    const getFileExtension = (name) => name.split('.').pop().toLowerCase();
    const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(
        getFileExtension(fileName),
    );
    const fileThumbnail = isImage ? fileUrl : unidentified;

    return (
        <div className="flex w-[300px] items-center gap-3 rounded-xl bg-gray-100 p-3">
            <img
                src={fileThumbnail}
                alt="file thumbnail"
                className="h-10 w-10 rounded-lg object-cover"
            />

            <p className="truncate text-sm font-medium">{fileName}</p>

            <a
                href={fileUrl}
                download={fileName}
                className="ml-auto rounded-full p-1 text-gray-600 hover:text-blue-600"
                title="Download"
                target="_blank"
                rel="noopener noreferrer"
            >
                <FontAwesomeIcon
                    icon="fas fa-download"
                    className="mr-2 text-lg"
                />
            </a>
        </div>
    );
}
