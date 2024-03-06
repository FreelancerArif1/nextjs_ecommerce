import Head from "next/head";

const Meta = ({title, keywords, decription}) => {
    return (
        <Head>
            <title>{title}</title>
            <meta charset="utf-8"></meta>
            <meta name="description" content={decription} />
            <meta name="keywords" content={keywords} />
            <meta name="viewport" content="width=device-width, initial-scale=1"/>
            <meta name="robots" content="noindex,nofollow" />
        </Head>
    );
}

Meta.defaultProps = {
    title: 'dhroobo',
    keywords: 'dhroobo keywords',
    decription: 'dhroobo decription',
}

export default Meta;