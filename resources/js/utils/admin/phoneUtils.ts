import { parsePhoneNumberFromString } from 'libphonenumber-js';

/**
 * Function to format phone number to international format.
 * @param phoneNumber - The phone number (string).
 * @returns {string | null} - The formatted phone number in international format or null if input is invalid.
 */
export function formatPhoneToIntl(phoneNumber: string | null | undefined): string | null {
    if (!phoneNumber) {
        return null; // Return null if phoneNumber is invalid
    }

    // Parse the phone number and format it in international format
    const parsedNumber = parsePhoneNumberFromString(phoneNumber);

    if (parsedNumber) {
        return parsedNumber.formatInternational(); // Format it in international format
    }

    return null; // Return null if the number is not valid
}

/**
 * Function to format phone number to national format.
 * @param phoneNumber - The phone number (string).
 * @returns {string | null} - The formatted phone number in national format or null if input is invalid.
 */
export function formatPhoneToLocal(phoneNumber: string | null | undefined): string | null {
    if (!phoneNumber) {
        return null; // Return null if phoneNumber is invalid
    }

    // Parse the phone number and format it in national format
    const parsedNumber = parsePhoneNumberFromString(phoneNumber);

    if (parsedNumber) {
        return parsedNumber.formatNational(); // Format it in national format
    }

    return null; // Return null if the number is not valid
}
