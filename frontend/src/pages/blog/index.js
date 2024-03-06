import FrontendLayouts from '@/components/Layouts/Frontend/FrontendLayouts'
import Head from 'next/head'
import Image from 'next/image'
import Meta from "@/components/Meta";
import Link from 'next/link';
import { useEffect, useState } from 'react';
const Index = () => {
    const [loadBlogs, setloadBlogs] = useState([]);
    const [pagenumber, setPage] = useState(1);

    const blog = async () => {
      try {
          const response = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-blogs/?page=`+pagenumber);
          const data = await response.json();
          setloadBlogs(data.blog.data);
          var mystring = data.blog.next_page_url;
          if(mystring){
            var slpited_string = mystring.split('=');
            setPage(slpited_string[1]);
          }else{
            setPage(0);
          }

      }catch (error) {
  
      }
    }

    const loadMore = async () => {
    //const nextPage = pagenumber + 1;
      try{
          const response = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-blogs/?page=`+pagenumber);
          const data = await response.json();
          setloadBlogs([...loadBlogs, ...data.blog.data]);
         var mystring = data.blog.next_page_url;
         if(mystring){
           var slpited_string = mystring.split('=');
           setPage(slpited_string[1]);
         }else{
            setPage(0);
         }
      }catch (error) {
  
      }
    }


    useEffect(() => {
        blog();
    }, []); 
    return (
        <FrontendLayouts>
            <Meta title="Blog | Dhroobo" keywords="dhroobo keywords" decription="dhroobo decription" />
            <div className="blogpage">
                <div className="container all_sellers">
                    {loadBlogs.length > 0 ? (
                    <div className="row"> 
                        {loadBlogs.map((data, index) => (
                        <div key={index} className="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div className="single_blog p-2">
                                    <Link href={`${'/blog'}/${data.slug}`}> <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/media/thumbnail/${data.image}`} width={200}  height={200}  alt="" /> </Link>
                                    <div className="single_blog_description">
                                        <Link href={`${'/blog'}/${data.slug}`}> <h5 className='mb-2 mt-2'> { data.title } </h5> </Link>
                                        {data.specification > 0 ? ( 
                                        <div className="blog_html_text pb-2" dangerouslySetInnerHTML={{ __html: data.specification.substr(0, 150) }} />
                                        ):('')}
                                        <p className="text-right"><Link href={`${'/blog'}/${data.slug}`} className="btn btn-animation text-light btn-sm"> View More </Link></p>
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
                            <h4> Blog Not Found</h4>
                        </div>
                    </div>
                    )}
                
                </div>
            </div>
        </FrontendLayouts>
    )






}
export default Index
