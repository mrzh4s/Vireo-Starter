/**
 * Convert a relative URL to an absolute URL
 * @param {string} pathname - The relative path
 * @returns {string} The absolute URL
 */
export const toAbsoluteUrl = (pathname) => {
  return import.meta.env.BASE_URL + pathname;
};
