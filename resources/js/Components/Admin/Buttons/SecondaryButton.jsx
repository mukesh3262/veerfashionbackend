export default function SecondaryButton({
    type = 'button',
    className = '',
    disabled,
    children,
    ...props
}) {
    return (
        <button
            {...props}
            type={type}
            className={
                `rounded-md bg-gray-200 px-4 py-2 font-semibold uppercase transition duration-150 ease-in-out hover:bg-gray-300 ${disabled && 'opacity-25'} ` +
                className
            }
            disabled={disabled}
        >
            {children}
        </button>
    );
}
