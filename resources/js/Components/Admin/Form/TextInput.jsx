import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
    forwardRef,
    useEffect,
    useImperativeHandle,
    useRef,
    useState,
} from 'react';

export default forwardRef(function TextInput(
    { type = 'text', className = '', isFocused = false, ...props },
    ref,
) {
    const localRef = useRef(null);

    const [isPasswordVisible, setPasswordVisible] = useState(false);

    const togglePasswordVisibility = () =>
        setPasswordVisible((v) => {
            localRef.current.type = v ? 'password' : 'text';
            return !v;
        });

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
            {type === 'password' ? (
                <div className="flex">
                    <input
                        {...props}
                        type={type}
                        className={
                            `${type === 'password' ? 'rounded-r-none' : ''} form-input read-only:pointer-events-none read-only:cursor-not-allowed read-only:bg-[#eee] disabled:pointer-events-none disabled:cursor-not-allowed disabled:bg-[#eee] dark:read-only:bg-[#1b2e4b] dark:disabled:bg-[#1b2e4b]` +
                            className
                        }
                        ref={localRef}
                    />
                    <button
                        type="button"
                        onClick={togglePasswordVisibility}
                        className="flex items-center justify-center border border-[#e0e6ed] bg-[#eee] px-3 font-semibold dark:border-[#17263c] dark:bg-[#1b2e4b] ltr:rounded-r-md ltr:border-l-0 rtl:rounded-l-md rtl:border-r-0"
                    >
                        {isPasswordVisible ? (
                            <FontAwesomeIcon
                                icon="fas fa-eye"
                                className="text-base"
                            />
                        ) : (
                            <FontAwesomeIcon icon="fas fa-eye-slash" />
                        )}
                    </button>
                </div>
            ) : (
                <input
                    {...props}
                    type={type}
                    className={
                        `${type === 'password' ? 'rounded-r-none' : ''} form-input read-only:pointer-events-none read-only:cursor-not-allowed read-only:bg-[#eee] disabled:pointer-events-none disabled:cursor-not-allowed disabled:bg-[#eee] dark:read-only:bg-[#1b2e4b] dark:disabled:bg-[#1b2e4b]` +
                        className
                    }
                    ref={localRef}
                />
            )}
        </>
    );
});
