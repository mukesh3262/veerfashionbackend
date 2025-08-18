export default function Checkbox({ children, className = '', ...props }) {
    return (
        <label className="cursor-pointer select-none">
            <input
                {...props}
                type="checkbox"
                className={'form-checkbox ' + className}
            />
            {children}
        </label>
    );
}
