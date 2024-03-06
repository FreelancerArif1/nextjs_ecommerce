

import FrontendLayouts from '@/components/Layouts/Frontend/FrontendLayouts'
import Head from 'next/head'
import Image from 'next/image'
import Meta from "@/components/Meta";
import { useRouter } from 'next/router'
import { useEffect, useState, React } from 'react';
import axios from 'axios';
import { getSession, useSession } from 'next-auth/react';
import Link from 'next/link';
import SingleProduct from "@/components/SingleProduct";



export default function Shop() {
    const router = useRouter();
    const slug = router.query.slug;
    const { data:userdata, status, update, session }= useSession();

    
    const [ratings, setratings] = useState(null);
    const [SellerProfile, setSellerProfile] = useState(null);
    const [sellerLogo, setsellerLogo] = useState(null);
    const [sellerbanner, setsellerbanner] = useState(null);

    const styling = {
        backgroundImage: `url('${process.env.NEXT_PUBLIC_BACKEND_URL+''+sellerbanner}')`,
    }



    const [searchProduct, setsearchProduct] = useState(null);
    const [pagenumber, setPage] = useState(1);


    const load_search_product = async () => {
        const slug = router.query.slug;
        let formData = new FormData();
        formData.append('shop_slug', slug);
        formData.append('page', pagenumber);
        try {
            const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/search`, formData, {
                headers: {
                    Authorization: `Bearer ${userdata?.accessToken}`,
                },
            });
            setsearchProduct(response.data.products.data);
            var mystring = response.data.products.next_page_url;
            if(mystring){
                var slpited_string = mystring.split('=');
                setPage(slpited_string[1]);
              }else{
                setPage(0);
              }

        } catch (error) {
            console.log('error ', error);
        }
    }






    const loadMore = async () => {
        $('.btn-animation').html('<span class="spinner-border spinner-border-sm"></span>');
        const slug = router.query.slug;
        let formData = new FormData();
        formData.append('shop_slug', slug);
        formData.append('page', pagenumber);
        try{
            const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/search`, formData, {
                headers: {
                    Authorization: `Bearer ${userdata?.accessToken}`,
                },
            });
            setsearchProduct([...searchProduct, ...response.data.products.data]);
            var mystring =data.products.next_page_url;
            if(mystring){
                var slpited_string = mystring.split('=');
                setPage(slpited_string[1]);
            }else{
                setPage(0);
            }
            $('.btn-md').text('Load more');
        }catch (error) {
            console.log('load more error=', error);
        }
    }






    const load_products_by_shop_slug = async () => {
        try {
            const response = await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-products-by-seller-slug/`+slug);
            setratings(response.data.ratings);
            setSellerProfile(response.data.profile);
            setsellerLogo(response.data.profile.shopinfo.logo);
            setsellerbanner('/'+response.data.profile.shopinfo.banner);
        } catch (error) {
            console.log('error ', error);
        }



    }







    useEffect(() => {
        if(slug) {
            load_search_product();
            load_products_by_shop_slug();
        }
    }, [slug]); 

    return (
    <FrontendLayouts>
        <Meta title= {`${'Shop'} | Dhroobo`} keywords="dhroobo keywords" decription="dhroobo decription" />
        























        <div className="brand_banner">
            <div className="container custom_container">
                <div className="row">
                    <div className="col-md-12 p-0 mb-3">
                        <div className="brand_banner_bg" style={styling}>
                            <div className="row">
                                <div className="col-md-4 brand_logo">
                                    <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/media/thumbnail/${sellerLogo}`} width={100}  height={100}  alt="" /> 
                                </div>
                                <div className="col-md-4"></div>
                                <div className="col-md-4"></div>
                            </div>
                        </div>
                    </div>
            
                
                </div>
            </div>
        </div>


        <div id="search-page" className="shop_responsive">
            <div className="container custom_container p-0">
                <div className="row search filter_responsive_row">
                    <div className="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 search_product_div"> 
                        {searchProduct ? (
                            <div className="row search_products col_padding">
                                {searchProduct?.map((data, index) => (
                                    <div key={index} className="col-6 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-3">
                                        <SingleProduct data={data}> </SingleProduct>
                                    </div>
                                ))}
                            </div>
                        ):(
                            <div className="row">
                                <div className='title'>
                                    <h2>Product not Found.</h2>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>


    



    </FrontendLayouts>
    )
}




