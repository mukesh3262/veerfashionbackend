const Pagination = ({ pagination, offset, handlePageChange }) => {
    const maxButton = 5;

    const startPage = () => {
        if (pagination.current_page === 1) {
            return 1;
        }

        if (pagination.current_page === pagination.last_page) {
            return pagination.last_page - maxButton + 1;
        }

        return pagination.current_page - 1;
    };

    const endPage = () => {
        return Math.min(
            startPage() + maxButton - 1,
            Math.ceil(pagination.total / offset),
        );
    };

    const lastPage = Math.ceil(pagination.total / offset);

    const handlePageClick = (page) => {
        handlePageChange(page);
    };

    const handleNextPage = () => {
        const page = pagination.current_page + 1;
        if (page <= lastPage) {
            handlePageChange(page);
        }
    };

    const handlePreviousPage = () => {
        const page = pagination.current_page - 1;
        if (page >= 1) {
            handlePageChange(page);
        }
    };

    const pages = () => {
        const range = [];
        let from = startPage();

        if (from < 1) {
            from = 1;
        }

        for (let i = from; i <= endPage(); i++) {
            range.push({
                name: i,
                isDisabled: i === pagination.current_page,
            });
        }

        return range;
    };

    const currentItemPage = () => {
        if (pagination.current_page) {
            const page = (pagination.current_page - 1) * offset;
            return page <= 0 ? 1 : page;
        }
        return 1;
    };

    const commonClass =
        'flex justify-center bg-white-light px-3 py-2 font-semibold text-dark transition dark:bg-[#191e3a]';
    const disabledClass = 'cursor-not-allowed text-gray-400 dark:text-gray-500';
    const nonDisabledClass =
        'hover:bg-primary hover:text-white dark:text-white-light dark:hover:bg-primary';

    return (
        <nav
            className="flex items-center justify-between pt-4"
            aria-label="Table navigation"
        >
            <p className="flex gap-1 text-sm font-normal text-gray-500 dark:text-gray-400">
                Showing
                <span className="font-semibold text-gray-900 dark:text-white">
                    {currentItemPage()}
                </span>
                -
                <span className="font-semibold text-gray-900 dark:text-white">
                    {pagination.current_page * offset > pagination.total
                        ? pagination.total
                        : pagination.current_page * offset}
                </span>
                out of
                <span className="font-semibold text-gray-900 dark:text-white">
                    {pagination.total}
                </span>
                results
            </p>

            <ul className="inline-flex items-center">
                {/* First Page Button */}
                <li>
                    <button
                        onClick={() => handlePageClick(1)}
                        disabled={pagination.current_page === 1}
                        className={`${commonClass} ${pagination.current_page === 1 ? disabledClass : nonDisabledClass} ltr:rounded-l-full rtl:rounded-r-full`}
                    >
                        <svg
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            className="h-5 w-5 rtl:rotate-180"
                        >
                            <path
                                d="M13 19L7 12L13 5"
                                stroke="currentColor"
                                strokeWidth="1.5"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            />
                            <path
                                opacity="0.5"
                                d="M16.9998 19L10.9998 12L16.9998 5"
                                stroke="currentColor"
                                strokeWidth="1.5"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            />
                        </svg>
                    </button>
                </li>

                {/* Previous Page Button */}
                <li>
                    <button
                        onClick={handlePreviousPage}
                        disabled={pagination.current_page === 1}
                        className={`${commonClass} ${pagination.current_page === 1 ? disabledClass : nonDisabledClass}`}
                    >
                        <svg
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            className="h-5 w-5 rtl:rotate-180"
                        >
                            <path
                                d="M15 5L9 12L15 19"
                                stroke="currentColor"
                                strokeWidth="1.5"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            />
                        </svg>
                    </button>
                </li>

                {/* Page Numbers */}
                {pages().map((page, i) => (
                    <li key={i} className="pagination-item">
                        <button
                            onClick={() => handlePageClick(page.name)}
                            disabled={page.isDisabled}
                            className={`flex justify-center px-3.5 py-2 font-semibold transition ${
                                page.isDisabled
                                    ? 'cursor-not-allowed bg-primary text-white dark:bg-primary dark:text-white-light'
                                    : 'bg-white-light text-dark hover:bg-primary hover:text-white dark:bg-[#191e3a] dark:text-white-light dark:hover:bg-primary'
                            }`}
                        >
                            {page.name}
                        </button>
                    </li>
                ))}

                {/* Next Page Button */}
                <li>
                    <button
                        onClick={handleNextPage}
                        disabled={pagination.current_page === lastPage}
                        className={`${commonClass} ${pagination.current_page === lastPage ? disabledClass : nonDisabledClass}`}
                    >
                        <svg
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            className="h-5 w-5 rtl:rotate-180"
                        >
                            <path
                                d="M9 5L15 12L9 19"
                                stroke="currentColor"
                                strokeWidth="1.5"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            />
                        </svg>
                    </button>
                </li>

                {/* Last Page Button */}
                <li>
                    <button
                        onClick={() => handlePageClick(lastPage)}
                        disabled={pagination.current_page === lastPage}
                        className={`${commonClass} ${pagination.current_page === lastPage ? disabledClass : nonDisabledClass} ltr:rounded-r-full rtl:rounded-l-full`}
                    >
                        <svg
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            className="h-5 w-5 rtl:rotate-180"
                        >
                            <path
                                d="M11 19L17 12L11 5"
                                stroke="currentColor"
                                strokeWidth="1.5"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            />
                            <path
                                opacity="0.5"
                                d="M6.99976 19L12.9998 12L6.99976 5"
                                stroke="currentColor"
                                strokeWidth="1.5"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            />
                        </svg>
                    </button>
                </li>
            </ul>
        </nav>
    );
};

export default Pagination;
