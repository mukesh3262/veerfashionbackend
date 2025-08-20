import { useEffect, useMemo, useState } from 'react';

export default function MultiImageUploader({
  id = 'multi_file_input',
  prevImages = [],                  // existing images: [{id, url}] or [{id, image}] or ["url"]
  onImagesChange = () => {},        // called with ONLY new File[] (no existing urls)
  onDeletedExistingChange = () => {}, // called with deleted existing ids: (number[]|string[])
  maxImages = 5,
  containerClass = '',
  imgClass = '!w-[200px] !h-[150px] !rounded-lg !border-solid',
}) {
  // Normalize incoming existing images into a single internal shape
  const normalizePrev = (arr) =>
    (arr || [])
      .map((img) => {
        if (typeof img === 'string') {
          return { kind: 'existing', id: null, url: img }; // no id → can't report deletion id
        }
        if (img && typeof img === 'object') {
          if (img.url) return { kind: 'existing', id: img.id ?? null, url: img.url };
          if (img.image) return { kind: 'existing', id: img.id ?? null, url: img.image };
          if (img.file) {
            // already a new item-like shape
            return {
              kind: 'new',
              file: img.file,
              preview: img.preview ?? URL.createObjectURL(img.file),
            };
          }
        }
        return null;
      })
      .filter(Boolean);

  const [items, setItems] = useState(normalizePrev(prevImages));
  const [deletedIds, setDeletedIds] = useState([]);

  // If parent updates prevImages, sync and reset deleted list
  useEffect(() => {
    setItems(normalizePrev(prevImages));
    setDeletedIds([]);
  }, [JSON.stringify(prevImages)]);

  // Emit helpers
  const emitChange = (nextItems, nextDeleted) => {
    const newFiles = nextItems.filter(i => i.kind === 'new').map(i => i.file);
    onImagesChange(newFiles);                 // only Files
    onDeletedExistingChange(nextDeleted);     // ids of removed existing images
  };

  const handleImageChange = (e) => {
    const files = Array.from(e.target.files || []);
    const totalIfAdded = items.length + files.length;
    if (totalIfAdded > maxImages) {
      alert(`You can only upload up to ${maxImages} images`);
      return;
    }

    const readers = files.map((file) => new Promise((resolve) => {
      const reader = new FileReader();
      reader.onloadend = () => resolve({ kind: 'new', file, preview: reader.result });
      reader.readAsDataURL(file);
    }));

    Promise.all(readers).then((newItems) => {
      const next = [...items, ...newItems];
      setItems(next);
      emitChange(next, deletedIds);
      e.target.value = ''; // allow choosing same file again after removal
    });
  };

  const handleRemoveImage = (index) => {
    const target = items[index];
    const next = items.filter((_, i) => i !== index);

    let nextDeleted = deletedIds;
    if (target?.kind === 'existing' && target.id) {
      nextDeleted = [...deletedIds, target.id];
      setDeletedIds(nextDeleted);
    }

    setItems(next);
    emitChange(next, nextDeleted);
  };

  const getSrc = (item) => (item.kind === 'existing' ? item.url : item.preview);

  return (
    <div className={`flex flex-wrap gap-4 ${containerClass}`}>
      {items.map((item, index) => (
        <div key={index} className="relative">
          <img
            src={getSrc(item)}
            alt="Preview"
            className={`object-cover ${imgClass}`}
          />
          <button
            type="button"
            onClick={() => handleRemoveImage(index)}
            className="absolute top-1 right-1 rounded-full bg-red-500 px-2 py-1 text-xs text-white"
            aria-label="Remove image"
          >
            ✕
          </button>
        </div>
      ))}

      {items.length < maxImages && (
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
