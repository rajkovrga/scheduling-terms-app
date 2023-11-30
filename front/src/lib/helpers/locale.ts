export const getNavigatorLanguage = (): string => {
    if (navigator.languages && navigator.languages.length) {
        return navigator.languages[0];
    }

    if ('userLanguage' in navigator) {
        return navigator.userLanguage as string;
    }

    return navigator.language || 'en';
};