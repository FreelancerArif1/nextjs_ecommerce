import Slider from 'react-slick'
import Image from 'next/image'


import { useEffect, useState } from 'react'
import Link from 'next/link';
import axios from 'axios';


import 'slick-carousel/slick/slick.css'
import 'slick-carousel/slick/slick-theme.css'

export default function Blog() {

    const settings = {
        dots: false,
        infinite: true,
        speed: 500,
        slidesToShow: 3,
        slidesToScroll: 1,
        initialSlide: 3,
        autoplay: true,
        pauseOnHover: true,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 3,
              infinite: true,
              dots: true
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
              initialSlide: 2
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
      }





  const [blogs, setloadBlogs2] = useState([]);
  const [pagenumber, setPage] = useState(1);

  const blog = async () => {
    try {
        const response = await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-blogs/?page=`+pagenumber);
        setloadBlogs2(response.data.blog.data);
    }catch (error) {

    }
  }
useEffect(() => {
    blog();
}, []);



  return (
    <div className='content'>

    {blogs.length > 0 ? (
      <Slider {...settings}>
      {blogs?.map((data, index) => (
          <div key={index}>
                <div className="blog-box mb-2">
                    <div className="blog-box-image">
                          <Link href={`${'/blog'}/${data?.slug}`} className="blog-image blog-image-home-page">
                            <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/media/thumbnail/${data?.image}`} className="bg-img blur-up lazyload"
                                 width={380} height={245} alt=""/>
                        </Link>
                    </div>

                      <Link href={`${'/blog'}/${data.slug}`} className="blog-detail">
                        <h5> { data?.title }</h5>
                    </Link>
                </div>

          </div>
        ))}
      </Slider>
      ):('')}

    </div>
  )
}