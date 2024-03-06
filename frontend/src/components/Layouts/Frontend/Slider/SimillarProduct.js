import Slider from 'react-slick'
import Image from 'next/image'

import 'slick-carousel/slick/slick.css'
import 'slick-carousel/slick/slick-theme.css'
import { useRouter } from 'next/router'
import { useEffect, useState, React } from 'react';
import SingleProduct from "@/components/SingleProduct";


export default function SimillarProduct({id, categoryId}) {
    const settings = {
        dots: true,
        infinite: true,
        speed: 500,
        slidesToShow: 4,
        slidesToScroll: 1,
        initialSlide: 4,
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




const router = useRouter();
const slug = router.query.slug;



const [product_simillar_status, setproduct_status] = useState([]);
const [simillar_products, setproduct_simillar] = useState([]);
const loadSimillerProduct = async () => {
    try {
      let formData = new FormData();
      formData.append('produt_id', id);
      formData.append('categoryId', categoryId);
      const response = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-simillar-product`, formData);
      const data = await response.json();
      setproduct_status(data.status);
      setproduct_simillar(data.simillar_products);
    } catch (error) {
  
    }
  }

useEffect(() => {
    if(slug) {
        loadSimillerProduct();
    }
}, [slug]); 


  return (
    <div className='simillerComponent'>
        {product_simillar_status > 0 ?  (
        <> 
        {simillar_products > 4 ?  (
        <div className='content'>
        <Slider {...settings}>
            {simillar_products.map((data, index) => (
            <div key={index}>
                <SingleProduct data={data}></SingleProduct>
            </div>
            ))}
        </Slider>
        </div>
        ):(
            <div className='row'>
                {simillar_products.map((data, index) => (
                <div key={index} className='col-md-3'>
                    <SingleProduct data={data}></SingleProduct>
                </div>
                ))}
            </div>
        )}
        </>
        ):(
            <div className='content'>Not Found.</div>
        )}
    </div>


  )
}