import FrontendLayouts from '@/components/Layouts/Frontend/FrontendLayouts'
import Head from 'next/head'
import Image from 'next/image'
import Meta from "@/components/Meta";
import Link from 'next/link';
import { useRouter } from 'next/router'
import { useEffect, useState, React } from 'react';
import axios from 'axios';
const Index = () => {

    

    const [sellers, setSellers] = useState(null);
    const [pagenumber, setPage] = useState(1);

    const load_sellers = async () => {
        // let formData = new FormData();
        // formData.append('page', pagenumber);
        try {
            const response = await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-all-sellers?page=`+pagenumber);
            setSellers(response.data.sellers.data);
            var mystring = response.data.sellers.next_page_url;
            if(mystring){
                var slpited_string = mystring.split('=');
                setPage(slpited_string[1]);
              }else{
                setPage(0);
              }

        } catch (error) {
            console.log('catch error ', error);
        }
    }





    const loadMore = async () => {
        try{
            const response = await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-all-sellers?page=`+pagenumber);
            setSellers([...sellers, ...response.data.sellers.data]);
            var mystring =response.data.sellers.next_page_url;
            if(mystring){
                var slpited_string = mystring.split('=');
                setPage(slpited_string[1]);
            }else{
                setPage(0);
            }
        }catch (error) {
            console.log('load more error=', error);
        }
    }



    useEffect(() => {
        load_sellers();
    }, []); 


    return (
        <FrontendLayouts>
            <Meta title="Blog | Dhroobo" keywords="dhroobo keywords" decription="dhroobo decription" />
            <div className="blogpage">
                <div className="container all_sellers">
                    {sellers ? (
                    <div className="row"> 
                        {sellers.map((seller, index) => (
                            <div key={index} className="col-12 col-sm-12 col-md-3 col-lg-2 mb-4">
                                <div className="sinlge_seller_box">
                                    {seller.shop_verified.if_veryfied ? (
                                    <div className="seller_flagship"><span> <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/media/${seller.shop_verified.veryfied_banner}`} width={100}  height={50}  alt="" />   </span></div>
                                    ):('')}
                                
                                    <div className="seller_border">
                                        <Link href={`${'/shop'}/${seller.slug}`} title={seller.name}>
                                            <Image className="shop_logo" src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/media/thumbnail/${seller.logo}`} width={100}  height={100}  alt="" /> 
                                                <p className='visit_store_a_link'>{ seller.name }</p>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        ))}
                        {pagenumber > 0 ? (
                            <div className="row loadMoreBtn">
                                <div className="col-md-12 text-center mt-3 mb-3">
                                    <a className='btn btn-animation text-light btn-md' onClick={() => loadMore()}>Load More</a>
                                </div>
                            </div>
                        ):('')}
                    </div>
                    ):(
                        <div className="row promotion_page blog_not_found blog_not_found_blog_page">
                            <div className="col-md-12">
                                <h4> Seller Not Found</h4>
                            </div>
                        </div>
                    )}
                
                </div>
            </div>
        </FrontendLayouts>
    )






}
export default Index
