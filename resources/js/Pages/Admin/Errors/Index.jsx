import { Link } from '@inertiajs/react';

export default function Error({ status, error }) {
    return (
        <div className="flex min-h-screen items-center justify-center bg-gradient-to-t from-[#c39be3] to-[#f2eafa]">
            <div className="p-5 text-center font-semibold">
                <h2 className="mb-8 text-[50px] font-bold leading-none md:text-[80px]">
                    Error {status}
                </h2>
                <h4 className="mb-5 text-xl font-semibold text-primary sm:text-5xl">
                    Ooops!
                </h4>
                <p className="p-2 text-base">{error}</p>

                <Link
                    href={route('admin.dashboard')}
                    className="btn btn-primary mx-auto mt-10 w-max"
                >
                    Go to Home
                </Link>
            </div>
        </div>
    );
}
