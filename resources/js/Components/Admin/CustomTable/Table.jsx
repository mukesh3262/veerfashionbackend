import React, { useEffect, useState } from 'react';
import PaginationView from './Pagination';
import TableLoader from './TableLoader';

export default function Table({
    data,
    headerData,
    currentSort,
    columnFilter,
    stickyHeader,
    offset,
    handlePageChange,
    handleSortChange,
    handleFilterChange,
    pagination,
    children,
}) {
    const [tableLoader, setTableLoader] = useState(false);

    useEffect(() => {
        setTableLoader(false);
    }, [data]);

    const checkCurrentSorting = () => {
        const [index, dir] = Object.entries(currentSort)[0] || [1, 'desc'];
        return { index, dir };
    };

    return (
        <div className="datatable relative text-sm font-normal text-black antialiased dark:text-gray-400">
            <div
                className={`overflow-auto rounded-t-lg ${tableLoader ? 'z-40 min-h-[300px] opacity-50' : ''}`}
                style={{ height: stickyHeader ? '500px' : 'auto' }}
            >
                <table
                    className={`table-hover whitespace-nowrap ${tableLoader ? 'min-h-[300px]' : ''} `}
                >
                    <thead className={stickyHeader ? 'sticky top-0 z-10' : ''}>
                        <tr>
                            {headerData?.map((data) => (
                                <th
                                    key={data.field}
                                    className={`z-[1] select-none ${
                                        typeof data?.sort === 'undefined' ||
                                        data?.sort !== false
                                            ? 'cursor-pointer'
                                            : ''
                                    }`}
                                >
                                    <div
                                        className={`flex items-center ${data.headerClass ? data.headerClass : ''}`}
                                        onClick={() =>
                                            data?.sort
                                                ? handleSortChange(data.field)
                                                : null
                                        }
                                    >
                                        {data.title}
                                        {data?.sort && (
                                            <span
                                                className={`sort ml-3 flex items-center ${
                                                    typeof data?.sort ===
                                                        'undefined' ||
                                                    data?.sort !== false
                                                        ? `${checkCurrentSorting().index === data.field ? checkCurrentSorting().dir : ''}`
                                                        : ''
                                                }`}
                                            >
                                                <svg
                                                    width="16"
                                                    height="16"
                                                    viewBox="0 0 14 14"
                                                    fill="none"
                                                >
                                                    <polygon
                                                        points="3.11,6.25 10.89,6.25 7,0.5 "
                                                        fill="currentColor"
                                                        className={`text-black/20 ${
                                                            checkCurrentSorting()
                                                                .index ===
                                                                data.field &&
                                                            checkCurrentSorting()
                                                                .dir === 'asc'
                                                                ? 'text-primary dark:text-white'
                                                                : 'dark:text-gray-500'
                                                        } `}
                                                    />
                                                    <polygon
                                                        points="7,14 10.89,7.75 3.11,7.75 "
                                                        fill="currentColor"
                                                        className={`text-black/20 ${
                                                            checkCurrentSorting()
                                                                .index ===
                                                                data.field &&
                                                            checkCurrentSorting()
                                                                .dir === 'desc'
                                                                ? 'text-primary dark:text-white'
                                                                : 'dark:text-gray-500'
                                                        } `}
                                                    />
                                                </svg>
                                            </span>
                                        )}
                                    </div>
                                </th>
                            ))}
                        </tr>

                        {columnFilter && (
                            <tr>
                                {headerData?.map((data, i) => {
                                    return (
                                        <th
                                            key={data?.field}
                                            className="mb-3 first:rounded-bl-lg last:rounded-br-lg"
                                        >
                                            {data?.filter && (
                                                <div className="relative filter">
                                                    {/* String Filter */}
                                                    {data?.type ===
                                                        'string' && (
                                                        <>
                                                            <input
                                                                value={
                                                                    data.value
                                                                }
                                                                onChange={(e) =>
                                                                    handleFilterChange(
                                                                        e.target
                                                                            .value,
                                                                        i,
                                                                    )
                                                                }
                                                                type="text"
                                                                className="form-input w-auto"
                                                                placeholder={`Enter ${data.title} ...`}
                                                            />
                                                        </>
                                                    )}

                                                    {/* Number Filter */}
                                                    {data?.type ===
                                                        'number' && (
                                                        <input
                                                            type="number"
                                                            className="form-input w-auto"
                                                            value={data.value}
                                                            onChange={(e) =>
                                                                handleFilterChange(
                                                                    parseFloat(
                                                                        e.target
                                                                            .value,
                                                                    ),
                                                                    i,
                                                                )
                                                            }
                                                        />
                                                    )}

                                                    {/* Date Filter */}

                                                    {/* Boolean Filter */}
                                                    {data?.type === 'bool' && (
                                                        <select
                                                            value={data.value}
                                                            onChange={(e) =>
                                                                handleFilterChange(
                                                                    e.target
                                                                        .value,
                                                                )
                                                            }
                                                            className="form-input w-auto"
                                                        >
                                                            <option value="">
                                                                All
                                                            </option>
                                                            <option
                                                                value={true}
                                                            >
                                                                True
                                                            </option>
                                                            <option
                                                                value={false}
                                                            >
                                                                False
                                                            </option>
                                                        </select>
                                                    )}

                                                    {/* Select Filter */}
                                                    {data?.type ===
                                                        'select' && (
                                                        <select
                                                            defaultValue={
                                                                data.value
                                                            }
                                                            onChange={(e) =>
                                                                handleFilterChange(
                                                                    e.target
                                                                        .value,
                                                                    i,
                                                                )
                                                            }
                                                            className="form-select w-auto"
                                                        >
                                                            {data?.filterOptions &&
                                                                data?.filterOptions?.map(
                                                                    (
                                                                        option,
                                                                        index,
                                                                    ) => {
                                                                        return (
                                                                            <option
                                                                                value={
                                                                                    option.value
                                                                                }
                                                                                key={
                                                                                    index
                                                                                }
                                                                            >
                                                                                {
                                                                                    option.label
                                                                                }
                                                                            </option>
                                                                        );
                                                                    },
                                                                )}
                                                        </select>
                                                    )}
                                                </div>
                                            )}
                                        </th>
                                    );
                                })}
                            </tr>
                        )}
                    </thead>

                    <tbody>
                        {data &&
                            data.map((row, rowIndex) => (
                                <tr key={rowIndex}>
                                    {headerData.map((col, colIndex) => {
                                        let fieldValue;
                                        // Handle 'sr_no' separately
                                        if (col.field === 'sr_no') {
                                            fieldValue =
                                                (pagination.current_page - 1) *
                                                    pagination.per_page +
                                                (rowIndex + 1);
                                        } else if (col.field.includes('.')) {
                                            // Safely access nested properties
                                            fieldValue = col.field
                                                .split('.')
                                                .reduce(
                                                    (acc, key) => acc?.[key],
                                                    row,
                                                );
                                        } else {
                                            // Direct access for non-nested fields
                                            fieldValue = row[col.field];
                                        }

                                        const child = React.Children.toArray(
                                            children,
                                        ).find(
                                            (child) =>
                                                React.isValidElement(child) &&
                                                child.props.field === col.field,
                                        );

                                        return (
                                            <td
                                                key={colIndex}
                                                className="max-w-xs whitespace-normal"
                                            >
                                                {child
                                                    ? React.cloneElement(
                                                          child,
                                                          {
                                                              value: fieldValue,
                                                              row,
                                                          },
                                                      )
                                                    : fieldValue}
                                            </td>
                                        );
                                    })}
                                </tr>
                            ))}
                        {data.length === 0 && !tableLoader && (
                            <tr>
                                <td
                                    colSpan={headerData?.length}
                                    style={{ textAlign: 'center' }}
                                >
                                    No Data Found
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>

                {tableLoader && (
                    <div className="bg-blue-light/50 absolute inset-0 grid place-content-center">
                        <TableLoader />
                    </div>
                )}

                {data.length !== 0 && (
                    <PaginationView
                        pagination={pagination}
                        offset={offset}
                        handlePageChange={handlePageChange}
                    />
                )}
            </div>
        </div>
    );
}

// Define a static sub-component for Table.Cell
Table.Cell = function TableCell({ value, children }) {
    if (children) {
        return children({ value });
    }
    return <span>{value}</span>;
};
