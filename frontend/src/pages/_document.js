import Document, { Html, Head, Main, NextScript } from 'next/document'
import React from "react";
import Image from 'next/image'
class MyDocument extends Document {
    static async getInitialProps(ctx) {
        const initialProps = await Document.getInitialProps(ctx)
        return { ...initialProps }
    }

    render() {
        return (
            <Html>
                <Head>


                        
                        <link rel="stylesheet" type="text/css" href="/assets/css/vendors/bootstrap.css" />
                        {/* <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" integrity="sha512-GQGU0fMMi238uA+a/bdWJfpUGKUkBdgfFdgBm72SUQ6BeyWjoY/ton0tEjH+OSH9iP4Dfh+7HM0I9f5eR0L/4w==" crossOrigin="anonymous" referrerPolicy="no-referrer" /> */}
                        <link rel="stylesheet" href="/assets/css/animate.min.css" />
                        {/* <link rel="stylesheet" type="text/css" href="/assets/css/vendors/font-awesome.css" /> */}
                        <link rel="stylesheet" type="text/css" href="/assets/css/vendors/feather-icon.css" />
                        <link rel="stylesheet" type="text/css" href="/assets/css/vendors/slick/slick.css" />
                        <link rel="stylesheet" type="text/css" href="/assets/css/vendors/slick/slick-theme.css" />
                        <link rel="stylesheet" type="text/css" href="/assets/css/bulk-style.css" />
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossOrigin="anonymous" referrerPolicy="no-referrer" />
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw==" crossOrigin="anonymous" referrerPolicy="no-referrer" />
                        {/* <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/> */}
                        <link id="color-link" rel="stylesheet" type="text/css" href="/assets/css/style.css" />

                        <link rel="stylesheet" href="/assets/css/custom.css"/> 
                        

                </Head>
                <body className="antialiased">
                    <Main />
                    <NextScript />
        
                    <script src="/assets/js/jquery-3.6.0.min.js"></script>
                    <script src="/assets/js/jquery-ui.min.js"></script>
                    {/* <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.min.js" integrity="sha512-OvBgP9A2JBgiRad/mM36mkzXSXaJE9BEIENnVEmeZdITvwT09xnxLtT4twkCa8m/loMbPHsvPl0T8lRGVBwjlQ==" crossOrigin="anonymous" referrerPolicy="no-referrer"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js" integrity="sha512-pax4MlgXjHEPfCwcJLQhigY7+N8rt6bVvWLFyUMuxShv170X53TRzGPmPkZmGBhk+jikR8WBM4yl7A9WMHHqvg==" crossOrigin="anonymous" referrerPolicy="no-referrer"></script> */}
                    {/* <script src="/assets/js/bootstrap/bootstrap.bundle.min.js"></script>
                    <script src="/assets/js/bootstrap/bootstrap-notify.min.js"></script>
                    <script src="/assets/js/bootstrap/popper.min.js"></script> */}
                    {/* <script src="/assets/js/feather/feather.min.js"></script>
                    <script src="/assets/js/feather/feather-icon.js"></script> */}
                    <script src="/assets/js/lazysizes.min.js"></script>
                    {/* <script src="/assets/js/slick/slick.js"></script> */}
                    
                    <script src="/assets/js/auto-height.js"></script>
                    {/* <script src="/assets/js/timer1.js"></script> */}
                    <script src="/assets/js/fly-cart.js"></script>
                    <script src="/assets/js/quantity-2.js"></script>
                    <script src="/assets/js/wow.min.js"></script>
                    <script src="/assets/js/custom-wow.js"></script>
                    {/* <script src="/assets/js/script.js"></script> */}
                    {/* <script src="/assets/js/theme-setting.js"></script> */}
                    {/* <script src="/assets/js/slick/custom_slick.js"></script> */}
                    <script src="/assets/js/singleProduct.js"></script>
                    <script src="/assets/js/myzoom-image.js"></script>
                    <script src="/assets/js/custom.js"></script>
                    
                    

                </body>
            </Html>
        )
    }
}

export default MyDocument
