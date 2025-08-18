import { format } from 'date-fns';

export const DEFAULT_FORMAT = "d MMM yyyy HH:mm:ss (z)";

/**
 * A utility function to get the ordinal suffix for a day.
 * @param day - The day of the month.
 * @returns The day with the ordinal suffix.
 */
function getOrdinalSuffix(day: number): string {
    const j = day % 10;
    const k = day % 100;

    if (j === 1 && k !== 11) return `${day}st`;
    if (j === 2 && k !== 12) return `${day}nd`;
    if (j === 3 && k !== 13) return `${day}rd`;
    return `${day}th`;
}

/**
 * A utility function to process and format dates in various formats.
 * It handles ISO strings, Date objects, timestamps, and invalid dates.
 * @param date - The date to process (can be ISO string, Date object, timestamp, etc.)
 * @param dateFormat - The optional format for the output. Defaults to `DEFAULT_FORMAT`.
 * @param extra - The optional extra parameter (e.g., 'ordinal') for special formatting.
 * @returns The formatted date string or a fallback value ('-' if the date is invalid).
 */
export function processDate(
    date: string | Date | number | null | undefined,
    dateFormat: string = DEFAULT_FORMAT,
    extra?: 'ordinal'
): string {
    // Handle null, undefined, or invalid dates
    if (date == null || isNaN(new Date(date).getTime())) {
        return '-';
    }

    const parsedDate = new Date(date);

    // Check if 'ordinal' is requested
    if (extra === 'ordinal') {
        // Extract the day and append the ordinal suffix
        const day = format(parsedDate, 'd'); // Get the day without leading zero
        const formattedDayWithOrdinal = getOrdinalSuffix(parseInt(day, 10));

        // Replace the day portion in the format with the ordinal day
        return format(parsedDate, dateFormat).replace(day, formattedDayWithOrdinal);
    }

    // Default formatting
    return format(parsedDate, dateFormat);
}
