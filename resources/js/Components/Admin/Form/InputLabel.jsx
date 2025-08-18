export default function InputLabel({
    value,
    className = '',
    children,
    required = false,
    ...props
}) {
    return (
        <label
            {...props}
            className={
                `block text-sm font-semibold text-gray-700 dark:text-gray-300 ` +
                className
            }
        >
            {value ? value : children}
            {required && <span className="text-danger">*</span>}
        </label>
    );
}
