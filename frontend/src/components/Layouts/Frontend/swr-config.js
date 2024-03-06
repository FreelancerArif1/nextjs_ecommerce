// swr-config.js
import useSWR from 'swr';

const fetcher = async (url) => {
    const response = await fetch(url);
    const data = await response.json();

    if (response.status >= 400) {
        throw new Error(data.message);
    }

    return data;
};

export const useApi = (url) => useSWR(url, fetcher);
