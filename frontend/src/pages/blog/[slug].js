

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



export default function Page() {
    const router = useRouter();
    const slug = router.query.slug;
    const { data:userdata, status, update, session }= useSession();

    //console.log('slug=', slug);

    const [singleBlog, setSingleBlog] = useState(null);
    const [latestBlogs, setLatestBlog] = useState(null);
    const [products, setproducts] = useState(null);
    const loadSingleBlog = async () => {

        try {
            const response = await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-single-blog/`+slug,{
                headers: {
                    Authorization: `Bearer ${userdata?.accessToken}`,
                },
            });
            setSingleBlog(response.data.blog);
            setLatestBlog(response.data.latestBlogs);
            setproducts(response.data.relatedProducts);
        } catch (error) {
            console.log('error ', error);
        }

    }


    useEffect(() => {
        if (slug) {
            loadSingleBlog();
        }
        
    }, [slug]); 

    return (
        <FrontendLayouts>
        <Meta title= {`${'Blog'} | Dhroobo`} keywords="dhroobo keywords" decription="dhroobo decription" />
        
        <section id="blog_single_page">
            <div className="container all_sellers">
                {singleBlog ? (
                <span>
                    <div className="row blog_single_page mt-2">
                        <div className="col-md-7 pl-0">
                        <div className="image-section">
                            <div className="mycontainer">
                                <div className="zoom-show" href={`${'/blog'}/${singleBlog.slug}`}>
                                    <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/media/thumbnail/${singleBlog.image}`} id="show-img" width={300} height={300} className="blog_default_image"/>
                                </div>
                            </div>
        

                            </div>
                            <div className="blog_single_page_deatails pt-3">
                                <div className='title'>
                                    <h2>{ singleBlog.title }</h2>
                                </div> 
                                <div  className="pb-5 dangerouslySetInnerHTML" dangerouslySetInnerHTML={{ __html: singleBlog?.description }} />
                            </div>
                            <hr/>
                            <div className="row">
                            <div className="latest_blogs">
                                <div className='col-md-12 title'>
                                    <h2>Latest Blogs</h2>
                                </div> 

                                <div className="row"> 
                                {latestBlogs?.map((data, index) => ( 
                                    <div key={index} className="col-6 col-sm-6 col-md-3">
                                        <Link href={`${'/blog'}/${data.slug}`}> 
                                            <Image  src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/media/thumbnail/${data.image}`} width={100}  height={100}  alt="" />
                                        </Link>
                                        <br/>
                                        {data.title ? (<b>{ data.title.substr(0, 100) }</b>):('')}
                                        {data.specification ? (<p>{ data.specification.substr(0, 50)+".." }</p>):('')}
                                    </div>
                                ))}
                                </div>
                            </div>
                        </div>

                        </div>
                
                        <div className="col-md-5">
                            <div className='title'>
                                <h2>Releted Products</h2>
                            </div> 
                            <hr/>
                            <div className="row search_products promotion product_wrapper">
                                {products?.map((data, index) => ( 
                                <div key={index} className="col-sm-6 col-md-61 col-lg-6">
                                    <SingleProduct data={data}> </SingleProduct>
                                </div>
                                ))}
                            </div>
                        </div>

                    </div>

                </span>
                ):(
                <span v-else>
                    <div className="row promotion_page">
                        {/* <div className="col-md-12">
                            <Image  src="/images/no-blog.webp" width={320}  height={200}  alt="" />
                            <h4> Blog Not Found  </h4>
                            <p> <Link href="{name: 'blog'}" className="Link-active"> Continue to blog list. </Link></p>
                        </div> */}
                    </div>
                </span>
                )}
            </div>
        </section>
    



        </FrontendLayouts>
    )
}




