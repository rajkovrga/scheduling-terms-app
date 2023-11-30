import { DateTime } from 'luxon';

import { getNavigatorLanguage } from './locale';

export const getTimeZone = () => Intl.DateTimeFormat().resolvedOptions().timeZone;

export const DISPLAY_FORMAT = 'dd-mm-yyyy hh:ii';
export const DISPLAY_FORMAT_INPUT = 'dd-MM-yyyy HH:mm';

export const formatDisplayDate = (
    date: string | undefined,
    { locale }: { zone?: string; locale?: string }
): string | undefined => {
    if (!date) {
        return undefined;
    }

    return DateTime.fromISO(date, { setZone: true }).toFormat(DISPLAY_FORMAT_INPUT, {
        locale: locale ?? getNavigatorLanguage()
    });
};

export const formatInputDate = (
    date: string | undefined,
    { zone, locale }: { zone?: string; locale?: string }
): string | undefined => {
    if (!date) {
        return undefined;
    }

    let newDate =
        DateTime.fromFormat(date, DISPLAY_FORMAT_INPUT, {
            zone: zone ?? getTimeZone(),
            locale: locale ?? getNavigatorLanguage()
        }).toISO({
            suppressMilliseconds: true,
            includeOffset: true,
            includePrefix: true,
            suppressSeconds: false,
            format: 'extended',
            extendedZone: false
        }) ?? '';

    if (newDate.indexOf('Z') === newDate.length - 1) {
        newDate = newDate.slice(0, newDate.length - 1) + '+00:00';
    }

    return newDate;
};