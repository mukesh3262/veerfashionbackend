import { forwardRef, useEffect, useImperativeHandle, useRef } from 'react';

export default forwardRef(function TextareaInput(
    { className = '', isFocused = false, ...props },
    ref,
) {
    const localRef = useRef(null);

    useImperativeHandle(ref, () => ({
        focus: () => localRef.current?.focus(),
    }));

    useEffect(() => {
        if (isFocused) {
            localRef.current?.focus();
        }
    }, [isFocused]);

    return (
        <>
            <textarea
                {...props}
                className={
                    `form-input read-only:pointer-events-none read-only:cursor-not-allowed read-only:bg-[#eee] disabled:pointer-events-none disabled:cursor-not-allowed disabled:bg-[#eee] dark:read-only:bg-[#1b2e4b] dark:disabled:bg-[#1b2e4b]` +
                    className
                }
                ref={localRef}
            />
        </>
    );
});
