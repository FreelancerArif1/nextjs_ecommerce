

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
import { useDispatch } from 'react-redux';


export default function Category() {
    const router = useRouter();
    const slug = router.query.slug;
    const { data:userdata, status, update, session }= useSession();
    const [searchProduct, setsearchProduct] = useState(null);
    const [pagenumber, setPage] = useState(1);
    const load_search_product = async () => {
        const slug = router.query.slug;
        let formData = new FormData();
        formData.append('slug', slug);
        formData.append('page', pagenumber);
        formData.append('upazila_id', localStorage.getItem('upazail_id'));
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
        formData.append('slug', slug);
        formData.append('page', pagenumber);
        formData.append('upazila_id', localStorage.getItem('upazail_id'));
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



    useEffect(() => {
        if(slug) {
            load_search_product();
        }
    }, [slug]); 

    return (
        <FrontendLayouts>
        <Meta title= {`${'Product'} | Dhroobo`} keywords="dhroobo keywords" decription="dhroobo decription" />
        <section id="category_page">
        <div className='container'>
            <div className="col-custome-12">
            {searchProduct ? (
                <>
                <div className="row">
                    {searchProduct?.map((data, index) => (
                        <div key={index} className='col-xxl-4 col-xl-3 col-lg-2 col-md-3 col-2 product-list-section' id='product_page'>
                              <SingleProduct data={data}> </SingleProduct>
                        </div>
                    ))}
                </div>
                
                {pagenumber > 0 ? (
                    <div className="row loadMoreBtn">
                        <div className="col-md-12 text-center mt-3 mb-3">
                            <a className='btn btn-animation text-light btn-md' onClick={() => loadMore()}>Load More</a>
                        </div>
                    </div>
                    ):('')}
                </>
            ):(
                <div className="row">
                    <div className='title'>
                        <h2>Product not Found.</h2>
                    </div>
                </div>
            )}
            </div>
        </div>
        </section>
        </FrontendLayouts>
    )
}




